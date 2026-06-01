<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barangs';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'harga',
        'harga_beli',
        'satuan',
    ];

    /**
     * Boot method untuk auto-create persediaan
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($barang) {
            // Auto-create persediaan entry ketika barang baru dibuat
            $persediaan = new \App\Models\Persediaan();
            $persediaan->barang_id = $barang->id;
            $persediaan->harga = $barang->harga ?? 0;
            $persediaan->safety_stock = 0;
            $persediaan->stock = 0;
            $persediaan->save();
        });
    }

    /**
     * Relasi many-to-many ke supplier
     */
    public function suppliers()
    {
        return $this->belongsToMany(
            Supplier::class,
            'supplier_barang',
            'barang_id',
            'supplier_id'
        )->withPivot('harga')
         ->withTimestamps();
    }

    /**
     * Relasi many-to-many ke toko
     */
    public function tokos()
    {
        return $this->belongsToMany(
            Toko::class,
            'toko_barang',
            'barang_id',
            'toko_id'
        )->withPivot('harga')
         ->withTimestamps();
    }

    /**
     * Relasi many-to-many ke reseller
     */
    public function resellers()
    {
        return $this->belongsToMany(
            Reseller::class,
            'reseller_barang',
            'barang_id',
            'reseller_id'
        )->withPivot('harga')
         ->withTimestamps();
    }

    /**
     * Persediaan
     */
    public function persediaan()
    {
        return $this->hasOne(Persediaan::class);
    }
}
