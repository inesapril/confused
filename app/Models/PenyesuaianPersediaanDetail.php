<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenyesuaianPersediaanDetail extends Model
{
    protected $table = 'penyesuaian_persediaan_detail';
    
    protected $fillable = [
        'penyesuaian_persediaan_id',
        'barang_id',
        'stok_sistem',
        'stok_fisik',
        'selisih',
    ];

    protected $casts = [
        'stok_sistem' => 'integer',
        'stok_fisik' => 'integer',
        'selisih' => 'integer',
    ];

    public function penyesuaianPersediaan(): BelongsTo
    {
        return $this->belongsTo(PenyesuaianPersediaan::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}