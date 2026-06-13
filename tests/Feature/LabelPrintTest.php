<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Barang;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabelPrintTest extends TestCase
{
    use RefreshDatabase;

    public function test_label_pdf_generation_a6()
    {
        // 1. Setup Roles and User
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('Owner');

        // 2. Create Barang
        $barang = Barang::create([
            'kode_barang' => 'BRG-0001',
            'nama_barang' => 'Barang A',
            'kategori' => 'Kategori A',
            'harga' => 10000,
            'harga_beli' => 8000,
            'satuan' => 'Pcs',
        ]);

        // 3. Post to download QR PDF with A6 3-column layout
        $response = $this->actingAs($user)->post(route('barang.downloadQrPdf'), [
            'paper_size' => 'a6_24',
            'action' => 'preview',
            'barang_id' => [$barang->id],
            'qty' => [1],
        ]);

        // Verify that the response is successful and returns a PDF file stream
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');

        // 4. Post to download QR PDF with A6 2-column layout
        $response = $this->actingAs($user)->post(route('barang.downloadQrPdf'), [
            'paper_size' => 'a6_12',
            'action' => 'preview',
            'barang_id' => [$barang->id],
            'qty' => [1],
        ]);

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');

        // 5. Post to download QR PDF with Single label layout
        $response = $this->actingAs($user)->post(route('barang.downloadQrPdf'), [
            'paper_size' => 'single',
            'action' => 'preview',
            'barang_id' => [$barang->id],
            'qty' => [1],
        ]);

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
