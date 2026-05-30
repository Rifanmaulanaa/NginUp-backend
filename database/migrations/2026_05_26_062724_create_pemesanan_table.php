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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('id_properti');
            $table->foreign('id_properti')->references('id_properti')->on('properti')->onDelete('cascade');

            $table->date('tanggal_check_in');
            $table->date('tanggal_check_out');
            $table->integer('total_malam');
            $table->integer('total_tamu');
            $table->decimal('total_harga', 15, 2); // total harga untuk seluruh masa inap / harga_per_malam × total_malam

            $table->enum('status_pemesanan', [
                'pending', // menunggu konfirmasi owner
                'confirmed', // owner setuju
                'ongoing', // traveler sedang menginap
                'completed', // selesai
                'cancelled', // dibatalkan
            ])->default('pending');
            
            $table->text('catatan_traveler')->nullable(); // catatan khusus dari traveler untuk owner
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
