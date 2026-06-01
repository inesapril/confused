<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    protected $table = 'tokos';

    protected $fillable = [
        'nama_toko',
        'platform',
    ];

    /**
     * Relasi ke barang
     */
    public function barangs()
    {
        return $this->belongsToMany(
            Barang::class,
            'toko_barang',
            'toko_id',
            'barang_id'
        )->withPivot('harga')
         ->withTimestamps();
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }
}