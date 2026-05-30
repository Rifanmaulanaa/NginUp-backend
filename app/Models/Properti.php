<?php

namespace App\Models;

use App\Models\Kamar;
use App\Models\Fasilitas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Properti extends Model
{
    use HasFactory;

    protected $table      = 'properti';
    protected $primaryKey = 'id_properti';

    protected $fillable = [
        'id_user',
        'nama_properti',
        'tipe_properti',
        'deskripsi',
        'alamat',
        'kota',
        'provinsi',
        'latitude',
        'longitude',
        'harga_per_malam',
        'max_tamu',
        'jumlah_ruang',
        'status',
        'verified_status',
    ];

    // ===========================
    // RELASI
    // ===========================

    // N:1 — banyak properti dimiliki satu user (owner)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // 1:N — satu properti punya banyak foto
    public function gambar()
    {
        return $this->hasMany(Gambar::class, 'id_properti', 'id_properti');
    }

    // M:N — satu properti punya banyak fasilitas (lewat pivot)
    public function fasilitas()
    {
        return $this->belongsToMany(
            Fasilitas::class,
            'fasilitas_properti',   // nama tabel pivot
            'id_properti',          // FK kolom ini di pivot
            'id_fasilitas'          // FK kolom target di pivot
        );
    }

    // M:N — satu properti punya banyak aturan (lewat pivot)
    public function aturan()
    {
        return $this->belongsToMany(
            Aturan::class,
            'properti_aturan',      // nama tabel pivot
            'id_properti',          // FK kolom ini di pivot
            'id_aturan'             // FK kolom target di pivot
        );
    }

    // 1:N — satu properti punya banyak blokir tanggal
    public function ketersediaan()
    {
        return $this->hasMany(KetersediaanProperti::class, 'id_properti', 'id_properti');
    }

    // 1:N — satu properti bisa dipesan berkali-kali
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_properti', 'id_properti');
    }

    // 1:N — satu properti punya banyak review
    public function review()
    {
        return $this->hasMany(Review::class, 'id_properti', 'id_properti');
    }

    // 1:N — satu properti punya banyak kamar
    public function kamar()
    {
        return $this->hasMany(Kamar::class, 'id_properti', 'id_properti');
    }

    // ===========================
    // APPENDS
    // ===========================
    protected $appends = ['review_avg_rating'];

    public function getReviewAvgRatingAttribute()
    {
        return $this->review()->avg('rating') ?? 0;
    }
}