<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_detail', function (Blueprint $table) {
            $table->id();

            $table->foreignId('return_id')
                  ->constrained('returns')
                  ->onDelete('cascade');

            $table->foreignId('barang_id')
                  ->constrained('barangs')
                  ->onDelete('restrict');

            $table->integer('qty');
            $table->decimal('harga', 15, 2);
            $table->decimal('subtotal', 15, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_detail');
    }
};