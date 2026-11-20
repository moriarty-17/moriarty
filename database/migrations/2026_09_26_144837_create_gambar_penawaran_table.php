<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gambar_penawaran', function (Blueprint $table) {
            $table->id('id_gambar');

            $table->unsignedBigInteger('id_penawaran');

            $table->string('gambar');

            $table->foreign('id_penawaran')
                ->references('id_penawaran')
                ->on('penawaran')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gambar_penawaran');
    }
};
