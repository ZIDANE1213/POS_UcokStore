<?php

namespace App\Imports;

use App\Models\AbsenKerja;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AbsenImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new AbsenKerja([
            'user_id' => $row['user_id'],
            'status_masuk' => $row['status_masuk'],
            'tanggal_masuk' => \Carbon\Carbon::parse($row['tanggal_masuk']),
            'waktu_mulai_kerja' => $row['status_masuk'] == 'masuk' ? now()->format('H:i:s') : null,
            'waktu_akhir_kerja' => in_array($row['status_masuk'], ['sakit', 'cuti']) ? '00:00:00' : null
        ]);
    }
}
