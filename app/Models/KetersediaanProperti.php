<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetersediaanProperti extends Model
{
    use HasFactory;

    protected $table      = 'ketersediaan_properti';
    protected $primaryKey = 'id_ketersediaan_properti';
    public    $timestamps = false;

    protected $fillable = [
        'id_properti',
        'blocked_from',
        'blocked_until',
        'status_ketersediaan',
    ];

    protected $casts = [
        'blocked_from'  => 'date',
        'blocked_until' => 'date',
    ];

    // ===========================
    // RELASI
    // ===========================

    // N:1 — banyak record ketersediaan milik satu properti
    public function properti()
    {
        return $this->belongsTo(Properti::class, 'id_properti', 'id_properti');
    }
}