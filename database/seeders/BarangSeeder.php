<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = [
            [
                'kode_barang' => 'BJU001',
                'nama_barang' => 'Baju Kaos Polos',
                'kategori' => 'Baju',
                'harga' => 25000,
                'harga_beli' => 20000,
                'satuan' => 'pcs',
            ],
            [
                'kode_barang' => 'CLN001',
                'nama_barang' => 'Celana Jeans',
                'kategori' => 'Celana',
                'harga' => 75000,
                'harga_beli' => 60000,
                'satuan' => 'pcs',
            ],
            [
                'kode_barang' => 'JKT001',
                'nama_barang' => 'Jaket Hoodie',
                'kategori' => 'Jaket',
                'harga' => 120000,
                'harga_beli' => 100000,
                'satuan' => 'pcs',
            ],
            [
                'kode_barang' => 'RMP001',
                'nama_barang' => 'Rompi Safety',
                'kategori' => 'Rompi',
                'harga' => 50000,
                'harga_beli' => 40000,
                'satuan' => 'pcs',
            ],
            [
                'kode_barang' => 'KRH001',
                'nama_barang' => 'Kerah Polo',
                'kategori' => 'Kerah',
                'harga' => 10000,
                'harga_beli' => 8000,
                'satuan' => 'pcs',
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}