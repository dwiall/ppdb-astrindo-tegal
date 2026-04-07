<?php

namespace App\Http\Controllers;

use App\Models\PpdbSummary;
use App\Services\ProdiPredictionService;

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
                'metodeTerbaikGlobal'  => '-',
                'tahunTerakhir'        => '-',
                'siswaTahunTerakhir'   => 0,
                'pertumbuhanText'      => 'Data belum cukup untuk analisis',
                'tahunPrediksi'        => '-',
                'hasilPrediksi'        => 0,
                'r2'                   => 0,
                'mape'                 => 0,
                'chartTahun'           => [],
                'chartAktualProdi'     => [],
                'chartPrediksiProdi'   => [],
                'statProdi'            => [],
            ]);
        }

        $hasil = ProdiPredictionService::hitungSemua();

        $tahun  = $hasil['tahun'];
        $global = $hasil['global'];

        // =========================
        // GLOBAL: PAKAI METODE TERBAIK (BERDASARKAN MAPE)
        // =========================
        $metodeTerbaikGlobal = $global['metode_terbaik'];
        if ($metodeTerbaikGlobal === 'moving_average') {
            $chartPredGlobal = $global['chart_prediksi_moving'];
            $r2 = 0;
            $mape = $global['moving_average']['mape'];
        } else {
            $chartPredGlobal = $global['chart_prediksi_regresi'];
            $r2 = $global['regresi']['r2'];
            $mape = $global['regresi']['mape'];
        }

        // =========================
        // TAHUN TERAKHIR & PREDIKSI
        // =========================
        $tahunTerakhir = $data->max('tahun');
        $siswaTahunTerakhir = $data->where('tahun', $tahunTerakhir)->first()->total_siswa ?? 0;

        $tahunPrediksi = end($tahun);
        $hasilPrediksi = end($chartPredGlobal);

        // =========================
        // PERTUMBUHAN (AUTO TEKS)
        // =========================
        $tahunSebelumnya = $tahunTerakhir - 1;
        $siswaSebelumnya = $data->where('tahun', $tahunSebelumnya)->first()->total_siswa ?? 0;

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

        // =========================
        // STATISTIK PER PRODI & DATA GRAFIK
        // =========================
        $labelProdi = ['AKL', 'DKV', 'TKJ', 'TO'];
        $chartAktualProdi   = [];
        $chartPrediksiProdi = [];
        $statProdi = [];

        foreach ($labelProdi as $prodi) {
            $dataProdi = $hasil[$prodi];

            $aktual = $dataProdi['chart_aktual'];
            $predTerbaik = $dataProdi['chart_prediksi_terbaik'];

            $chartAktualProdi[$prodi]   = $aktual;
            $chartPrediksiProdi[$prodi] = $predTerbaik;

            $statProdi[$prodi] = [
                'prediksi_tahun_depan' => end($predTerbaik),
                'mape' => $dataProdi['mape_terbaik'],
            ];
        }

        return view('dashboard.index', [
            'metodeTerbaikGlobal' => $metodeTerbaikGlobal,
            'tahunTerakhir'       => $tahunTerakhir,
            'siswaTahunTerakhir'  => $siswaTahunTerakhir,
            'pertumbuhanText'     => $pertumbuhanText,
            'tahunPrediksi'       => $tahunPrediksi,
            'hasilPrediksi'       => $hasilPrediksi,
            'r2'                  => $r2,
            'mape'                => $mape,
            'chartTahun'          => $tahun,
            'chartAktualProdi'    => $chartAktualProdi,
            'chartPrediksiProdi'  => $chartPrediksiProdi,
            'statProdi'           => $statProdi,
        ]);
    }
}
