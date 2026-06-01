<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->id();

            $table->string('no_barang_masuk')->unique();
            $table->date('tanggal_masuk');

            $table->foreignId('supplier_id')
                  ->constrained('suppliers')
                  ->onDelete('restrict');

            $table->decimal('total', 15, 2)->default(0);

            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_masuk');
    }
};