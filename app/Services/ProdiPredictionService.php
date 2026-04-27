<?php

namespace App\Services;

use App\Models\PpdbSummary;

class ProdiPredictionService
{
    public static function hitungSemua(): array
    {
        $data = PpdbSummary::orderBy('tahun')->get();

        if ($data->count() < 2) {
            return [
                'tahun' => [],
                'global' => [],
                'AKL' => [],
                'DKV' => [],
                'TKJ' => [],
                'TO' => [],
            ];
        }

        $tahun = $data->pluck('tahun')->map(fn($v) => (int)$v)->toArray();

        $seri = [
            'global' => $data->pluck('total_siswa')->map(fn($v) => (int)$v)->toArray(),
            'AKL'    => $data->pluck('total_akl')->map(fn($v) => (int)$v)->toArray(),
            'DKV'    => $data->pluck('total_dkv')->map(fn($v) => (int)$v)->toArray(),
            'TKJ'    => $data->pluck('total_tkj')->map(fn($v) => (int)$v)->toArray(),
            'TO'     => $data->pluck('total_to')->map(fn($v) => (int)$v)->toArray(),
        ];

        $hasil = [
            'tahun' => array_merge($tahun, [max($tahun) + 1]),
        ];

        foreach ($seri as $key => $y) {
            $hasil[$key] = self::hitungUntukSeri($y);
        }

        return $hasil;
    }

    public static function hitungUntukSeri(array $y): array
    {
        $n = count($y);

        $x = range(1, $n);

        $regresi = PrediksiService::hitungRegresiLinear($x, $y);
        $moving  = PrediksiService::hitungMovingAverage($y);

        $chartPredRegresi = [];
        $chartPredMoving  = [];

        for ($i = 0; $i < $n; $i++) {
            $chartPredRegresi[] = $regresi['y_pred'][$i] ?? null;
            $chartPredMoving[]  = $moving['y_pred'][$i] ?? null;
        }

        $chartPredRegresi[] = $regresi['next_prediction'];
        $chartPredMoving[]  = $moving['next_prediction'];

        // Pilih metode terbaik
        if ($regresi['mape'] <= $moving['mape']) {
            $metodeTerbaik      = 'regresi_linear';
            $chartPredTerbaik   = $chartPredRegresi;
            $prediksiTahunDepan = (int) $regresi['next_prediction'];
            $mapeTerbaik        = $regresi['mape'];
            $r2Terbaik          = $regresi['r2'];
        } else {
            $metodeTerbaik      = 'moving_average';
            $chartPredTerbaik   = $chartPredMoving;
            $prediksiTahunDepan = (int) $moving['next_prediction'];
            $mapeTerbaik        = $moving['mape'];
            $r2Terbaik          = null;
        }

        return [
            'metode_terbaik'          => $metodeTerbaik,
            'regresi'                 => $regresi,
            'moving_average'          => $moving,
            'ma_2025'                 => $moving['ma_2025'] ?? null,
            'error_2025'              => $moving['error_2025'] ?? null,
            'chart_aktual'            => $y,
            'chart_prediksi_regresi'  => $chartPredRegresi,
            'chart_prediksi_moving'   => $chartPredMoving,
            'chart_prediksi_terbaik'  => $chartPredTerbaik,
            'prediksi_tahun_depan'    => $prediksiTahunDepan,
            'mape_terbaik'            => $mapeTerbaik,
            'r2_terbaik'              => $r2Terbaik,
        ];
    }
}