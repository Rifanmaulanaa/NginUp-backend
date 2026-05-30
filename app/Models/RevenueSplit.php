<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevenueSplit extends Model
{
    use HasFactory;

    protected $table      = 'revenue_splits';
    protected $primaryKey = 'id_revenue_split';
    public    $timestamps = false;

    protected $fillable = [
        'id_pesanan',
        'id_user',
        'jumlah_kotor',
        'persentase_biaya_platform',
        'jumlah_biaya_platform',
        'jumlah_pemilik',
        'status',
        'settled_at',
    ];

    protected $casts = [
        'jumlah_kotor'              => 'decimal:2',
        'persentase_biaya_platform' => 'decimal:2',
        'jumlah_biaya_platform'     => 'decimal:2',
        'jumlah_pemilik'            => 'decimal:2',
        'settled_at'                => 'datetime',
    ];

    // ===========================
    // RELASI
    // ===========================

    // 1:1 balik — revenue split dari satu pemesanan
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pesanan', 'id_pesanan');
    }

    // N:1 — bagi hasil diterima satu owner
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}