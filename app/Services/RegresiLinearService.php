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

        $i = 1; // 🔥 index dimulai dari 1

        foreach ($data as $row) {
            $x = $i; // 🔥 pakai index, bukan tahun
            $y = (int) $row->total_siswa;

            $sumX  += $x;
            $sumY  += $y;
            $sumXY += $x * $y;
            $sumX2 += $x * $x;

            $i++;
        }

        $denom = ($n * $sumX2 - pow($sumX, 2));

        if ($denom == 0) {
            return null;
        }

        $b = ($n * $sumXY - $sumX * $sumY) / $denom;
        $a = ($sumY - $b * $sumX) / $n;

        // 🔥 prediksi pakai index berikutnya (n+1)
        $nextX = $n + 1;
        $hasilPrediksi = round($a + ($b * $nextX));

        // tetap tampilkan tahun 2026
        $tahunTerakhir = $data->last()->tahun;
        $tahunPrediksi = $tahunTerakhir + 1;

        return [
            'a' => round($a, 4),
            'b' => round($b, 4),
            'tahun_prediksi' => $tahunPrediksi,
            'hasil_prediksi' => $hasilPrediksi,
            'data' => $data
        ];
    }
}