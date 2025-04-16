<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);  // Menggunakan relasi belongsTo
    }
}

