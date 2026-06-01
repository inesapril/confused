<?php

namespace App\Models;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = [
        'nama_supplier',
        'alamat',
    ];

    /**
     * Relasi ke barang
     */
    public function barangs()
    {
        return $this->belongsToMany(
            Barang::class,
            'supplier_barang',
            'supplier_id',
            'barang_id'
        )->withPivot('harga')
         ->withTimestamps();
    }
}