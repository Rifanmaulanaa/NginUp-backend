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
            $table->id('id_pembayaran');

            $table->unsignedBigInteger('id_pesanan');
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pemesanan')->onDelete('cascade');

           // Kode unik untuk konfirmasi transfer manual
            // contoh: PAY-20250115-001
            $table->string('code_pembayaran', 50)->unique();

            $table->decimal('jumlah_pembayaran', 12, 2);

            $table->enum('metode_pembayaran', [
                'transfer',
                'virtual_account',
                'cash',
            ]);

            $table->string('bukti_pembayaran')->nullable(); // path foto bukti transfer
            $table->enum('status_pembayaran', [
                'pending',   // belum bayar
                'paid',      // sudah lunas
                'failed',    // gagal/ditolak
                'refunded',  // uang dikembalikan
            ])->default('pending');

            $table->timestamp('tanggal_pembayaran')->nullable(); // kapan konfirmasi lunas
            $table->timestamp('expired_at')->nullable();         // batas waktu bayar
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
