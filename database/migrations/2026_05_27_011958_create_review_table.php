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
        Schema::create('review', function (Blueprint $table) {
            $table->id('id_review');
            $table->unsignedBigInteger('id_pesanan');
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pemesanan')->onDelete('cascade');

            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('id_properti');
            $table->foreign('id_properti')->references('id_properti')->on('properti')->onDelete('cascade');

            $table->tinyInteger('rating'); // nilai 1–5 bintang
            $table->text('komentar')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review');
    }
};
