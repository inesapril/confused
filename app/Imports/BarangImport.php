<?php

namespace App\Imports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\ToModel;

class BarangImport implements ToModel
{
    public function model(array $row)
    {
        // skip header
        if ($row[0] === 'Kode Barang') {
            return null;
        }

        return Barang::updateOrCreate(
            [
                'kode_barang' => $row[0]
            ],
            [
                'nama_barang' => $row[1],
                'kategori' => $row[2],
                'harga' => $row[3],
                'harga_beli' => $row[4],
                'satuan' => $row[5],
            ]
        );
    }
}