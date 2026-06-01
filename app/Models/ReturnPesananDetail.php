<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnPesananDetail extends Model
{
    use HasFactory;

    protected $table = 'return_pesanan_details';

    protected $fillable = [
        'return_pesanan_id',
        'barang_id',
        'qty',
        'kondisi'
    ];

    public function returnPesanan()
    {
        return $this->belongsTo(
            ReturnPesanan::class,
            'return_pesanan_id'
        );
    }

    public function barang()
    {
        return $this->belongsTo(
            Barang::class,
            'barang_id'
        );
    }
}