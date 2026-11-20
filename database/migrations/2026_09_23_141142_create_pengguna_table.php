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
        Schema::create('pengguna', function (Blueprint $table) {
            $table->bigIncrements('id_pengguna', 8);
            $table->string('email', 225)->nullable();
            $table->string('username', 125)->nullable();
            $table->string('passwrord', 25)->nullable();
            $table->string('role', 16)->nullable();
            $table->string('gambar', 225)->nullable();
            $table->string('saldo', 16)->nullable();
            $table->string('identitas', 225)->nullable();
            $table->string('status', 50)->nullable();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};
