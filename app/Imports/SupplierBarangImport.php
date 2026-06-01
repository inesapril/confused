<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SupplierBarangImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows->skip(1) as $row) {

            $namaSupplier = $row[0];
            $kodeBarang   = $row[1];
            $harga        = $row[3];

            $supplier = Supplier::where(
                'nama_supplier',
                $namaSupplier
            )->first();

            if (!$supplier) continue;

            $barang = Barang::where(
                'kode_barang',
                $kodeBarang
            )->first();

            if (!$barang) continue;

            $supplier->barangs()->syncWithoutDetaching([
                $barang->id => [
                    'harga' => $harga
                ]
            ]);
        }
    }
}