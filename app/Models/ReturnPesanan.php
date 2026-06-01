<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnPesanan extends Model
{
    use HasFactory;

    protected $table = 'return_pesanan';

    protected $fillable = [
        'no_return',
        'tanggal_return',
        'no_pesanan',
        'toko',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_return' => 'date',
    ];

    /*
    relasi detail
    */
    public function details()
    {
        return $this->hasMany(
            ReturnPesananDetail::class,
            'return_pesanan_id'
        );
    }

    /*
    auto generate no return
    */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->no_return)) {
                $model->no_return =
                    self::generateNoReturn();
            }
        });
    }

    public static function generateNoReturn()
    {
        $latest = self::latest('id')->first();

        if (!$latest) {
            return 'RTN-00001';
        }

        $lastNumber = (int) substr(
            $latest->no_return,
            3
        );

        return 'RTN-' . str_pad(
            $lastNumber + 1,
            5,
            '0',
            STR_PAD_LEFT
        );
    }
}