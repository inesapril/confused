<?php

namespace App\Exports;

use App\Models\Toko;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TokoBarangExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $rows = collect();

        $tokos = Toko::with('barangs')->get();

        foreach ($tokos as $toko) {
            foreach ($toko->barangs as $barang) {
                $rows->push([
                    'nama_toko' => $toko->nama_toko,
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
            'nama_toko',
            'kode_barang',
            'nama_barang',
            'harga',
        ];
    }
}