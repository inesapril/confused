<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\Toko;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class TokoBarangImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows->skip(1) as $row) {

            $namaToko = $row[0];
            $kodeBarang   = $row[1];
            $harga        = $row[3];

            $toko = Toko::where(
                'nama_toko',
                $namaToko
            )->first();

            if (!$toko) continue;

            $barang = Barang::where(
                'kode_barang',
                $kodeBarang
            )->first();

            if (!$barang) continue;

            $toko->barangs()->syncWithoutDetaching([
                $barang->id => [
                    'harga' => $harga
                ]
            ]);
        }
    }
}