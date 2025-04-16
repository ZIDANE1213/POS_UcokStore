<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsenKerja extends Model
{
    protected $table = 'absen_kerjas'; // atau nama tabel yang sesuai
    protected $fillable = [
        'user_id', 'status_masuk', 'waktu_mulai_kerja', 'waktu_akhir_kerja'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

