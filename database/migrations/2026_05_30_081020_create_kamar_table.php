<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamar', function (Blueprint $table) {
            $table->id('id_kamar');

            $table->unsignedBigInteger('id_properti');
            $table->foreign('id_properti')->references('id_properti')->on('properti')->onDelete('cascade');

            $table->string('nama_kamar', 200);
            $table->integer('kapasitas')->default(2);
            $table->integer('jumlah_tempat_tidur')->default(1);
            $table->enum('tipe_tempat_tidur', ['single', 'double', 'queen', 'king', 'twin', 'bunk'])->default('single');
            $table->decimal('harga_per_malam', 15, 2)->nullable()->comment('Override harga properti jika diisi');
            $table->enum('status', ['available', 'maintenance'])->default('available');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
