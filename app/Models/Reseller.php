<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reseller extends Model
{
    protected $table = 'resellers';

    protected $fillable = [
        'nama_reseller',
        'alamat',
    ];

    /**
     * Relasi ke barang
     */
    public function barangs()
    {
        return $this->belongsToMany(
            Barang::class,
            'reseller_barang',
            'reseller_id',
            'barang_id'
        )->withPivot('harga')
         ->withTimestamps();
    }
}