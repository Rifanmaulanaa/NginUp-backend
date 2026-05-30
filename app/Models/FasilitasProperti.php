<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FasilitasProperti extends Model
{
    use HasFactory;

    protected $table      = 'fasilitas_properti';
    protected $primaryKey = 'id_fasilitas_properti';
    public    $timestamps = false;

    protected $fillable = [
        'id_properti',
        'id_fasilitas',
    ];

    // ===========================
    // RELASI
    // ===========================

    public function properti()
    {
        return $this->belongsTo(Properti::class, 'id_properti', 'id_properti');
    }

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'id_fasilitas', 'id_fasilitas');
    }
}