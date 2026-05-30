<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'no_hp',
        'foto_profil',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ===========================
    // RELASI
    // ===========================

    // 1:N — satu user (owner) punya banyak properti
    public function properti()
    {
        return $this->hasMany(Properti::class, 'id_user', 'id_user');
    }

    // 1:N — satu user (traveler) buat banyak pemesanan
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_user', 'id_user');
    }

    // 1:N — satu user tulis banyak review
    public function review()
    {
        return $this->hasMany(Review::class, 'id_user', 'id_user');
    }

    // 1:N — satu user terima banyak notifikasi
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'id_user', 'id_user');
    }

    // 1:N — satu owner terima banyak bagi hasil
    public function revenueSplit()
    {
        return $this->hasMany(RevenueSplit::class, 'id_user', 'id_user');
    }
}
