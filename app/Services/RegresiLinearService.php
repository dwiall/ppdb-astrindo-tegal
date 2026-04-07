<?php

namespace App\Services;

use App\Models\PpdbSummary;

class RegresiLinearService
{
    public static function hitung()
    {
        $data = PpdbSummary::orderBy('tahun')->get();

        $n = $data->count();
        if ($n < 2) {
            return null;
        }

        $sumX = $sumY = $sumXY = $sumX2 = 0;

        foreach ($data as $row) {
            $x = (int) $row->tahun;
            $y = (int) $row->total_siswa;

            $sumX  += $x;
            $sumY  += $y;
            $sumXY += $x * $y;
            $sumX2 += $x * $x;
        }

        $b = ($n * $sumXY - $sumX * $sumY)
           / ($n * $sumX2 - pow($sumX, 2));

        $a = ($sumY - $b * $sumX) / $n;

        $tahunTerakhir = $data->last()->tahun;
        $tahunPrediksi = $tahunTerakhir + 1;
        $hasilPrediksi = round($a + ($b * $tahunPrediksi));

        return [
            'a' => round($a, 4),
            'b' => round($b, 4),
            'tahun_prediksi' => $tahunPrediksi,
            'hasil_prediksi' => $hasilPrediksi,
            'data' => $data
        ];
    }
}
