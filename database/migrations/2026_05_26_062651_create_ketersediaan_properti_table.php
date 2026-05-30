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
        Schema::create('ketersediaan_properti', function (Blueprint $table) {
            $table->id('id_ketersediaan_properti');

            $table->unsignedBigInteger('id_properti');
            $table->foreign('id_properti')->references('id_properti')->on('properti')->onDelete('cascade');

            // Range tanggal diblokir (bukan satu tanggal)
            $table->date('blocked_from');  // tanggal mulai diblokir
            $table->date('blocked_until'); // tanggal akhir diblokir

            $table->enum('status_ketersediaan', ['blocked', 'booked'])->default('blocked');
            // blocked = diblokir manual owner
            // booked  = otomatis terisi karena ada booking aktif
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ketersediaan_properti');
    }
};
