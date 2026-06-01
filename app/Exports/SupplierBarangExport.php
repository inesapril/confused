<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SupplierBarangExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $rows = collect();

        $suppliers = Supplier::with('barangs')->get();

        foreach ($suppliers as $supplier) {
            foreach ($supplier->barangs as $barang) {
                $rows->push([
                    'nama_supplier' => $supplier->nama_supplier,
                    'kode_barang'   => $barang->kode_barang,
                    'nama_barang'   => $barang->nama_barang,
                    'harga'         => $barang->pivot->harga,
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'nama_supplier',
            'kode_barang',
            'nama_barang',
            'harga'
        ];
    }
}