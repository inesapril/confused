<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\ReturnPesanan;
use App\Models\ReturnPesananDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnPesananController extends Controller
{
    public function index()
    {
        $returnPesanans = ReturnPesanan::with([
            'details.barang'
        ])->latest()->get();

        return view(
            'return_pesanan.index',
            compact('returnPesanans')
        );
    }

    public function create()
    {
        $noReturn = ReturnPesanan::generateNoReturn();

        $barangs = Barang::with('persediaan')->get();

        return view(
            'return_pesanan.create',
            compact(
                'noReturn',
                'barangs'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_return' => 'required|date',
            'no_pesanan'     => 'required',
            'toko'           => 'required',
            'status'         => 'required',

            'barang_id'      => 'required|array|min:1',
            'barang_id.*'    => 'required|exists:barangs,id',

            'qty'            => 'required|array|min:1',
            'qty.*'          => 'required|integer|min:1',

            'kondisi'        => 'required|array|min:1',
            'kondisi.*'      => 'required',
        ]);

        DB::beginTransaction();

        try {

            $returnPesanan = ReturnPesanan::create([
                'tanggal_return' => $request->tanggal_return,
                'no_pesanan'     => $request->no_pesanan,
                'toko'           => $request->toko,
                'status'         => $request->status,
                'keterangan'     => $request->keterangan,
            ]);

            foreach ($request->barang_id as $index => $barangId) {

                $qty = $request->qty[$index];
                $kondisi = $request->kondisi[$index];

                ReturnPesananDetail::create([
                    'return_pesanan_id' => $returnPesanan->id,
                    'barang_id'         => $barangId,
                    'qty'               => $qty,
                    'kondisi'           => $kondisi,
                ]);

                // STOCK MASUK RULE
                if (
                    $request->status === 'selesai' &&
                    $kondisi === 'baik'
                ) {
                    Barang::find($barangId)
                        ->persediaan()
                        ->increment('stock', $qty);
                }
            }

            DB::commit();

            return redirect()
                ->route('return_pesanan.index')
                ->with(
                    'success',
                    'Return pesanan berhasil disimpan'
                );

        } catch (\Exception $e) {

            DB::rollback();

            return back()
                ->withInput()
                ->withErrors([
                    'error' => $e->getMessage()
                ]);
        }
    }

    public function show(ReturnPesanan $returnPesanan)
    {
        $returnPesanan->load([
            'details.barang'
        ]);

        return view(
            'return_pesanan.show',
            compact('returnPesanan')
        );
    }

    public function edit($id)
    {
        $returnPesanan = ReturnPesanan::with(
            'details'
        )->findOrFail($id);

        $barangs = Barang::with(
            'persediaan'
        )->get();

        $noReturn = $returnPesanan->no_return;

        return view(
            'return_pesanan.edit',
            compact(
                'returnPesanan',
                'barangs',
                'noReturn'
            )
        );
    }

    public function update(Request $request, ReturnPesanan $returnPesanan)
    {
        $request->validate([
            'tanggal_return' => 'required|date',
            'no_pesanan'     => 'required',
            'toko'           => 'required',
            'status'         => 'required',
            'barang_id'      => 'required|array|min:1',
            'barang_id.*'    => 'required|exists:barangs,id',
            'qty'            => 'required|array|min:1',
            'qty.*'          => 'required|integer|min:1',
            'kondisi'        => 'required|array|min:1',
            'kondisi.*'      => 'required',
        ]);

        DB::beginTransaction();

        try {

            $oldStatus = $returnPesanan->status;

            // 1. rollback stok lama (HANYA jika sebelumnya sudah masuk stok)
            foreach ($returnPesanan->details as $detail) {
                if ($oldStatus === 'selesai' && $detail->kondisi === 'baik') {
                    $detail->barang
                        ->persediaan()
                        ->decrement('stock', $detail->qty);
                }
            }

            // 2. hapus detail lama
            $returnPesanan->details()->delete();

            // 3. update header
            $returnPesanan->update([
                'tanggal_return' => $request->tanggal_return,
                'no_pesanan'     => $request->no_pesanan,
                'toko'           => $request->toko,
                'status'         => $request->status,
                'keterangan'     => $request->keterangan,
            ]);

            // 4. insert ulang + apply rule baru
            foreach ($request->barang_id as $index => $barangId) {

                $qty = $request->qty[$index];
                $kondisi = $request->kondisi[$index];

                ReturnPesananDetail::create([
                    'return_pesanan_id' => $returnPesanan->id,
                    'barang_id'         => $barangId,
                    'qty'               => $qty,
                    'kondisi'           => $kondisi,
                ]);

                // NEW RULE STOCK MASUK
                if ($request->status === 'selesai' && $kondisi === 'baik') {
                    Barang::find($barangId)
                        ->persediaan()
                        ->increment('stock', $qty);
                }
            }

            DB::commit();

            return redirect()
                ->route('return_pesanan.index')
                ->with('success', 'Return pesanan berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollback();

            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(ReturnPesanan $returnPesanan)
    {
        DB::beginTransaction();

        try {

            // rollback stock dulu
            foreach ($returnPesanan->details as $detail) {

                if (
                    $returnPesanan->status === 'selesai' &&
                    $detail->kondisi === 'baik'
                ) {
                    $detail->barang
                        ->persediaan()
                        ->decrement('stock', $detail->qty);
                }
            }

            // hapus detail
            $returnPesanan->details()->delete();

            // hapus header
            $returnPesanan->delete();

            DB::commit();

            return redirect()
                ->route('return_pesanan.index')
                ->with('success', 'Return pesanan berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }
}