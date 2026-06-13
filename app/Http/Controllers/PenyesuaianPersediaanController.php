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

            // Map old details by barang_id for easy lookup
            $oldDetails = $penyesuaianPersediaan->details->keyBy('barang_id');

            // Array to hold the list of barang IDs processed in the request
            $processedBarangIds = [];

            // loop request barang
            foreach ($request->barang_id as $index => $barangId) {
                $barang = Barang::findOrFail($barangId);
                $persediaan = $barang->persediaan;

                if (!$persediaan) {
                    throw new \Exception(
                        "Persediaan untuk barang {$barang->nama_barang} tidak ditemukan"
                    );
                }

                $stokReal = $request->stok_fisik[$index];

                if ($oldDetails->has($barangId)) {
                    // Barang already exists in this stock opname
                    $oldDetail = $oldDetails->get($barangId);
                    $oldStokFisik = $oldDetail->stok_fisik;
                    $stokSistem = $oldDetail->stok_sistem; // keep original system stock

                    $delta = $stokReal - $oldStokFisik;

                    if ($delta > 0) {
                        $persediaan->increment('stock', $delta);
                    } elseif ($delta < 0) {
                        $persediaan->decrement('stock', abs($delta));
                    }
                } else {
                    // This is a new barang added to this stock opname during edit
                    $stokSistem = $persediaan->stock;
                    
                    $persediaan->update([
                        'stock' => $stokReal
                    ]);
                }

                $processedBarangIds[$barangId] = [
                    'stok_sistem' => $stokSistem,
                    'stok_fisik' => $stokReal,
                    'selisih' => $stokReal - $stokSistem,
                ];
            }

            // Revert stock for any barang that was removed from the stock opname during edit
            foreach ($oldDetails as $barangId => $oldDetail) {
                if (!array_key_exists($barangId, $processedBarangIds)) {
                    $persediaan = $oldDetail->barang->persediaan;
                    if ($persediaan) {
                        $revertDelta = $oldDetail->stok_fisik - $oldDetail->stok_sistem;
                        if ($revertDelta > 0) {
                            $persediaan->decrement('stock', $revertDelta);
                        } elseif ($revertDelta < 0) {
                            $persediaan->increment('stock', abs($revertDelta));
                        }
                    }
                }
            }

            // delete old details
            $penyesuaianPersediaan->details()->delete();

            // update header
            $penyesuaianPersediaan->update([
                'tanggal_penyesuaian' => $request->tanggal_penyesuaian,
                'keterangan' => $request->keterangan,
            ]);

            // create new details
            foreach ($processedBarangIds as $barangId => $data) {
                PenyesuaianPersediaanDetail::create([
                    'penyesuaian_persediaan_id' => $penyesuaianPersediaan->id,
                    'barang_id' => $barangId,
                    'stok_sistem' => $data['stok_sistem'],
                    'stok_fisik' => $data['stok_fisik'],
                    'selisih' => $data['selisih'],
                ]);
            }

            DB::commit();

            // Check notifications for affected barangs
            \App\Models\Persediaan::checkAllStockNotifications();

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

            // Revert stock changes by applying the inverse delta
            foreach ($penyesuaianPersediaan->details as $detail) {
                $persediaan = $detail->barang->persediaan;

                if ($persediaan) {
                    $revertDelta = $detail->stok_fisik - $detail->stok_sistem;
                    if ($revertDelta > 0) {
                        $persediaan->decrement('stock', $revertDelta);
                    } elseif ($revertDelta < 0) {
                        $persediaan->increment('stock', abs($revertDelta));
                    }
                }
            }

            $penyesuaianPersediaan->delete();

            DB::commit();

            // Check notifications
            \App\Models\Persediaan::checkAllStockNotifications();

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


