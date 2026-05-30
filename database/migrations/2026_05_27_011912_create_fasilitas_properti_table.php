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
        Schema::create('fasilitas_properti', function (Blueprint $table) {
            $table->id('id_fasilitas_properti');

            // FK ke tabel properti
            $table->unsignedBigInteger('id_properti');
            $table->foreign('id_properti')
                  ->references('id_properti')
                  ->on('properti')
                  ->onDelete('cascade');

            // FK ke tabel fasilitas
            $table->unsignedBigInteger('id_fasilitas');
            $table->foreign('id_fasilitas')
                  ->references('id_fasilitas')
                  ->on('fasilitas')
                  ->onDelete('cascade');

            // Kombinasi unik — satu properti tidak bisa punya fasilitas yang sama dua kali
            $table->unique(['id_properti', 'id_fasilitas']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas_properti');
    }
};
