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
        Schema::create('revenue_splits', function (Blueprint $table) {
            $table->id('id_revenue_split');

            $table->unsignedBigInteger('id_pesanan');
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pemesanan')->onDelete('cascade');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');

            $table->decimal('jumlah_kotor', 12, 2);          // total pembayaran sebelum dipotong
            $table->decimal('persentase_biaya_platform', 5, 2); // contoh: 10.00 (%)
            $table->decimal('jumlah_biaya_platform', 12, 2); // nominal fee masuk ke admin
            $table->decimal('jumlah_pemilik', 12, 2);        // nominal yang diterima owner

            $table->enum('status', ['pending', 'settled'])->default('pending');
            // pending  = belum ditransfer ke owner
            // settled  = admin sudah transfer ke rekening owner

            $table->timestamp('settled_at')->nullable(); // kapan admin transfer ke owner
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue_splits');
    }
};
