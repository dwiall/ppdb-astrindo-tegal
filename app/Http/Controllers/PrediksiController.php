<?php

namespace App\Http\Controllers;

use App\Models\PpdbSummary;
use Illuminate\Http\Request;
use App\Services\PrediksiService;
use App\Services\ProdiPredictionService;

class PrediksiController extends Controller
{
    public function index(Request $request)
    {
        $metode = $request->get('metode', 'regresi_linear');

        // Ambil hasil perhitungan global & per prodi
        $hasil = ProdiPredictionService::hitungSemua();

        if (empty($hasil['tahun'])) {
            return view('prediksi.index', [
                'metode' => $metode,
                'tahunPrediksi' => '-',
                'hasilPrediksi' => 0,
                'r2' => 0,
                'mape' => 0,
                'chartTahun' => [],
                'chartAktualGlobal' => [],
                'chartPrediksiProdi' => [],
                'statProdi' => [],
            ]);
        }

        $tahun = $hasil['tahun'];

        $global = $hasil['global'];

        // Tentukan data global sesuai metode terpilih
        if ($metode === 'moving_average') {
            $chartPredGlobal = $global['chart_prediksi_moving'];
            $r2 = 0;
            $mapeGlobal = $global['moving_average']['mape'];
        } else {
            $chartPredGlobal = $global['chart_prediksi_regresi'];
            $r2 = $global['regresi']['r2'];
            $mapeGlobal = $global['regresi']['mape'];
        }

        $tahunPrediksi = end($tahun);
        $hasilPrediksi = $metode === 'moving_average'
            ? $global['moving_average']['next_prediction']
            : $global['regresi']['next_prediction'];

        // Siapkan statistik per prodi dan data chart multi-line (aktual + prediksi)
        $labelProdi = ['AKL', 'DKV', 'TKJ', 'TO'];
        $chartAktualProdi   = [];
        $chartPrediksiProdi = [];
       $statProdi = [];

        foreach ($labelProdi as $prodi) {
            $dataProdi = $hasil[$prodi];

            $aktual = $dataProdi['chart_aktual'];

            if ($metode === 'moving_average') {
                $predSeries = $dataProdi['chart_prediksi_moving'];
                $mape = $dataProdi['moving_average']['mape'];
                $r2Prodi = 0;
            } else {
                $predSeries = $dataProdi['chart_prediksi_regresi'];
                $mape = $dataProdi['regresi']['mape'];
                $r2Prodi = $dataProdi['regresi']['r2'];
            }

            $chartAktualProdi[$prodi]   = $aktual;
            $chartPrediksiProdi[$prodi] = $predSeries;

            $statProdi[$prodi] = [
                'prediksi_tahun_depan' => end($predSeries),
                'mape' => $mape,
                'r2' => $r2Prodi,
            ];
        }

        return view('prediksi.index', [
            'metode' => $metode,
            'tahunPrediksi' => $tahunPrediksi,
            'hasilPrediksi' => $hasilPrediksi,
            'r2' => $r2,
            'mape' => $mapeGlobal,
            'chartTahun'        => $tahun,
            'chartAktualProdi'  => $chartAktualProdi,
            'chartPrediksiProdi'=> $chartPrediksiProdi,
            'statProdi'         => $statProdi,
        ]);
    }
}
