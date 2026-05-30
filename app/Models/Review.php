<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table      = 'review';
    protected $primaryKey = 'id_review';

    protected $fillable = [
        'id_pesanan',
        'id_user',
        'id_properti',
        'rating',
        'komentar',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // ===========================
    // RELASI
    // ===========================

    // 1:1 balik — review dari satu pemesanan
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pesanan', 'id_pesanan');
    }

    // N:1 — review ditulis satu user (traveler)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // N:1 — review untuk satu properti
    public function properti()
    {
        return $this->belongsTo(Properti::class, 'id_properti', 'id_properti');
    }
}