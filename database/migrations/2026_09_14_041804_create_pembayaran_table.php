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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->bigIncrements('id_pembayaran', 8);
            $table->string('kode_pembayaran', 125)->nullable();
            $table->string('kode_penawaran', 125)->nullable();
            $table->string('harga', 16)->nullable();
            $table->string('metode_pembayaran', 25)->nullable();
            $table->string('snap_token', 255)->nullable();
            $table->string('payment_link')->nullable();
            $table->string('status', 16)->nullable();
            $table->timestamp('waktu_pembayaran')->useCurrent();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
