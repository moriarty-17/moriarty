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
        Schema::create('penawaran', function (Blueprint $table) {
            $table->bigIncrements('id_penawaran', 8);
            $table->string('id_pengguna', 8);
            $table->string('jenis', 32)->nullable();
            $table->string('kode_penawaran', 125)->nullable();
            $table->string('judul', 160)->nullable();
            $table->string('gambar', 225)->nullable();
            $table->string('deskripsi', 2000)->nullable();
            $table->string('informasi_penawaran', 1000)->nullable();
            $table->string('harga', 16)->nullable();
            $table->string('status', 16)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penawaran');
    }
};
