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
        Schema::create('eksistensi', function (Blueprint $table) {
            $table->bigIncrements('id_eksistensi', 8);
            $table->string('username', 32)->nullable();
            $table->string('alamat_email', 125)->nullable();
            $table->string('password', 50)->nullable();
            $table->string('role', 5)->nullable();
            $table->string('foto_profil', 225)->nullable();
            $table->string('saldo', 32)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eksistensi');
    }
};
