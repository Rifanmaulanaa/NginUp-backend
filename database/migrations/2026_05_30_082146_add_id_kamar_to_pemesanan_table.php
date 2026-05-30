<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kamar')->nullable()->after('id_properti');
            $table->foreign('id_kamar')->references('id_kamar')->on('kamar')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropForeign(['id_kamar']);
            $table->dropColumn('id_kamar');
        });
    }
};
