<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'membership'];

    protected $hidden = ['password'];

    // Relasi dengan model Absen
    public function absens()
    {
        return $this->hasMany(Absen::class);  // Menggunakan relasi hasMany
    }

    // public function produk()
    // {
    //     return $this->hasMany(produk::class);
    // }

    // public function pembelian()
    // {
    //     return $this->hasMany(Pembelian::class);
    // }

    // public function penjualan()
    // {
    //     return $this->hasMany(Penjualan::class);
    // }
}

    
