<?php

namespace App\Http\Controllers;



use App\Models\PenyesuaianPersediaan;
use App\Models\PenyesuaianPersediaanDetail;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenyesuaianPersediaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penyesuaianPersediaans =
            PenyesuaianPersediaan::with('details.barang')
            ->latest()
            ->get();

        return view('penyesuaian_persediaan.index', compact('penyesuaianPersediaans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangs = Barang::with('persediaan')->get();
        $noPenyesuaian = PenyesuaianPersediaan::generateNoPenyesuaian();

        return view('penyesuaian_persediaan.create', compact('barangs', 'noPenyesuaian'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_penyesuaian' => 'required|date',
            'keterangan' => 'nullable|string|max:1000',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:barangs,id',
            'stok_fisik' => 'required|array|min:1',
            'stok_fisik.*' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            // simpan header
            $penyesuaianPersediaan = PenyesuaianPersediaan::create([
                'tanggal_penyesuaian' => $request->tanggal_penyesuaian,
                'keterangan' => $request->keterangan,
            ]);

            // simpan detail
            foreach ($request->barang_id as $index => $barangId) {
                $barang = Barang::findOrFail($barangId);
                $persediaan = $barang->persediaan;

                if (!$persediaan) {
                    throw new \Exception(
                        "Persediaan untuk barang {$barang->nama_barang} tidak ditemukan"
                    );
                }

                $stokSistem = $persediaan->stock;
                $stokReal   = $request->stok_fisik[$index];
                $selisih    = $stokReal - $stokSistem;

                PenyesuaianPersediaanDetail::create([
                    'penyesuaian_persediaan_id' => $penyesuaianPersediaan->id,
                    'barang_id' => $barangId,
                    'stok_sistem' => $stokSistem,
                    'stok_fisik' => $stokReal,
                    'selisih' => $selisih,
                ]);

                // update stok barang
                $persediaan->update([
                    'stock' => $stokReal
                ]);
            }

            DB::commit();

            return redirect()
                ->route('penyesuaian_persediaan.index')
                ->with('success', 'Penyesuaian persediaan berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'error' => $e->getMessage()
                ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $penyesuaianPersediaan = PenyesuaianPersediaan::with('details.barang')->findOrFail($id);
        $barangs = Barang::with('persediaan')->get();

        return view('penyesuaian_persediaan.edit', compact('penyesuaianPersediaan', 'barangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tanggal_penyesuaian' => 'required|date',
            'keterangan' => 'nullable|string|max:1000',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:barangs,id',
            'stok_fisik' => 'required|array|min:1',
            'stok_fisik.*' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $penyesuaianPersediaan = PenyesuaianPersediaan::findOrFail($id);

            // kembalikan stok lama
            foreach ($penyesuaianPersediaan->details as $detail) {
                $persediaan = $detail->barang->persediaan;

                if ($persediaan) {
                    $persediaan->update([
                        'stock' => $detail->stok_sistem
                    ]);
                }
            }

            // hapus detail lama
            $penyesuaianPersediaan->details()->delete();

            // update header
            $penyesuaianPersediaan->update([
                'tanggal_penyesuaian' => $request->tanggal_penyesuaian,
                'keterangan' => $request->keterangan,
            ]);

            // simpan detail baru
            foreach ($request->barang_id as $index => $barangId) {
                $barang = Barang::findOrFail($barangId);
                $persediaan = $barang->persediaan;

                if (!$persediaan) {
                    throw new \Exception(
                        "Persediaan untuk barang {$barang->nama_barang} tidak ditemukan"
                    );
                }

                $stokSistem = $persediaan->stock;
                $stokReal   = $request->stok_fisik[$index];
                $selisih    = $stokReal - $stokSistem;

                PenyesuaianPersediaanDetail::create([
                    'penyesuaian_persediaan_id' => $penyesuaianPersediaan->id,
                    'barang_id' => $barangId,
                    'stok_sistem' => $stokSistem,
                    'stok_fisik' => $stokReal,
                    'selisih' => $selisih,
                ]);

                $persediaan->update([
                    'stock' => $stokReal
                ]);
            }

            DB::commit();

            return redirect()
                ->route('penyesuaian_persediaan.index')
                ->with('success', 'Penyesuaian berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'error' => $e->getMessage()
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $penyesuaianPersediaan = PenyesuaianPersediaan::findOrFail($id);

            // Revert stock changes
            foreach ($penyesuaianPersediaan->details as $detail) {
                $persediaan = $detail->barang->persediaan;

                if ($persediaan) {
                    $persediaan->update([
                        'stock' => $detail->stok_sistem
                    ]);
                }
            }

            $penyesuaianPersediaan->delete();

            DB::commit();

            return redirect()->route('penyesuaian_persediaan.index')
                           ->with('success', 'Penyesuaian persediaan berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(string $id)
    {
        $penyesuaianPersediaan =
            PenyesuaianPersediaan::with('details.barang')
            ->findOrFail($id);

        return view(
            'penyesuaian_persediaan.show',
            compact('penyesuaianPersediaan')
        );
    }
}


