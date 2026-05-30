<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table      = 'fasilitas';
    protected $primaryKey = 'id_fasilitas';
    public    $timestamps = false; // data master statis

    protected $fillable = [
        'nama_fasilitas',
        'ikon_fasilitas',
    ];

    // ===========================
    // RELASI
    // ===========================

    // M:N — satu fasilitas dipakai banyak properti (lewat pivot)
    public function properti()
    {
        return $this->belongsToMany(
            Properti::class,
            'fasilitas_properti',
            'id_fasilitas',
            'id_properti'
        );
    }
}