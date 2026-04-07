<?php

namespace App\Http\Controllers;

use App\Models\PpdbSummary;
use Illuminate\Http\Request;

class PrediksiController extends Controller
{
    public function index()
    {
        // Ambil data summary (tahun & total siswa)
        $data = PpdbSummary::orderBy('tahun')->get();

        // X = tahun, Y = total siswa
        $x = $data->pluck('tahun')->toArray();
        $y = $data->pluck('total_siswa')->toArray();

        $n = count($x);

        // ======================
        // REGRESI LINEAR
        // ======================
        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumXY = 0;
        $sumX2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $sumXY += $x[$i] * $y[$i];
            $sumX2 += $x[$i] * $x[$i];
        }

        $b = (($n * $sumXY) - ($sumX * $sumY)) / (($n * $sumX2) - ($sumX ** 2));
        $a = ($sumY - ($b * $sumX)) / $n;

        // ======================
        // PREDIKSI TAHUN DEPAN
        // ======================
        $tahunPrediksi = max($x) + 1;
        $hasilPrediksi = round($a + ($b * $tahunPrediksi));

        // ======================
        // HITUNG R²
        // ======================
        $meanY = array_sum($y) / $n;
        $ssTot = 0;
        $ssRes = 0;

        for ($i = 0; $i < $n; $i++) {
            $yPred = $a + ($b * $x[$i]);
            $ssTot += pow($y[$i] - $meanY, 2);
            $ssRes += pow($y[$i] - $yPred, 2);
        }

        $r2 = 1 - ($ssRes / $ssTot);

        // ======================
        // HITUNG MAPE
        // ======================
        $mapeTotal = 0;
        for ($i = 0; $i < $n; $i++) {
            $yPred = $a + ($b * $x[$i]);
            if ($y[$i] != 0) {
                $mapeTotal += abs(($y[$i] - $yPred) / $y[$i]);
            }
        }

        $mape = ($mapeTotal / $n) * 100;

        // ======================
        // DATA UNTUK GRAFIK
        // ======================
        $chartTahun = array_merge($x, [$tahunPrediksi]);
        $chartAktual = $y;
        $chartPrediksi = [];

        foreach ($chartTahun as $tahun) {
            $chartPrediksi[] = round($a + ($b * $tahun));
        }

        return view('prediksi.index', compact(
            'tahunPrediksi',
            'hasilPrediksi',
            'r2',
            'mape',
            'chartTahun',
            'chartAktual',
            'chartPrediksi'
        ));
    }
}
