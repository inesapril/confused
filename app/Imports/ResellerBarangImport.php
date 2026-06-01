<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\Reseller;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ResellerBarangImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows->skip(1) as $row) {

            $namaReseller = $row[0];
            $kodeBarang   = $row[1];
            $harga        = $row[3];

            $reseller = Reseller::where(
                'nama_reseller',
                $namaReseller
            )->first();

            if (!$reseller) continue;

            $barang = Barang::where(
                'kode_barang',
                $kodeBarang
            )->first();

            if (!$barang) continue;

            $reseller->barangs()->syncWithoutDetaching([
                $barang->id => [
                    'harga' => $harga
                ]
            ]);
        }
    }
}