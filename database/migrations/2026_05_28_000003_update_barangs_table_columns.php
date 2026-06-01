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
        Schema::table('barangs', function (Blueprint $table) {
            $table->renameColumn('harga_barang', 'harga');
        });

        Schema::table('barangs', function (Blueprint $table) {
            $table->decimal('harga_beli', 12, 2)->default(0)->after('harga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn('harga_beli');
        });

        Schema::table('barangs', function (Blueprint $table) {
            $table->renameColumn('harga', 'harga_barang');
        });
    }
};
