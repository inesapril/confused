<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Barang;
use App\Models\Reseller;
use App\Models\Persediaan;
use App\Models\PenyesuaianPersediaan;
use App\Models\PenyesuaianPersediaanDetail;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StockOpnameDeltaTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_opname_delta_calculations()
    {
        // 1. Setup Roles and User
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('Owner');

        // 2. Create Barang (automatically creates Persediaan with stock = 0)
        $barang = Barang::create([
            'kode_barang' => 'BRG-0001',
            'nama_barang' => 'Barang A',
            'kategori' => 'Kategori A',
            'harga' => 10000,
            'harga_beli' => 8000,
            'satuan' => 'Pcs',
        ]);

        $persediaan = $barang->persediaan;
        $this->assertEquals(0, $persediaan->stock);

        // 3. Create Stock Opname of 5 pcs
        $response = $this->actingAs($user)->post(route('penyesuaian_persediaan.store'), [
            'tanggal_penyesuaian' => '2026-06-13',
            'keterangan' => 'Opname Awal',
            'barang_id' => [$barang->id],
            'stok_fisik' => [5],
        ]);

        $response->assertRedirect(route('penyesuaian_persediaan.index'));
        $persediaan->refresh();
        $this->assertEquals(5, $persediaan->stock);

        // Verify detail records
        $detail = PenyesuaianPersediaanDetail::first();
        $this->assertEquals(0, $detail->stok_sistem);
        $this->assertEquals(5, $detail->stok_fisik);
        $this->assertEquals(5, $detail->selisih);

        // 4. Create Outgoing Goods of 5 pcs
        $reseller = Reseller::create([
            'nama_reseller' => 'Reseller A',
            'telepon' => '08123456789',
            'alamat' => 'Alamat A',
        ]);

        // Setup reseller price mapping
        DB::table('reseller_barang')->insert([
            'reseller_id' => $reseller->id,
            'barang_id' => $barang->id,
            'harga' => 10000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)->post(route('barang_keluar.store'), [
            'tanggal_keluar' => '2026-06-14',
            'reseller_id' => $reseller->id,
            'keterangan' => 'Penjualan',
            'barang_id' => [$barang->id],
            'qty' => [5],
        ]);

        $response->assertRedirect(route('barang_keluar.index'));
        $persediaan->refresh();
        $this->assertEquals(0, $persediaan->stock);

        // 5. Edit Stock Opname to 6 pcs
        $penyesuaian = PenyesuaianPersediaan::first();

        // Simulate time difference for revision check
        // Set created_at to past, so updated_at will trigger is_revised flag
        $penyesuaian->created_at = now()->subMinutes(10);
        $penyesuaian->save();

        $response = $this->actingAs($user)->put(route('penyesuaian_persediaan.update', $penyesuaian->id), [
            'tanggal_penyesuaian' => '2026-06-13',
            'keterangan' => 'Opname Awal (Revisi)',
            'barang_id' => [$barang->id],
            'stok_fisik' => [6],
        ]);

        $response->assertRedirect(route('penyesuaian_persediaan.index'));
        $persediaan->refresh();

        // The stock should be 1 now (6 original - 5 sold = 1)
        $this->assertEquals(1, $persediaan->stock);

        // Verify updated detail records
        $detail = PenyesuaianPersediaanDetail::first();
        $this->assertEquals(0, $detail->stok_sistem);
        $this->assertEquals(6, $detail->stok_fisik);
        $this->assertEquals(6, $detail->selisih);

        // 6. View Persediaan details and verify "Stock Opname" and "Direvisi" exist
        $response = $this->actingAs($user)->get(route('persediaan.show', $persediaan->id));
        $response->assertOk();
        $response->assertSee('Stock Opname');
        $response->assertSee('Direvisi');

        // 7. Delete the Stock Opname and verify stock is reverted correctly
        $response = $this->actingAs($user)->delete(route('penyesuaian_persediaan.destroy', $penyesuaian->id));
        $response->assertRedirect(route('penyesuaian_persediaan.index'));
        $persediaan->refresh();

        // Without the opname, we started with 0, sold 5, so stock should be -5
        $this->assertEquals(-5, $persediaan->stock);
    }
}
