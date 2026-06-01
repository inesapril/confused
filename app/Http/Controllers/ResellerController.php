<?php

namespace App\Http\Controllers;

use App\Models\Reseller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ResellerBarangExport;
use App\Imports\ResellerBarangImport;


class ResellerController extends Controller
{
    public function index()
    {
        $resellers = Reseller::all();
        return view('reseller.index', compact('resellers'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('reseller.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_reseller' => 'required|string|max:100',
            'alamat' => 'nullable|string|max:255',
            'barang_id' => 'required|array',
            'harga' => 'required|array',
            'harga.*' => 'required|numeric|min:0',
        ]);

        $reseller = Reseller::create([
            'nama_reseller' => $request->nama_reseller,
            'alamat' => $request->alamat,
        ]);

        foreach ($request->barang_id as $index => $barangId) {
            $reseller->barangs()->attach($barangId, [
                'harga' => $request->harga[$index]
            ]);
        }

        return redirect()
            ->route('reseller.index')
            ->with('success', 'Reseller berhasil ditambahkan.');
    }

    public function edit(Reseller $reseller)
    {
        $barangs = Barang::all();
        $reseller->load('barangs');

        return view('reseller.edit', compact('reseller', 'barangs'));
    }

    public function update(Request $request, Reseller $reseller)
    {
        $request->validate([
            'nama_reseller' => 'required|string|max:100',
            'alamat' => 'nullable|string|max:255',
        ]);

        $reseller->update([
            'nama_reseller' => $request->nama_reseller,
            'alamat' => $request->alamat,
        ]);

        $syncData = [];

        if ($request->barang_id) {
            foreach ($request->barang_id as $index => $barangId) {
                $syncData[$barangId] = [
                    'harga' => $request->harga[$index]
                ];
            }
        }

        $reseller->barangs()->sync($syncData);

        return redirect()->route('reseller.index')
            ->with('success', 'Reseller berhasil diperbarui.');
    }

    public function destroy(Reseller $reseller)
    {
        $reseller->delete();

        return redirect()->route('reseller.index')
            ->with('success', 'Reseller berhasil dihapus.');
    }

    public function exportBarang()
    {
        return Excel::download(
            new ResellerBarangExport,
            'reseller_barang.xlsx'
        );
    }

    public function importBarang(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(
            new ResellerBarangImport,
            $request->file('file')
        );

        return back()->with(
            'success',
            'Import reseller berhasil'
        );
    }
}