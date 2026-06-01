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
        Schema::create('barang_keluar_pesanan', function (Blueprint $table) {
            $table->id();

            $table->string(
                'no_barang_keluar_pesanan'
            );

            $table->date(
                'tanggal_keluar'
            );

            $table->string(
                'no_pesanan'
            );

            $table->enum('toko', [
                'confused',
                'wesker',
                'manmayer',
                'simplejoy_tiktok'
            ]);

            $table->text(
                'keterangan'
            )->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
