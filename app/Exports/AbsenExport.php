<?php

namespace App\Exports;

use App\Models\AbsenKerja;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsenExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return AbsenKerja::with('user')->get()->map(function ($item) {
            return [
                'Nama' => $item->user->name,
                'Status' => ucfirst($item->status_masuk),
                'Waktu Mulai' => $item->waktu_mulai_kerja,
                'Waktu Selesai' => $item->waktu_akhir_kerja,
                'Tanggal' => $item->created_at->format('d-m-Y')
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Status',
            'Waktu Mulai',
            'Waktu Selesai',
            'Tanggal'
        ];
    }
}
