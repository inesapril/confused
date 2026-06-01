<?php

namespace App\Exports;

use App\Models\Reseller;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ResellerBarangExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $rows = collect();

        $resellers = Reseller::with('barangs')->get();

        foreach ($resellers as $reseller) {
            foreach ($reseller->barangs as $barang) {
                $rows->push([
                    'nama_reseller' => $reseller->nama_reseller,
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
            'nama_reseller',
            'kode_barang',
            'nama_barang',
            'harga',
        ];
    }
}