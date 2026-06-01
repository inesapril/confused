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
        Schema::create('return_pesanan', function (Blueprint $table) {
            $table->id();

            $table->string('no_return')->unique();

            $table->string('no_pesanan');
            
            $table->enum(
                'toko', 
                [
                    'confused',
                    'wesker',
                    'manmayer',
                    'simplejoy_tiktok'
                ]
            );

            $table->date('tanggal_return');

            $table->enum(
                'status',
                [
                    'belum_selesai',
                    'selesai'
                ]
            )->default('belum_selesai');

            $table->text('keterangan')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_pesanan');
    }
};
