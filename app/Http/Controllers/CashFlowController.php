<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangKeluar;
use App\Models\Pesanan;
use App\Models\BarangMasuk;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class CashFlowController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)
            : now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
            : now();

        /*
        |-------------------------
        | PEMASUKAN
        |-------------------------
        */

        // PESANAN (jual ke toko)
        $pesananTotal = Pesanan::with('details')
            ->whereBetween('tanggal_keluar', [$startDate, $endDate])
            ->get()
            ->sum(function ($item) {
                return $item->details->sum(function ($d) {
                    return $d->qty * $d->harga;
                });
            });

        // BARANG KELUAR (reseller)
        $resellerTotal = BarangKeluar::with('details')
            ->whereBetween('tanggal_keluar', [$startDate, $endDate])
            ->get()
            ->sum(function ($item) {
                return $item->details->sum(function ($d) {
                    return $d->qty * $d->harga;
                });
            });

        $pemasukan = $pesananTotal + $resellerTotal;

        /*
        |-------------------------
        | PENGELUARAN (HPP)
        |-------------------------
        */

        // PESANAN HPP
        $pesananHpp = Pesanan::with('details')
            ->whereBetween('tanggal_keluar', [$startDate, $endDate])
            ->get()
            ->sum(function ($item) {
                return $item->details->sum(function ($d) {
                    return $d->qty * $d->harga_beli;
                });
            });

        // RESELLER HPP
        $resellerHpp = BarangKeluar::with('details')
            ->whereBetween('tanggal_keluar', [$startDate, $endDate])
            ->get()
            ->sum(function ($item) {
                return $item->details->sum(function ($d) {
                    return $d->qty * $d->harga_beli;
                });
            });

        $pengeluaran = $pesananHpp + $resellerHpp;

        /*
        |-------------------------
        | PROFIT
        |-------------------------
        */

        $profit = $pemasukan - $pengeluaran;

        /*
        |-------------------------
        | TABLE ROWS
        |-------------------------
        */

        $rows = collect();

        // PESANAN
        $rows = $rows->merge(
            Pesanan::with('toko', 'details')
                ->whereBetween('tanggal_keluar', [$startDate, $endDate])
                ->get()
                ->map(function ($item) {

                    $omzet = $item->details->sum(fn($d) => $d->qty * $d->harga);

                    $hpp = $item->details->sum(fn($d) => $d->qty * $d->harga_beli);

                    return [
                        'tanggal' => $item->tanggal_keluar,
                        'jenis' => 'Pesanan',
                        'sumber' => $item->toko->nama_toko ?? '-',
                        'omzet' => $omzet,
                        'hpp' => $hpp,
                        'profit' => $omzet - $hpp,
                    ];
                })
        );

        // RESELLER
        $rows = $rows->merge(
            BarangKeluar::with('reseller', 'details')
                ->whereBetween('tanggal_keluar', [$startDate, $endDate])
                ->get()
                ->map(function ($item) {

                    $omzet = $item->details->sum(fn($d) => $d->qty * $d->harga);

                    $hpp = $item->details->sum(fn($d) => $d->qty * $d->harga_beli);

                    return [
                        'tanggal' => $item->tanggal_keluar,
                        'jenis' => 'Reseller',
                        'sumber' => $item->reseller->nama_reseller ?? '-',
                        'omzet' => $omzet,
                        'hpp' => $hpp,
                        'profit' => $omzet - $hpp,
                    ];
                })
        );

        $rows = $rows->sortByDesc('tanggal')->values();

        return view('cashflow.index', compact(
            'rows',
            'pemasukan',
            'pengeluaran',
            'profit'
        ));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)
            : now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
            : now();

        $rows = collect();

        $pemasukan = 0;
        $pengeluaran = 0;

        /*
        |-------------------------
        | PESANAN
        |-------------------------
        */

        $pesanan = Pesanan::with(['toko', 'details'])
            ->whereBetween('tanggal_keluar', [$startDate, $endDate])
            ->get();

        foreach ($pesanan as $item) {

            $income = $item->details->sum(fn($d) => $d->qty * $d->harga);
            $hpp = $item->details->sum(fn($d) => $d->qty * $d->harga_beli);

            $rows->push([
                'tanggal' => $item->tanggal_keluar,
                'jenis' => 'Pesanan',
                'sumber' => $item->toko->nama_toko ?? '-',
                'omzet' => $income,
                'hpp' => $hpp,
                'profit' => $income - $hpp,
            ]);

            $pemasukan += $income;
            $pengeluaran += $hpp;
        }

        /*
        |-------------------------
        | RESSELLER
        |-------------------------
        */

        $reseller = BarangKeluar::with(['reseller', 'details'])
            ->whereBetween('tanggal_keluar', [$startDate, $endDate])
            ->get();

        foreach ($reseller as $item) {

            $income = $item->details->sum(fn($d) => $d->qty * $d->harga);
            $hpp = $item->details->sum(fn($d) => $d->qty * $d->harga_beli);

            $rows->push([
                'tanggal' => $item->tanggal_keluar,
                'jenis' => 'Reseller',
                'sumber' => $item->reseller->nama_reseller ?? '-',
                'omzet' => $income,
                'hpp' => $hpp,
                'profit' => $income - $hpp,
            ]);

            $pemasukan += $income;
            $pengeluaran += $hpp;
        }

        $profit = $pemasukan - $pengeluaran;

        return Pdf::loadView('cashflow.pdf', compact(
            'rows',
            'pemasukan',
            'pengeluaran',
            'profit',
            'startDate',
            'endDate'
        ))->download('cashflow.pdf');
    }
}