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
        Schema::table('penyesuaian_persediaan_detail', function (Blueprint $table) {
            $table->renameColumn('stok_real', 'stok_fisik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyesuaian_persediaan_detail', function (Blueprint $table) {
            $table->renameColumn('stok_fisik', 'stok_real');
        });
    }
};
