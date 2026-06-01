<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasukDetail extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk_detail';

    protected $fillable = [
        'barang_masuk_id',
        'barang_id',
        'qty',
        'harga',
        'subtotal',
    ];

    /**
     * Relasi ke BarangMasuk
     */
    public function barangMasuk()
    {
        return $this->belongsTo(
            BarangMasuk::class
        );
    }

    /**
     * Relasi ke Barang
     */
    public function barang()
    {
        return $this->belongsTo(
            Barang::class
        );
    }
}