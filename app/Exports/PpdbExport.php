<?php

namespace App\Exports;

use App\Models\Ppdb;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PpdbExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Ppdb::select(
            'tahun',
            'jumlah_pendaftar',
            'jumlah_diterima',
            'jumlah_registrasi',
            'asal_sekolah',
            'asal_daerah'
        )->orderBy('tahun')->get();
    }

    public function headings(): array
    {
        return [
            'Tahun',
            'Jumlah Pendaftar',
            'Jumlah Diterima',
            'Jumlah Registrasi',
            'Asal Sekolah',
            'Asal Daerah'
        ];
    }
}
