<?php

namespace App\Imports;

use App\Models\PpdbDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PpdbDetailImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return PpdbDetail::updateOrCreate(
            [
                'tahun' => $row['tahun'],
                'kategori' => $row['kategori'],
                'sub_kategori' => $row['sub_kategori'],
            ],
            [
                'jumlah' => $row['jumlah'],
            ]
        );
    }
}
