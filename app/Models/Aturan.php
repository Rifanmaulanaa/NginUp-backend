<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aturan extends Model
{
    use HasFactory;

    protected $table      = 'aturan';
    protected $primaryKey = 'id_aturan';
    public    $timestamps = false; // data master statis

    protected $fillable = [
        'text_aturan',
    ];

    // ===========================
    // RELASI
    // ===========================

    // M:N — satu aturan diterapkan di banyak properti (lewat pivot)
    public function properti()
    {
        return $this->belongsToMany(
            Properti::class,
            'properti_aturan',
            'id_aturan',
            'id_properti'
        );
    }
}