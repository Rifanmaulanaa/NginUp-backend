<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table      = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    public    $timestamps = false;

    protected $fillable = [
        'id_pesanan',
        'code_pembayaran',
        'jumlah_pembayaran',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status_pembayaran',
        'tanggal_pembayaran',
        'expired_at',
    ];

    protected $casts = [
        'jumlah_pembayaran'  => 'decimal:2',
        'tanggal_pembayaran' => 'datetime',
        'expired_at'         => 'datetime',
    ];

    // ===========================
    // RELASI
    // ===========================

    // 1:1 balik — pembayaran milik satu pemesanan
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pesanan', 'id_pesanan');
    }
}