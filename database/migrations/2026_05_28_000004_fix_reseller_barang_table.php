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
        // 1. Drop existing reseller_barang table to clear the incorrect structure
        Schema::dropIfExists('reseller_barang');

        // 2. Recreate reseller_barang table with correct pivot columns
        Schema::create('reseller_barang', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('reseller_id')
                  ->constrained('resellers')
                  ->onDelete('cascade');

            $table->foreignId('barang_id')
                  ->constrained('barangs')
                  ->onDelete('cascade');

            $table->decimal('harga', 12, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseller_barang');
    }
};
