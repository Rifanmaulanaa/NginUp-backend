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
        Schema::create('properti', function (Blueprint $table) {
            $table->id('id_properti');

            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');

            $table->string('nama_properti', 200);
            $table->enum('tipe_properti', ['hotel', 'villa', 'apartemen', 'kost', 'rumah', 'guesthouse']);
            $table->text('deskripsi');
            $table->string('alamat', 255);
            $table->string('kota', 100);
            $table->string('provinsi', 100);

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->decimal('harga_per_malam', 15, 2);
            $table->integer('max_tamu');
            $table->integer('jumlah_ruang');
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft');

            $table->enum('verified_status', ['pending', 'verified', 'rejected'])->default('pending');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properti');
    }
};
