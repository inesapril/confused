<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\Barang;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TokoBarangExport;
use App\Imports\TokoBarangImport;


class TokoController extends Controller
{
    public function index()
    {
        $tokos = Toko::all();
        return view('toko.index', compact('tokos'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('toko.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:100',
            'platform' => 'nullable|string|max:255',
            'barang_id' => 'required|array',
            'harga' => 'required|array',
            'harga.*' => 'required|numeric|min:0',
        ]);

        $toko = Toko::create([
            'nama_toko' => $request->nama_toko,
            'platform' => $request->platform,
        ]);

        foreach ($request->barang_id as $index => $barangId) {
            $toko->barangs()->attach($barangId, [
                'harga' => $request->harga[$index]
            ]);
        }

        return redirect()
            ->route('toko.index')
            ->with('success', 'Toko berhasil ditambahkan.');
    }

    public function edit(Toko $toko)
    {
        $barangs = Barang::all();
        $toko->load('barangs');

        return view('toko.edit', compact('toko', 'barangs'));
    }

    public function update(Request $request, Toko $toko)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:100',
            'platform' => 'nullable|string|max:255',
        ]);

        $toko->update([
            'nama_toko' => $request->nama_toko,
            'platform' => $request->platform,
        ]);

        $syncData = [];

        if ($request->barang_id) {
            foreach ($request->barang_id as $index => $barangId) {
                $syncData[$barangId] = [
                    'harga' => $request->harga[$index]
                ];
            }
        }

        $toko->barangs()->sync($syncData);

        return redirect()->route('toko.index')
            ->with('success', 'Toko berhasil diperbarui.');
    }

    public function destroy(Toko $toko)
    {
        $toko->delete();

        return redirect()->route('toko.index')
            ->with('success', 'Toko berhasil dihapus.');
    }

    public function exportBarang()
    {
        return Excel::download(
            new TokoBarangExport,
            'toko_barang.xlsx'
        );
    }

    public function importBarang(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(
            new TokoBarangImport,
            $request->file('file')
        );

        return back()->with(
            'success',
            'Import toko berhasil'
        );
    }
}