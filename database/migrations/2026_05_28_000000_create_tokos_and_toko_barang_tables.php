<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create tokos table
        Schema::create('tokos', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko', 100);
            $table->string('platform', 255)->nullable();
            $table->timestamps();
        });

        // 2. Create toko_barang pivot table
        Schema::create('toko_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('toko_id')
                ->constrained('tokos')
                ->cascadeOnDelete();
            $table->foreignId('barang_id')
                ->constrained('barangs')
                ->cascadeOnDelete();
            $table->decimal('harga', 15, 2);
            $table->timestamps();
        });

        // 3. Seed default stores
        DB::table('tokos')->insert([
            [
                'nama_toko' => 'Confused',
                'platform' => 'Shopee',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_toko' => 'Wesker',
                'platform' => 'Tokopedia',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_toko' => 'Manmayer',
                'platform' => 'Lazada',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_toko' => 'Simplejoy Tiktok',
                'platform' => 'Tiktok Shop',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toko_barang');
        Schema::dropIfExists('tokos');
    }
};
