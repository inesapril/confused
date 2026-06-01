<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class PenyesuaianPersediaan extends Model
{
    protected $table = 'penyesuaian_persediaan';

    protected $fillable = [
        'no_penyesuaian',
        'tanggal_penyesuaian',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_penyesuaian' => 'date',
    ];

    /**
     * Relasi ke detail penyesuaian
     */
    public function details(): HasMany
    {
        return $this->hasMany(
            PenyesuaianPersediaanDetail::class,
            'penyesuaian_persediaan_id'
        );
    }

    /**
     * Auto generate nomor penyesuaian
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->no_penyesuaian)) {
                $model->no_penyesuaian = self::generateNoPenyesuaian();
            }
        });
    }

    /**
     * Generate format:
     * ADJ-00001
     */
    public static function generateNoPenyesuaian(): string
    {
        $latest = self::latest('id')->first();

        if (!$latest) {
            return 'ADJ-00001';
        }

        $lastNumber = (int) substr($latest->no_penyesuaian, 4);
        $newNumber = $lastNumber + 1;

        return 'ADJ-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }
}