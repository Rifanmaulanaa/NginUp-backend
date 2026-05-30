<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table      = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';
    public    $timestamps = false; // hanya created_at manual

    protected $fillable = [
        'id_user',
        'title',
        'pesan',
        'type',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // ===========================
    // RELASI
    // ===========================

    // N:1 — banyak notifikasi milik satu user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}