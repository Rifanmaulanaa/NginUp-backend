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
        Schema::create('gambar', function (Blueprint $table) {
            $table->id('id_gambar');

            $table->unsignedBigInteger('id_properti');
            $table->foreign('id_properti')->references('id_properti')->on('properti')->onDelete('cascade'); // foto ikut terhapus kalau properti dihapus


            $table->string('url_gambar'); // path file di storage
            $table->boolean('is_primary')->default(false); // foto utama/thumbnail
            $table->integer('urutan')->default(0); // untuk mengatur urutan tampil gambar

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gambar');
    }
};
