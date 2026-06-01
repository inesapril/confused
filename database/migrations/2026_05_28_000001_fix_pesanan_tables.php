<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drop existing pesanan_detail and pesanan tables to clean up constraints & old structure
        Schema::dropIfExists('pesanan_detail');
        Schema::dropIfExists('pesanan');

        // 2. Recreate pesanan table with correct columns matching the model & controller
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('no_pesanan')->unique();
            $table->date('tanggal_keluar');
            $table->foreignId('toko_id')
                  ->constrained('tokos')
                  ->cascadeOnDelete();
            $table->decimal('total', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 3. Recreate pesanan_detail table with correct columns matching the model & controller
        Schema::create('pesanan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')
                  ->constrained('pesanan')
                  ->cascadeOnDelete();
            $table->foreignId('barang_id')
                  ->constrained('barangs')
                  ->restrictOnDelete();
            $table->integer('qty');
            $table->decimal('harga', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_detail');
        Schema::dropIfExists('pesanan');
    }
};
