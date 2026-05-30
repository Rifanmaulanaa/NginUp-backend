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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('id_notifikasi');
            
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            
            $table->string('title', 200);
            $table->text('pesan');

            // Kategori notif: booking, payment, review, system
            $table->string('type', 50)->default('system');

            // Sudah dibaca atau belum — untuk badge angka di ikon lonceng
            $table->boolean('is_read')->default(false);

            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
