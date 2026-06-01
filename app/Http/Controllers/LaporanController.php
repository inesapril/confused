<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Reseller;
use App\Models\BarangMasukDetail;
use App\Models\BarangKeluar;
use App\Models\ReturnPesanan;
use App\Models\Persediaan;
use App\Models\Toko;
use App\Models\Pesanan;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $jenis = $request->jenis ?? 'supplier';

        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)
            : now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
            : now();

        if ($jenis == 'supplier') {
            [$data, $total] = $this->getSupplierData($request, $startDate, $endDate);
        } elseif ($jenis == 'reseller') {
            [$data, $total] = $this->getResellerData($request, $startDate, $endDate);
        } elseif ($jenis == 'return') {
            [$data, $total] = $this->getReturnData($startDate, $endDate);
        } elseif ($jenis == 'pesanan') {
            [$data, $total] = $this->getPesananData($request, $startDate, $endDate);
        } else {
            [$data, $total] = $this->getStokMenipisData();
        }

        $suppliers = Supplier::orderBy('nama_supplier')->get();
        $resellers = Reseller::orderBy('nama_reseller')->get();
        $tokos = Toko::all();

        return view('laporan.index', compact(
            'data',
            'jenis',
            'suppliers',
            'resellers',
            'tokos',
            'total'
        ));
    }

    private function getSupplierData(Request $request, Carbon $startDate, Carbon $endDate)
    {
        $query = BarangMasukDetail::with([
            'barangMasuk.supplier',
            'barang'
        ])
        ->whereHas('barangMasuk', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_masuk', [$startDate, $endDate]);
        });

        if ($request->supplier_id) {
            $query->whereHas('barangMasuk', function ($q) use ($request) {
                $q->where('supplier_id', $request->supplier_id);
            });
        }

        $data = $query->get();

        $total = $data->sum(function ($item) {
            return $item->qty * $item->harga;
        });

        return [$data, $total];
    }

    private function getResellerData(Request $request, Carbon $startDate, Carbon $endDate)
    {
        $query = BarangKeluar::with([
            'reseller',
            'details.barang'
        ])
        ->whereBetween('tanggal_keluar', [$startDate, $endDate]);

        if ($request->reseller_id) {
            $query->where('reseller_id', $request->reseller_id);
        }

        $data = $query->get();

        $total = $data->sum(function ($keluar) {
            return $keluar->details->sum(function ($d) {
                return $d->qty * $d->harga;
            });
        });

        return [$data, $total];
    }

    private function getReturnData(Carbon $startDate, Carbon $endDate)
    {
        $data = ReturnPesanan::with([
            'details.barang'
        ])
        ->whereBetween('tanggal_return', [$startDate, $endDate])
        ->get();

        $total = $data->sum(function ($retur) {
            return $retur->details->sum(function ($d) {
                return $d->qty * $d->barang->harga;
            });
        });

        return [$data, $total];
    }

    private function getPesananData(Request $request, Carbon $startDate, Carbon $endDate)
    {
        $query = Pesanan::with([
            'toko',
            'details.barang'
        ])->whereBetween(
            'tanggal_keluar',
            [$startDate, $endDate]
        );

        if ($request->toko_id) {
            $query->where('toko_id', $request->toko_id);
        }

        $data = $query->get();

        $total = $data->sum(function ($pesanan) {
            return $pesanan->details->sum(function ($d) {
                return $d->qty * $d->harga;
            });
        });

        return [$data, $total];
    }

    private function getStokMenipisData()
    {
        $data = Persediaan::with('barang')
            ->whereColumn('stock', '<=', 'safety_stock')
            ->get();

        return [$data, 0];
    }

    public function exportPdf(Request $request)
    {
        $jenis = $request->jenis ?? 'supplier';

        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)
            : now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
            : now();

        $data = collect();
        $total = 0;

        /*
        |-------------------------
        | SUPPLIER
        |-------------------------
        */
        if ($jenis == 'supplier') {

            $query = BarangMasukDetail::with(['barangMasuk.supplier','barang'])
                ->whereHas('barangMasuk', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('tanggal_masuk', [$startDate, $endDate]);
                });

            if ($request->supplier_id) {
                $query->whereHas('barangMasuk', function ($q) use ($request) {
                    $q->where('supplier_id', $request->supplier_id);
                });
            }

            $data = $query->get();

            $total = $data->sum(fn($i) => $i->qty * $i->harga);
        }

        /*
        |-------------------------
        | RESELLER
        |-------------------------
        */
        elseif ($jenis == 'reseller') {

            $query = BarangKeluar::with([
                'reseller',
                'details.barang'
            ])
            ->whereBetween('tanggal_keluar', [$startDate, $endDate]);

            if ($request->reseller_id) {
                $query->where('reseller_id', $request->reseller_id);
            }

            $data = $query->get();

            $total = $data->sum(fn($k) =>
                $k->details->sum(fn($d) => $d->qty * $d->harga)
            );
        }

        /*
        |-------------------------
        | RETURN
        |-------------------------
        */
        elseif ($jenis == 'return') {

            $data = ReturnPesanan::with('details.barang')
                ->whereBetween('tanggal_return', [$startDate, $endDate])
                ->get();

            $total = $data->sum(fn($r) =>
                $r->details->sum(fn($d) => $d->qty * $d->barang->harga)
            );
        }

        /*
        |-------------------------
        | PESANAN
        |-------------------------
        */
        elseif ($jenis == 'pesanan') {

            $query = Pesanan::with([
                'toko',
                'details.barang'
            ])->whereBetween(
                'tanggal_keluar',
                [$startDate, $endDate]
            );

            if ($request->toko_id) {
                $query->where('toko_id', $request->toko_id);
            }

            $data = $query->get();

            $total = $data->sum(fn($p) =>
                $p->details->sum(fn($d) => $d->qty * $d->harga)
            );
        }

        /*
        |-------------------------
        | STOK MENIPIS
        |-------------------------
        */
        elseif ($jenis == 'stok_menipis') {

            $data = Persediaan::with('barang')
                ->whereColumn('stock', '<=', 'safety_stock')
                ->get();

            $total = 0;
        }

        return Pdf::loadView('laporan.pdf', [
            'data' => $data,
            'jenis' => $jenis,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'total' => $total
        ])
        ->setPaper('A4', 'portrait')
        ->download('laporan_'.$jenis.'_'.now()->format('YmdHis').'.pdf');
    }
}