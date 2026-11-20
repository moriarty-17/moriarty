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
        Schema::create('pencairan', function (Blueprint $table) {
            $table->bigIncrements('id_pencairan', 8);
            $table->string('id_pengguna', 125)->nullable();
            $table->string('kode_pencairan', 125)->nullable();
            $table->string('jumlah_pencairan', 16)->nullable();
            $table->string('metode_pencairan', 25)->nullable();
            $table->string('alamat_pencairan', 25)->nullable();
            $table->string('status', 255)->nullable();
            $table->timestamp('waktu_pencairan')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pencairan');
    }
};
