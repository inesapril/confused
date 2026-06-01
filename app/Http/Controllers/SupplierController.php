<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Exports\SupplierBarangExport;
use App\Imports\SupplierBarangImport;
use Maatwebsite\Excel\Facades\Excel;


class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('supplier.index', compact('suppliers'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('supplier.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:100',
            'alamat' => 'nullable|string|max:255',
            'barang_id' => 'required|array',
            'harga' => 'required|array',
            'harga.*' => 'required|numeric|min:0',
        ]);

        $supplier = Supplier::create([
            'nama_supplier' => $request->nama_supplier,
            'alamat' => $request->alamat,
        ]);

        foreach ($request->barang_id as $index => $barangId) {
            $supplier->barangs()->attach($barangId, [
                'harga' => $request->harga[$index]
            ]);
        }

        return redirect()
            ->route('supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        $barangs = Barang::all();
        $supplier->load('barangs');

        return view('supplier.edit', compact('supplier', 'barangs'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:100',
            'alamat' => 'nullable|string|max:255',
        ]);

        $supplier->update([
            'nama_supplier' => $request->nama_supplier,
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

        $supplier->barangs()->sync($syncData);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }

    public function exportBarang()
    {
        return Excel::download(
            new SupplierBarangExport,
            'supplier_barang.xlsx'
        );
    }

    public function importBarang(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(
            new SupplierBarangImport,
            $request->file('file')
        );

        return back()->with(
            'success',
            'Import supplier barang berhasil'
        );
    }
}