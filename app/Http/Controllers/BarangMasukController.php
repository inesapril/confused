<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index()
    {
        $barangMasuks =
            BarangMasuk::with([
                'supplier',
                'details.barang'
            ])
            ->latest()
            ->get();

        return view(
            'barang_masuk.index',
            compact('barangMasuks')
        );
    }

    public function create()
    {
        $noBarangMasuk = BarangMasuk::generateNoBarangMasuk();

        $suppliers = Supplier::all();
        $barangs = Barang::with('persediaan', 'suppliers')
            ->get()
            ->map(function ($barang) {
                $barang->supplier_prices = DB::table('supplier_barang')
                    ->where('barang_id', $barang->id)
                    ->pluck('harga', 'supplier_id');

                return $barang;
            });

        return view('barang_masuk.create', compact(
            'noBarangMasuk',
            'suppliers',
            'barangs'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'keterangan' => 'nullable|string',

            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:barangs,id',

            'qty' => 'required|array|min:1',
            'qty.*' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {

            $barangMasuk =
                BarangMasuk::create([
                    'tanggal_masuk' =>
                        $request->tanggal_masuk,
                    'supplier_id' =>
                        $request->supplier_id,
                    'keterangan' =>
                        $request->keterangan,
                ]);

                $total = 0;

            foreach (
                $request->barang_id
                as $index => $barangId
            ) {
                $qty = $request->qty[$index];
                $harga = DB::table('supplier_barang')
                    ->where('supplier_id', $request->supplier_id)
                    ->where('barang_id', $barangId)
                    ->value('harga');

                $total += $qty * $harga;

                BarangMasukDetail::create([
                    'barang_masuk_id' =>
                        $barangMasuk->id,

                    'barang_id' =>
                        $barangId,

                    'qty' =>
                        $qty,

                    'harga' =>
                        $harga,

                    'subtotal' =>
                        $qty * $harga,
                ]);

                /*
                tambah stok persediaan
                */
                $barang =
                    Barang::findOrFail(
                        $barangId
                    );

                $barang
                    ->persediaan()
                    ->increment(
                        'stock',
                        $qty
                    );
            }

            $barangMasuk->update([
                'total' => $total
            ]);

            DB::commit();

            return redirect()
                ->route(
                    'barang_masuk.index'
                )
                ->with(
                    'success',
                    'Barang masuk berhasil disimpan'
                );

        } catch (\Exception $e) {

            DB::rollback();

            return back()
                ->withInput()
                ->withErrors([
                    'error' =>
                        $e->getMessage()
                ]);
        }
    }

    public function show(BarangMasuk $barangMasuk)
    {
        $barangMasuk->load([
            'supplier',
            'details.barang'
        ]);

        return view(
            'barang_masuk.show',
            compact('barangMasuk')
        );
    }

    public function edit($id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);

        $suppliers = Supplier::all();

        $barangs = Barang::with('persediaan', 'suppliers')
            ->get()
            ->map(function ($barang) {
                $barang->supplier_prices = DB::table('supplier_barang')
                    ->where('barang_id', $barang->id)
                    ->pluck('harga', 'supplier_id');

                return $barang;
            });

        $noBarangMasuk = $barangMasuk->no_barang_masuk;

        return view('barang_masuk.edit', compact(
            'barangMasuk',
            'suppliers',
            'barangs',
            'noBarangMasuk'
        ));
    }

    public function update(
    Request $request,
    BarangMasuk $barangMasuk
) {
    $request->validate([
        'tanggal_masuk' => 'required|date',
        'supplier_id' => 'required|exists:suppliers,id',
        'barang_id' => 'required|array|min:1',
        'qty' => 'required|array|min:1',
        'harga' => 'required|array|min:1',
    ]);

    DB::beginTransaction();

    try {

        /*
        rollback stok lama
        */
        foreach ($barangMasuk->details as $detail) {
            $detail
                ->barang
                ->persediaan()
                ->decrement(
                    'stock',
                    $detail->qty
                );
        }

        /*
        hapus detail lama
        */
        $barangMasuk
            ->details()
            ->delete();

        /*
        update header
        */
        $barangMasuk->update([
            'tanggal_masuk' => $request->tanggal_masuk,
            'supplier_id' => $request->supplier_id,
            'keterangan' => $request->keterangan,
        ]);

        /*
        simpan detail baru
        */
        $total = 0;

        foreach ($request->barang_id as $index => $barangId) {

            $qty = $request->qty[$index];

            $harga = DB::table('supplier_barang')
                ->where('supplier_id', $request->supplier_id)
                ->where('barang_id', $barangId)
                ->value('harga');

            $total += $qty * $harga;

            BarangMasukDetail::create([
                'barang_masuk_id' => $barangMasuk->id,
                'barang_id' => $barangId,
                'qty' => $qty,
                'harga' => $harga,
                'subtotal' => $qty * $harga,
            ]);

            Barang::findOrFail($barangId)
                ->persediaan()
                ->increment(
                    'stock',
                    $qty
                );
        }

        /*
        update total
        */
        $barangMasuk->update([
            'total' => $total
        ]);

        DB::commit();

        return redirect()
            ->route('barang_masuk.index')
            ->with(
                'success',
                'Barang masuk berhasil diupdate'
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

    public function destroy(BarangMasuk $barangMasuk)
    {
        DB::beginTransaction();

        try {

            /*
            kurangi stok
            */
            foreach ($barangMasuk->details as $detail) {
                $detail
                    ->barang
                    ->persediaan()
                    ->decrement(
                        'stock',
                        $detail->qty
                    );
            }

            /*
            hapus detail dulu
            */
            $barangMasuk
                ->details()
                ->delete();

            /*
            baru hapus header
            */
            $barangMasuk->delete();

            DB::commit();

            return redirect()
                ->route('barang_masuk.index')
                ->with(
                    'success',
                    'Barang masuk berhasil dihapus'
                );

        } catch (\Exception $e) {

            DB::rollback();

            return back()
                ->withErrors([
                    'error' => $e->getMessage()
                ]);
        }
    }
}