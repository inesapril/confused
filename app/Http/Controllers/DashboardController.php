<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Barang;
use App\Models\Persediaan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total barang
        $totalBarang = Barang::count();

        // Barang menipis
        $barangMenipis = Persediaan::whereColumn(
            'stock',
            '<=',
            'safety_stock'
        )->count();

        // Detail barang menipis untuk widget prioritasi stok
        $barangMenipisDetail = Persediaan::with('barang')
            ->whereColumn('stock', '<=', 'safety_stock')
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // Total nilai persediaan
        $totalNilaiPersediaan = Persediaan::with('barang')
            ->get()
            ->sum(function ($item) {
                return $item->stock * ($item->barang->harga ?? 0);
            });

        // Chart pesanan per bulan
        $chartData = [];

        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = Pesanan::whereMonth('created_at', $i)->count();
        }

        // Pesanan terbaru untuk tabel aktivitas utama
        $pesananTerbaru = Pesanan::with('toko')
            ->latest('id')
            ->take(5)
            ->get();

        // Metrik tambahan untuk analitik compact
        $totalPesanan = Pesanan::count();
        $totalPenjualan = Pesanan::sum('total');

        return view('dashboard', compact(
            'totalBarang',
            'barangMenipis',
            'barangMenipisDetail',
            'totalNilaiPersediaan',
            'chartData',
            'pesananTerbaru',
            'totalPesanan',
            'totalPenjualan'
        ));
    }
}