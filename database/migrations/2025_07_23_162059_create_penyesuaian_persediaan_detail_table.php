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
        Schema::create('penyesuaian_persediaan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyesuaian_persediaan_id')->constrained('penyesuaian_persediaan')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs');
            $table->integer('stok_sistem');
            $table->integer('stok_real');
            $table->integer('selisih');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyesuaian_persediaan_detail');
    }
};
