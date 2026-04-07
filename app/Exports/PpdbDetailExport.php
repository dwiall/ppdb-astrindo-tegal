<?php

namespace App\Exports;

use App\Models\PpdbDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PpdbDetailExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return PpdbDetail::select(
            'tahun',
            'kategori',
            'sub_kategori',
            'jumlah'
        )
        ->orderBy('tahun', 'asc')
        ->orderBy('kategori', 'asc')
        ->get();
    }

    public function headings(): array
    {
        return [
            'Tahun',
            'Kategori',
            'Sub Kategori',
            'Jumlah Peserta Didik'
        ];
    }
}
