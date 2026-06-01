<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Persediaan extends Model
{
    protected $table = 'persediaan';

    protected $fillable = [
        'barang_id',
        'harga',
        'safety_stock',
        'stock'
    ];

    protected $casts = [
        'safety_stock' => 'integer',
        'stock' => 'integer'
    ];

    // RELATION

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    // SAFETY STOCK CALCULATION

    // Rumus:
    // Safety Stock = K × σD × √LT

    public function calculateSafetyStock(): array
    {
        $K = 1.65;
        $LT = 7;
        $days = 90;

        /*
        |--------------------------------------------------------------------------
        | Ambil pemakaian dari RESELLER
        |--------------------------------------------------------------------------
        */
        $resellerUsage = DB::table('barang_keluar_detail')
            ->join(
                'barang_keluar',
                'barang_keluar.id',
                '=',
                'barang_keluar_detail.barang_keluar_id'
            )
            ->where('barang_keluar_detail.barang_id', $this->barang_id)
            ->where(
                'barang_keluar.tanggal_keluar',
                '>=',
                now()->subDays($days)
            )
            ->select(
                DB::raw('DATE(barang_keluar.tanggal_keluar) as tanggal'),
                DB::raw('SUM(barang_keluar_detail.qty) as qty')
            )
            ->groupBy('tanggal')
            ->pluck('qty', 'tanggal')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Ambil pemakaian dari PESANAN
        |--------------------------------------------------------------------------
        */
        $pesananUsage = DB::table('pesanan_detail')
            ->join(
                'pesanan',
                'pesanan.id',
                '=',
                'pesanan_detail.pesanan_id'
            )
            ->where(
                'pesanan_detail.barang_id',
                $this->barang_id
            )
            ->where(
                'pesanan.tanggal_keluar',
                '>=',
                now()->subDays($days)
            )
            ->select(
                DB::raw(
                    'DATE(pesanan.tanggal_keluar) as tanggal'
                ),
                DB::raw(
                    'SUM(pesanan_detail.qty) as qty'
                )
            )
            ->groupBy('tanggal')
            ->pluck('qty', 'tanggal')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Gabungkan usage harian
        |--------------------------------------------------------------------------
        */
        $allDates = collect(array_keys($resellerUsage))
            ->merge(array_keys($pesananUsage))
            ->unique();

        $usageData = [];

        foreach ($allDates as $tanggal) {
            $usageData[] =
                ($resellerUsage[$tanggal] ?? 0)
                +
                ($pesananUsage[$tanggal] ?? 0);
        }

        /*
        |--------------------------------------------------------------------------
        | Kalau belum ada histori
        |--------------------------------------------------------------------------
        */
        if (empty($usageData)) {
            return [
                'success' => false,
                'message' => 'Belum ada histori pemakaian',
                'safety_stock' => 5
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Mean
        |--------------------------------------------------------------------------
        */
        $meanDemand =
            array_sum($usageData)
            /
            count($usageData);

        /*
        |--------------------------------------------------------------------------
        | Standard deviation
        |--------------------------------------------------------------------------
        */
        $variance = 0;

        foreach ($usageData as $usage) {
            $variance += pow(
                $usage - $meanDemand,
                2
            );
        }

        $standardDeviation =
            sqrt(
                $variance / count($usageData)
            );

        /*
        |--------------------------------------------------------------------------
        | Safety stock
        |--------------------------------------------------------------------------
        */
        $safetyStock = round(
            $K * $standardDeviation * sqrt($LT)
        );

        if ($safetyStock < 5) {
            $safetyStock = 5;
        }

        return [
            'success' => true,
            'safety_stock' => $safetyStock
        ];
    }

    // UPDATE SAFETY STOCK

    public function updateSafetyStock(): bool
    {
        $result = $this->calculateSafetyStock();

        $this->safety_stock = $result['safety_stock'];

        return $this->save();
    }

    //STATUS
    public function getSafetyStockStatus(): array
    {
        if ($this->stock > $this->safety_stock) {
            return [
                'status' => 'safe',
                'color' => 'success',
                'message' => 'Stok aman'
            ];
        }

        if ($this->stock == $this->safety_stock) {
            return [
                'status' => 'warning',
                'color' => 'warning',
                'message' => 'Stok mendekati minimum'
            ];
        }

        return [
            'status' => 'critical',
            'color' => 'danger',
            'message' => 'Stok kritis'
        ];
    }

    // COLOR BADGE
    public function getSafetyStockColor(): string
    {
        return $this->getSafetyStockStatus()['color'];
    }

    /**
     * Cek notifikasi stok untuk item ini
     */
    public function checkAndGenerateNotification(): void
    {
        if ($this->safety_stock <= 0) {
            return;
        }

        if ($this->stock <= $this->safety_stock) {

            $barangName = $this->barang->nama_barang ?? 'Barang';

            Notification::updateOrCreate(
                [
                    'type' => 'stock_low',
                    'data->persediaan_id' => $this->id,
                    'is_read' => false,
                ],
                [
                    'title' => 'Stok Menipis',
                    'message' => "{$barangName} stok tinggal {$this->stock}",
                    'data' => [
                        'persediaan_id' => $this->id,
                        'barang_name' => $barangName,
                        'current_stock' => $this->stock,
                        'safety_stock' => $this->safety_stock,
                    ]
                ]
            );

        } else {
            Notification::where(
                'type',
                'stock_low'
            )
            ->where(
                'data->persediaan_id',
                $this->id
            )
            ->delete();
        }
    }
    /**
     * Cek semua persediaan lalu generate notifikasi
     */
    public static function checkAllStockNotifications(): void
    {
        self::with('barang')->get()->each(function ($persediaan) {
            $persediaan->checkAndGenerateNotification();
        });
    }
}