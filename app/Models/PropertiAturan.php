<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertiAturan extends Model
{
    use HasFactory;

    protected $table      = 'properti_aturan';
    protected $primaryKey = 'id_properti_aturan';
    public    $timestamps = false;

    protected $fillable = [
        'id_properti',
        'id_aturan',
    ];

    // ===========================
    // RELASI
    // ===========================

    public function properti()
    {
        return $this->belongsTo(Properti::class, 'id_properti', 'id_properti');
    }

    public function aturan()
    {
        return $this->belongsTo(Aturan::class, 'id_aturan', 'id_aturan');
    }
}