<?php

namespace App\Imports;

use App\Models\PpdbSummary;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PpdbSummaryImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return PpdbSummary::updateOrCreate(
            ['tahun' => $row['tahun']], // kunci unik
            ['total_siswa' => $row['total_siswa']]
        );
    }
}
