<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Exports\BarangExport;
use App\Imports\BarangImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::all();
        return view('barang.index', compact('barangs'));
    }

    /**
     * Show form create
     */
    public function create()
    {
        return view('barang.create');
    }

    /**
     * Store barang baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100|unique:barangs,nama_barang',
            'kategori' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:20',
        ], [
            'nama_barang.unique' => 'Nama barang sudah ada, tidak boleh duplikat.',
        ]);

        // ambil barang terakhir
        $lastBarang = Barang::latest('id')->first();

        if ($lastBarang) {
            $lastNumber = (int) substr($lastBarang->kode_barang, 4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $kodeBarang = 'BRG-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        Barang::create([
            'kode_barang' => $kodeBarang,
            'nama_barang' => $request->nama_barang,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'harga_beli' => $request->harga_beli,
            'satuan' => $request->satuan,
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * Show form edit
     */
    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    /**
     * Update barang
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100|unique:barangs,nama_barang,' . $barang->id,
            'kategori' => 'required|string|max:50',
            'harga' => 'required|numeric',
            'harga_beli' => 'required|numeric',
            'satuan' => 'required|string|max:20',
        ], [
            'nama_barang.unique' => 'Nama barang sudah ada, tidak boleh duplikat.',
        ]);

        $barang->update([
            'nama_barang' => $request->nama_barang,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'harga_beli' => $request->harga_beli,
            'satuan' => $request->satuan,
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function qrForm()
    {
        $barangs = Barang::orderBy('nama_barang')->get();

        return view('barang.print', compact('barangs'));
    }

    public function downloadQrPdf(Request $request)
    {
        $selectedBarang = [];
        $template = $request->paper_size;
        $action = $request->action;

        foreach ($request->barang_id as $index => $id) {
            $selectedBarang[] = [
                'barang' => Barang::find($id),
                'qty' => $request->qty[$index]
            ];
        }

        $pdf = Pdf::loadView(
            'barang.download',
            compact('selectedBarang', 'template')
        );

        // atur ukuran kertas
        if ($template == 'thermal') {
            $pdf->setPaper([0, 0, 164, 85], 'portrait');
        } else {
            $pdf->setPaper('a4', 'portrait');
        }

        $fileName =
            'Label_Barcode_' .
            date('Ymd_His') .
            '.pdf';

        if ($action == 'preview') {
            return $pdf->stream($fileName);
        }

        return $pdf->download($fileName);
    }

    public function exportExcel()
    {
        return Excel::download(
            new BarangExport,
            'barang.xlsx'
        );
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {

            Excel::import(
                new BarangImport,
                $request->file('file')
            );

            return back()->with(
                'success',
                'Import data barang berhasil.'
            );

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Format file tidak sesuai template barang.'
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        try {
            $barang->delete();
            return redirect()->route('barang.index')
                ->with('success', 'Barang berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('barang.index')
                ->with('error', 'Barang tidak dapat dihapus karena sudah memiliki riwayat transaksi/pesanan.');
        }
    }
}