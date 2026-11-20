<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id('id_ulasan');
            
            // Relasi ke tabel pemesanan
            $table->unsignedBigInteger('id_pemesanan');
            
            // Relasi ke user (pembeli yang mereview dan penjual yang direview)
            $table->unsignedBigInteger('id_pembeli');
            $table->unsignedBigInteger('id_penjual');
            
            // Data Review
            $table->tinyInteger('rating'); // Menyimpan angka 1 sampai 5
            $table->text('komentar')->nullable();
            
            $table->timestamps();

            // Opsional: Pastikan 1 pesanan hanya bisa di-review 1 kali
            $table->unique('id_pemesanan');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ulasan');
    }
};