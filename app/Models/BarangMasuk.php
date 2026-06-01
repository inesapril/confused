<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';

    protected $fillable = [
        'no_barang_masuk',
        'tanggal_masuk',
        'supplier_id',
        'total',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    /**
     * Relasi ke Supplier
     */
    public function supplier()
    {
        return $this->belongsTo(
            Supplier::class,
            'supplier_id'
        );
    }

    /**
     * Relasi ke detail barang masuk
     */
    public function details()
    {
        return $this->hasMany(BarangMasukDetail::class);
    }

    /**
     * Relasi many-to-many ke Barang
     */
    public function barangs()
    {
        return $this->belongsToMany(
            Barang::class,
            'barang_masuk_detail'
        )->withPivot([
            'qty',
            'harga',
            'subtotal'
        ])->withTimestamps();
    }

    /**
     * Auto generate nomor barang masuk
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->no_barang_masuk)) {
                $model->no_barang_masuk = self::generateNoBarangMasuk();
            }
        });
    }

    /**
     * Generate format:
     * IN-00001
     */
    public static function generateNoBarangMasuk(): string
    {
        $latest = self::latest('id')->first();

        if (!$latest) {
            return 'IN-00001';
        }

        $lastNumber = (int) substr(
            $latest->no_barang_masuk,
            3
        );

        $newNumber = $lastNumber + 1;

        return 'IN-' . str_pad(
            $newNumber,
            5,
            '0',
            STR_PAD_LEFT
        );
    }
}