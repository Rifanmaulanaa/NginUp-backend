<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table      = 'pemesanan';
    protected $primaryKey = 'id_pesanan';
    public    $timestamps = false; // hanya pakai created_at manual

    protected $fillable = [
        'id_user',
        'id_properti',
        'id_kamar',
        'tanggal_check_in',
        'tanggal_check_out',
        'total_malam',
        'total_tamu',
        'total_harga',
        'status_pemesanan',
        'catatan_traveler',
    ];

    protected $casts = [
        'tanggal_check_in'  => 'date',
        'tanggal_check_out' => 'date',
        'total_harga'       => 'decimal:2',
    ];

    // ===========================
    // RELASI
    // ===========================

    // N:1 — banyak pemesanan dibuat satu user (traveler)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // N:1 — banyak pemesanan untuk satu properti
    public function properti()
    {
        return $this->belongsTo(Properti::class, 'id_properti', 'id_properti');
    }

    // N:1 — banyak pemesanan untuk satu kamar (optional)
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }

    // 1:1 — satu pemesanan punya tepat satu pembayaran
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pesanan', 'id_pesanan');
    }

    // 1:1 — satu pemesanan punya tepat satu bagi hasil
    public function revenueSplit()
    {
        return $this->hasOne(RevenueSplit::class, 'id_pesanan', 'id_pesanan');
    }

    // 1:0,1 — satu pemesanan boleh tidak punya review
    public function review()
    {
        return $this->hasOne(Review::class, 'id_pesanan', 'id_pesanan');
    }
}