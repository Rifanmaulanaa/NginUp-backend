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
        Schema::create('properti_aturan', function (Blueprint $table) {
            $table->id('id_properti_aturan');

            // FK ke tabel properti
            $table->unsignedBigInteger('id_properti');
            $table->foreign('id_properti')
                  ->references('id_properti')
                  ->on('properti')
                  ->onDelete('cascade');

            // FK ke tabel aturan
            $table->unsignedBigInteger('id_aturan');
            $table->foreign('id_aturan')
                  ->references('id_aturan')
                  ->on('aturan')
                  ->onDelete('cascade');

            // Kombinasi unik — satu properti tidak bisa punya aturan yang sama dua kali
            $table->unique(['id_properti', 'id_aturan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properti_aturan');
    }
};
