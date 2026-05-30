<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $table      = 'kamar';
    protected $primaryKey = 'id_kamar';

    protected $fillable = [
        'id_properti',
        'nama_kamar',
        'kapasitas',
        'jumlah_tempat_tidur',
        'tipe_tempat_tidur',
        'harga_per_malam',
        'status',
    ];

    protected $casts = [
        'harga_per_malam' => 'decimal:2',
    ];

    // N:1 — banyak kamar milik satu properti
    public function properti()
    {
        return $this->belongsTo(Properti::class, 'id_properti', 'id_properti');
    }
}
