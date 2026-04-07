<?php

namespace App\Http\Controllers;

use App\Models\PpdbSummary;

class DashboardController extends Controller
{
    public function index()
    {
        // =========================
        // AMBIL DATA SUMMARY
        // =========================
        $data = PpdbSummary::orderBy('tahun')->get();

        // =========================
        // GUARD: DATA MINIMAL
        // =========================
        if ($data->count() < 2) {
            return view('dashboard.index', [
                'tahunTerakhir'        => '-',
                'siswaTahunTerakhir'   => 0,
                'pertumbuhanText'      => 'Data belum cukup untuk analisis',
                'tahunPrediksi'        => '-',
                'hasilPrediksi'        => 0,
                'r2'                   => 0,
                'mape'                 => 0,
                'chartTahun'           => [],
                'chartAktual'          => [],
                'chartPrediksi'        => [],
            ]);
        }

        $x = $data->pluck('tahun')->toArray();
        $y = $data->pluck('total_siswa')->toArray();
        $n = count($x);

        // =========================
        // REGRESI LINEAR
        // =========================
        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumXY = 0;
        $sumX2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $sumXY += $x[$i] * $y[$i];
            $sumX2 += $x[$i] * $x[$i];
        }

        $penyebut = ($n * $sumX2) - ($sumX ** 2);
        $b = $penyebut != 0 ? (($n * $sumXY - $sumX * $sumY) / $penyebut) : 0;
        $a = ($sumY - ($b * $sumX)) / $n;

        // =========================
        // TAHUN TERAKHIR & PREDIKSI
        // =========================
        $tahunTerakhir = max($x);
        $siswaTahunTerakhir = $data
            ->where('tahun', $tahunTerakhir)
            ->first()
            ->total_siswa;

        $tahunPrediksi = $tahunTerakhir + 1;
        $hasilPrediksi = round($a + ($b * $tahunPrediksi));

        // =========================
        // R² (KOEFISIEN DETERMINASI)
        // =========================
        $meanY = array_sum($y) / $n;
        $ssTot = 0;
        $ssRes = 0;

        for ($i = 0; $i < $n; $i++) {
            $yPred = $a + ($b * $x[$i]);
            $ssTot += pow($y[$i] - $meanY, 2);
            $ssRes += pow($y[$i] - $yPred, 2);
        }

        $r2 = $ssTot != 0 ? 1 - ($ssRes / $ssTot) : 0;

        // =========================
        // MAPE
        // =========================
        $mapeTotal = 0;
        $valid = 0;

        for ($i = 0; $i < $n; $i++) {
            if ($y[$i] != 0) {
                $yPred = $a + ($b * $x[$i]);
                $mapeTotal += abs(($y[$i] - $yPred) / $y[$i]);
                $valid++;
            }
        }

        $mape = $valid > 0 ? ($mapeTotal / $valid) * 100 : 0;

        // =========================
        // DATA GRAFIK
        // =========================
        $chartTahun = array_merge($x, [$tahunPrediksi]);
        $chartAktual = $y;
        $chartPrediksi = [];

        foreach ($chartTahun as $tahun) {
            $chartPrediksi[] = round($a + ($b * $tahun));
        }

        // =========================
        // PERTUMBUHAN (AUTO TEKS)
        // =========================
        $tahunSebelumnya = $tahunTerakhir - 1;
        $siswaSebelumnya = $data
            ->where('tahun', $tahunSebelumnya)
            ->first()
            ->total_siswa ?? 0;

        if ($siswaSebelumnya > 0) {
            $pertumbuhan = (($siswaTahunTerakhir - $siswaSebelumnya) / $siswaSebelumnya) * 100;
            $persen = number_format(abs($pertumbuhan), 1, ',', '.');

            if ($pertumbuhan > 0) {
                $pertumbuhanText = "Naik {$persen}% dari tahun {$tahunSebelumnya}";
            } elseif ($pertumbuhan < 0) {
                $pertumbuhanText = "Turun {$persen}% dari tahun {$tahunSebelumnya}";
            } else {
                $pertumbuhanText = "Tidak ada perubahan dari tahun {$tahunSebelumnya}";
            }
        } else {
            $pertumbuhanText = "Data tahun sebelumnya tidak tersedia";
        }

        return view('dashboard.index', compact(
            'tahunTerakhir',
            'siswaTahunTerakhir',
            'pertumbuhanText',
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
