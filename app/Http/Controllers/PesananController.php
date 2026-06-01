<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index()
    {
        $pesanans = Pesanan::with(['toko', 'details.barang'])
            ->latest()
            ->get();

        return view('pesanan.index', compact('pesanans'));
    }

    public function create()
    {
        $noPesanan = Pesanan::generateNoPesanan();

        $tokos = Toko::all();

        $barangs = Barang::with(['persediaan', 'tokos'])
            ->get()
            ->map(function ($barang) {
                $barang->toko_prices = DB::table('toko_barang')
                    ->where('barang_id', $barang->id)
                    ->pluck('harga', 'toko_id');

                return $barang;
            });

        return view('pesanan.create', compact('noPesanan', 'tokos', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_keluar' => 'required|date',
            'toko_id' => 'required|exists:tokos,id',
            'keterangan' => 'nullable|string',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:barangs,id',
            'qty' => 'required|array|min:1',
            'qty.*' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $pesanan = Pesanan::create([
                'tanggal_keluar' => $request->tanggal_keluar,
                'toko_id' => $request->toko_id,
                'keterangan' => $request->keterangan,
                'total' => 0
            ]);

            $total = 0;

            foreach ($request->barang_id as $index => $barangId) {

                $qty = $request->qty[$index];

                $barang = Barang::with('persediaan')->findOrFail($barangId);

                if (!$barang->persediaan || $barang->persediaan->stock < $qty) {
                    throw new \Exception("Stok {$barang->nama_barang} tidak cukup");
                }

                $harga = DB::table('toko_barang')
                    ->where('toko_id', $request->toko_id)
                    ->where('barang_id', $barangId)
                    ->value('harga');

                if (!$harga) {
                    throw new \Exception("Harga tidak ditemukan untuk {$barang->nama_barang}");
                }

                $subtotal = $qty * $harga;
                $total += $subtotal;

                PesananDetail::create([
                    'pesanan_id' => $pesanan->id,
                    'barang_id' => $barangId,
                    'qty' => $qty,
                    'harga' => $harga,
                    'harga_beli' => $barang->harga_beli,
                    'subtotal' => $subtotal,
                ]);

                $barang->persediaan()->decrement('stock', $qty);
            }

            $pesanan->update(['total' => $total]);

            DB::commit();

            return redirect()
                ->route('pesanan.index')
                ->with('success', 'Pesanan berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollback();

            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['toko', 'details.barang']);

        return view('pesanan.show', compact('pesanan'));
    }

    public function edit($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $tokos = Toko::all();

        $barangs = Barang::with(['persediaan', 'tokos'])
            ->get()
            ->map(function ($barang) {
                $barang->toko_prices = DB::table('toko_barang')
                    ->where('barang_id', $barang->id)
                    ->pluck('harga', 'toko_id');

                return $barang;
            });

        return view('pesanan.edit', compact('pesanan', 'tokos', 'barangs'));
    }

    public function update(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'tanggal_keluar' => 'required|date',
            'toko_id' => 'required|exists:tokos,id',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:barangs,id',
            'qty' => 'required|array|min:1',
            'qty.*' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // rollback stok lama
            foreach ($pesanan->details as $detail) {
                $detail->barang->persediaan()->increment('stock', $detail->qty);
            }

            $pesanan->details()->delete();

            $pesanan->update([
                'tanggal_keluar' => $request->tanggal_keluar,
                'toko_id' => $request->toko_id,
                'keterangan' => $request->keterangan,
            ]);

            $total = 0;

            foreach ($request->barang_id as $index => $barangId) {

                $qty = $request->qty[$index];

                $barang = Barang::with('persediaan')->findOrFail($barangId);

                if (!$barang->persediaan || $barang->persediaan->stock < $qty) {
                    throw new \Exception("Stok {$barang->nama_barang} tidak cukup");
                }

                $harga = DB::table('toko_barang')
                    ->where('toko_id', $request->toko_id)
                    ->where('barang_id', $barangId)
                    ->value('harga');

                if (!$harga) {
                    throw new \Exception("Harga tidak ditemukan untuk {$barang->nama_barang}");
                }

                $subtotal = $qty * $harga;
                $total += $subtotal;

                PesananDetail::create([
                    'pesanan_id' => $pesanan->id,
                    'barang_id' => $barangId,
                    'qty' => $qty,
                    'harga' => $harga,
                    'harga_beli' => $barang->harga_beli,
                    'subtotal' => $subtotal,
                ]);

                $barang->persediaan()->decrement('stock', $qty);
            }

            $pesanan->update(['total' => $total]);

            DB::commit();

            return redirect()
                ->route('pesanan.index')
                ->with('success', 'Pesanan berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollback();

            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Pesanan $pesanan)
    {
        DB::beginTransaction();

        try {
            foreach ($pesanan->details as $detail) {
                $detail->barang->persediaan()->increment('stock', $detail->qty);
            }

            $pesanan->details()->delete();
            $pesanan->delete();

            DB::commit();

            return redirect()
                ->route('pesanan.index')
                ->with('success', 'Pesanan berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}