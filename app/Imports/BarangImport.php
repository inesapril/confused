<?php

namespace App\Imports;

use App\Models\Barang;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class BarangImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $header = $rows->first();

        if (
            trim($header[0]) !== 'Kode Barang' ||
            trim($header[1]) !== 'Nama Barang'
        ) {
            throw new \Exception(
                'Format file tidak sesuai template barang.'
            );
        }

        foreach ($rows->skip(1) as $row) {

            if (empty($row[0])) {
                continue;
            }

            Barang::updateOrCreate(
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
}