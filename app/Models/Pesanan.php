<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'no_pesanan',
        'tanggal_keluar',
        'toko_id',
        'total',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_keluar' => 'date',
    ];

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }

    public function details()
    {
        return $this->hasMany(PesananDetail::class);
    }

    // OPTIONAL (boleh ada, tapi tidak wajib)
    public function barangs()
    {
        return $this->belongsToMany(
            Barang::class,
            'pesanan_detail'
        )->withPivot(['qty', 'harga', 'subtotal']);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->no_pesanan)) {
                $model->no_pesanan = self::generateNoPesanan();
            }
        });
    }

    public static function generateNoPesanan(): string
    {
        $latest = self::whereNotNull('no_pesanan')
            ->latest('id')
            ->first();

        if (!$latest) {
            return 'OUT-00001';
        }

        $lastNumber = (int) substr($latest->no_pesanan, 4); // OUT-

        $newNumber = $lastNumber + 1;

        return 'OUT-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }
}