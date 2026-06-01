<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangKeluarDetail;
use App\Models\Reseller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $barangKeluars =
            BarangKeluar::with([
                'reseller',
                'details.barang'
            ])
            ->latest()
            ->get();

        return view(
            'barang_keluar.index',
            compact('barangKeluars')
        );
    }

    public function create()
    {
        $noBarangKeluar = BarangKeluar::generateNoBarangKeluar();

        $resellers = Reseller::all();
        $barangs = Barang::with('persediaan', 'resellers')
            ->get()
            ->map(function ($barang) {
                $barang->reseller_prices = DB::table('reseller_barang')
                    ->where('barang_id', $barang->id)
                    ->pluck('harga', 'reseller_id');

                return $barang;
            });

        return view('barang_keluar.create', compact(
            'noBarangKeluar',
            'resellers',
            'barangs'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_keluar' => 'required|date',
            'reseller_id' => 'required|exists:resellers,id',
            'keterangan' => 'nullable|string',

            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:barangs,id',

            'qty' => 'required|array|min:1',
            'qty.*' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {

            $barangKeluar =
                BarangKeluar::create([
                    'tanggal_keluar' =>
                        $request->tanggal_keluar,
                    'reseller_id' =>
                        $request->reseller_id,
                    'keterangan' =>
                        $request->keterangan,
                ]);

                $total = 0;

            foreach ($request->barang_id as $index => $barangId) {

            $qty = $request->qty[$index];

            $harga = DB::table('reseller_barang')
                ->where('reseller_id', $request->reseller_id)
                ->where('barang_id', $barangId)
                ->value('harga');

            $barang = Barang::findOrFail($barangId);

            if ($barang->persediaan->stock < $qty) {
                throw new \Exception(
                    "Stok {$barang->nama_barang} tidak cukup"
                );
            }

            $subtotal = $qty * $harga;

            $total += $subtotal;

            BarangKeluarDetail::create([
                'barang_keluar_id' => $barangKeluar->id,
                'barang_id' => $barangId,
                'qty' => $qty,
                'harga' => $harga,
                'harga_beli' => $barang->harga_beli,
                'subtotal' => $subtotal,
            ]);

            $barang
                ->persediaan()
                ->decrement('stock', $qty);
        }

            $barangKeluar->update([
                'total' => $total
            ]);

            DB::commit();

            return redirect()
                ->route(
                    'barang_keluar.index'
                )
                ->with(
                    'success',
                    'Barang keluar berhasil disimpan'
                );

        } catch (\Exception $e) {

            DB::rollback();

            dd([
                'MESSAGE' => $e->getMessage(),
                'LINE' => $e->getLine(),
                'FILE' => $e->getFile(),
                'REQUEST' => $request->all(),
            ]);
        }
    }

    public function show(BarangKeluar $barangKeluar)
    {
        $barangKeluar->load([
            'reseller',
            'details.barang'
        ]);

        return view(
            'barang_keluar.show',
            compact('barangKeluar')
        );
    }

    public function edit($id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);

        $resellers = Reseller::all();

        $barangs = Barang::with('persediaan', 'resellers')
            ->get()
            ->map(function ($barang) {
                $barang->reseller_prices = DB::table('reseller_barang')
                    ->where('barang_id', $barang->id)
                    ->pluck('harga', 'reseller_id');

                return $barang;
            });

        $noBarangKeluar = $barangKeluar->no_barang_keluar;

        return view('barang_keluar.edit', compact(
            'barangKeluar',
            'resellers',
            'barangs',
            'noBarangKeluar'
        ));
    }

    public function update(Request $request, BarangKeluar $barangKeluar)
    {
        
        $request->validate([
            'tanggal_keluar' => 'required|date',
            'reseller_id' => 'required|exists:resellers,id',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:barangs,id',
            'qty' => 'required|array|min:1',
            'qty.*' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {

            foreach ($barangKeluar->details as $detail) {
                $detail->barang
                    ->persediaan()
                    ->increment('stock', $detail->qty);
            }

            /*
            hapus detail lama
            */
            $barangKeluar->details()->delete();

            /*
            update header
            */
            $barangKeluar->update([
                'tanggal_keluar' => $request->tanggal_keluar,
                'reseller_id' => $request->reseller_id,
                'keterangan' => $request->keterangan,
            ]);

            $total = 0;

            foreach ($request->barang_id as $index => $barangId) {

                $qty = $request->qty[$index];

                $harga = DB::table('reseller_barang')
                    ->where('reseller_id', $request->reseller_id)
                    ->where('barang_id', $barangId)
                    ->value('harga');

                if (!$harga) {
                    throw new \Exception("Harga tidak ditemukan untuk barang ID $barangId");
                }

                $barang = Barang::findOrFail($barangId);

                // VALIDASI STOK
                if ($barang->persediaan->stock < $qty) {
                    throw new \Exception(
                        "Stok {$barang->nama_barang} tidak cukup"
                    );
                }

                $subtotal = $qty * $harga;

                $total += $subtotal;

                BarangKeluarDetail::create([
                    'barang_keluar_id' => $barangKeluar->id,
                    'barang_id' => $barangId,
                    'qty' => $qty,
                    'harga' => $harga,
                    'harga_beli' => $barang->harga_beli,
                    'subtotal' => $subtotal,
                ]);

                // OUT = KURANG STOK
                $barang
                    ->persediaan()
                    ->decrement('stock', $qty);
            }

            $barangKeluar->update([
                'total' => $total
            ]);

            DB::commit();

            return redirect()
                ->route('barang_keluar.index')
                ->with('success', 'Barang keluar berhasil diupdate');

        } catch (\Exception $e) {

            DB::rollback();

            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(BarangKeluar $barangKeluar)
    {
        DB::beginTransaction();

        try {

            foreach ($barangKeluar->details as $detail) {
                $detail->barang
                    ->persediaan()
                    ->increment('stock', $detail->qty);
            }

            $barangKeluar->details()->delete();
            $barangKeluar->delete();

            DB::commit();

            return redirect()
                ->route('barang_keluar.index')
                ->with('success', 'Barang keluar berhasil dihapus');

        } catch (\Exception $e) {

            DB::rollback();

            return back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }
}