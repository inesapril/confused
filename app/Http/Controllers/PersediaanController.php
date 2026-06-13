<?php

namespace App\Http\Controllers;

use App\Models\Persediaan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PersediaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $this->ensurePersediaanForAllBarang();
        $this->autoUpdateSafetyStock();
        $this->checkStockNotifications();

        $persediaan = Persediaan::with('barang')->get();

        $totalNilaiPersediaan = $persediaan->sum(function ($item) {
            return $item->stock * $item->barang->harga;
        });

        return view(
            'persediaan.index',
            compact('persediaan', 'totalNilaiPersediaan')
        );
    }

    /**
     * Pastikan semua barang memiliki data persediaan
     */
    private function ensurePersediaanForAllBarang()
    {
        $barangs = Barang::whereDoesntHave('persediaan')->get();
        
        foreach ($barangs as $barang) {
            Persediaan::create([
                'barang_id' => $barang->id,
                'harga' => $barang->harga ?? 0,
                'safety_stock' => 0,
                'stock' => 0
            ]);
        }

        $persediaanList = Persediaan::where(function ($query) {
            $query->where('safety_stock', 0)
                ->orWhere('updated_at', '<', now()->subDays(7));
        })->get();

        foreach ($persediaanList as $persediaan) {
            $persediaan->updateSafetyStock();
        }
    }

    /**
     * Auto update safety stock
     */
    private function autoUpdateSafetyStock()
    {
        $persediaanList = Persediaan::all();

        foreach ($persediaanList as $persediaan) {
            $persediaan->updateSafetyStock();
        }
    }
    /**
     * Check notifikasi stok
     */
    private function checkStockNotifications()
    {
        Persediaan::checkAllStockNotifications();
    }
        /**
         * Show the form for creating a new resource.
         */
        public function create()
        {
            // Redirect ke index karena tidak ada form create (auto-created)
            return redirect()->route('persediaan.index');
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store(Request $request)
        {
            // Redirect ke index karena tidak ada store manual (auto-created)
            return redirect()->route('persediaan.index');
        }

        /**
         * Display the specified resource.
         */
        public function show(string $id)
        {
            $persediaan = Persediaan::with('barang')->findOrFail($id);

            $barangId = $persediaan->barang_id;

            $riwayat = collect()
                ->merge($this->getSupplierRiwayat($barangId))
                ->merge($this->getResellerRiwayat($barangId))
                ->merge($this->getPesananRiwayat($barangId))
                ->merge($this->getReturnRiwayat($barangId))
                ->merge($this->getStockOpnameRiwayat($barangId))
                ->sortByDesc('tanggal')
                ->values();

            /*
            |--------------------------------------------------------------------------
            | Tambahkan stok berjalan
            | mulai dari persediaan.stock
            |--------------------------------------------------------------------------
            */
            $stokBerjalan = $persediaan->stock;

            $riwayat = $riwayat->map(function ($item) use (&$stokBerjalan) {
                $item['stok'] = $stokBerjalan;

                // mundur ke stok sebelumnya
                $stokBerjalan -= $item['qty'];

                return $item;
            });

            return view('persediaan.show', compact(
                'persediaan',
                'riwayat'
            ));
        }

        private function getStockOpnameRiwayat($barangId)
        {
            $opname = \App\Models\PenyesuaianPersediaanDetail::with('penyesuaianPersediaan')
                ->where('barang_id', $barangId)
                ->get();

            $data = collect();
            foreach ($opname as $item) {
                if ($item->penyesuaianPersediaan) {
                    $isRevised = false;
                    $createdAt = $item->penyesuaianPersediaan->created_at;
                    $updatedAt = $item->penyesuaianPersediaan->updated_at;
                    if ($createdAt && $updatedAt && abs($updatedAt->diffInSeconds($createdAt)) > 2) {
                        $isRevised = true;
                    }

                    $data->push([
                        'tanggal' => $item->penyesuaianPersediaan->tanggal_penyesuaian,
                        'jenis'   => 'Stock Opname',
                        'qty'     => $item->selisih,
                        'is_revised' => $isRevised,
                    ]);
                }
            }
            return $data;
        }

        private function getSupplierRiwayat($barangId)
        {
            $supplier = \App\Models\BarangMasukDetail::with('barangMasuk')
                ->where('barang_id', $barangId)
                ->get();

            return $supplier->map(function ($item) {
                return [
                    'tanggal' => $item->barangMasuk->tanggal_masuk,
                    'jenis'   => 'Supplier',
                    'qty'     => +$item->qty,
                ];
            });
        }

        private function getResellerRiwayat($barangId)
        {
            $reseller = \App\Models\BarangKeluarDetail::with('barangKeluar')
                ->where('barang_id', $barangId)
                ->get();

            return $reseller->map(function ($item) {
                return [
                    'tanggal' => $item->barangKeluar->tanggal_keluar,
                    'jenis'   => 'Reseller',
                    'qty'     => -$item->qty,
                ];
            });
        }

        private function getPesananRiwayat($barangId)
        {
            $pesanan = \App\Models\PesananDetail::with('pesanan')
                ->where('barang_id', $barangId)
                ->get();

            $data = collect();
            foreach ($pesanan as $item) {
                if ($item->pesanan) {
                    $data->push([
                        'tanggal' => $item->pesanan->tanggal_keluar,
                        'jenis'   => 'Pesanan',
                        'qty'     => -$item->qty,
                    ]);
                }
            }
            return $data;
        }

        private function getReturnRiwayat($barangId)
        {
            $returns = \App\Models\ReturnPesananDetail::with('returnPesanan')
                ->where('barang_id', $barangId)
                ->get();

            return $returns->map(function ($item) {
                return [
                    'tanggal' => $item->returnPesanan->tanggal_return,
                    'jenis'   => 'Return',
                    'qty'     => +$item->qty,
                ];
            });
        }

        /**
         * Show the form for editing the specified resource.
         */
        public function edit(string $id)
        {
            // Edit tidak diperbolehkan - hanya tampil data
            return redirect()->route('persediaan.index')
                            ->with('info', 'Data persediaan hanya dapat dilihat, tidak dapat diedit.');
        }

        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, string $id)
        {
            // Update tidak diperbolehkan
            return redirect()->route('persediaan.index')
                            ->with('info', 'Data persediaan hanya dapat dilihat, tidak dapat diedit.');
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy(string $id)
        {
            // Delete tidak diperbolehkan - data persediaan terikat dengan barang
            return redirect()->route('persediaan.index')
                            ->with('info', 'Data persediaan tidak dapat dihapus karena terikat dengan data barang.');
        }

    public function exportPdf()
    {
        $data = Persediaan::with('barang')
            ->orderBy('barang_id')
            ->get();

        $pdf = Pdf::loadView(
            'persediaan.pdf',
            compact('data')
        )->setPaper('A4', 'portrait');

        return $pdf->download(
            'stock_opname_'.now()->format('YmdHis').'.pdf'
        );
    }
}
