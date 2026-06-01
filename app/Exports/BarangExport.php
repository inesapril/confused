<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BarangExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Barang::select(
            'kode_barang',
            'nama_barang',
            'kategori',
            'harga',
            'harga_beli',
            'satuan'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Kode Barang',
            'Nama Barang',
            'Kategori',
            'Harga Jual',
            'Harga Beli',
            'Satuan',
        ];
    }
}