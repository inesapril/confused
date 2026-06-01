<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar';

    protected $fillable = [
        'no_barang_keluar',
        'tanggal_keluar',
        'reseller_id',
        'total',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_keluar' => 'date',
    ];

    public function reseller()
    {
        return $this->belongsTo(Reseller::class, 'reseller_id');
    }

    public function details()
    {
        return $this->hasMany(BarangKeluarDetail::class);
    }

    // OPTIONAL (boleh ada, tapi tidak wajib)
    public function barangs()
    {
        return $this->belongsToMany(
            Barang::class,
            'barang_keluar_detail'
        )->withPivot(['qty', 'harga', 'subtotal']);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->no_barang_keluar)) {
                $model->no_barang_keluar = self::generateNoBarangKeluar();
            }
        });
    }

    public static function generateNoBarangKeluar(): string
    {
        $latest = self::whereNotNull('no_barang_keluar')
            ->latest('id')
            ->first();

        if (!$latest) {
            return 'RSL-00001';
        }

        $lastNumber = (int) substr($latest->no_barang_keluar, 4); // OUT-

        $newNumber = $lastNumber + 1;

        return 'RSL-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }
}