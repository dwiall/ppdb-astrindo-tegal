<?php

namespace App\Services;

use App\Models\PpdbSummary;

class ProdiPredictionService
{
    /**
     * Hitung hasil prediksi (regresi linear & moving average) untuk total dan tiap prodi.
     *
     * @return array{
     *   tahun: array<int, int>,
     *   global: array,
     *   AKL: array,
     *   DKV: array,
     *   TKJ: array,
     *   TO: array
     * }
     */
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

        $tahun = $data->pluck('tahun')->map(function ($v) {
            return (int) $v;
        })->toArray();

        $seri = [
            'global' => $data->pluck('total_siswa')->map(function ($v) {
                return (int) $v;
            })->toArray(),
            'AKL'    => $data->pluck('total_akl')->map(function ($v) {
                return (int) $v;
            })->toArray(),
            'DKV'    => $data->pluck('total_dkv')->map(function ($v) {
                return (int) $v;
            })->toArray(),
            'TKJ'    => $data->pluck('total_tkj')->map(function ($v) {
                return (int) $v;
            })->toArray(),
            'TO'     => $data->pluck('total_to')->map(function ($v) {
                return (int) $v;
            })->toArray(),
        ];

        $hasil = [
            'tahun' => array_merge($tahun, [max($tahun) + 1]),
        ];

        foreach ($seri as $key => $y) {
            $hasil[$key] = self::hitungUntukSeri($tahun, $y);
        }

        return $hasil;
    }

    /**
     * Hitung kedua metode dan pilih yang terbaik berdasarkan MAPE.
     *
     * @param  array<int,int> $tahun
     * @param  array<int,int> $y
     * @return array{
     *   metode_terbaik: string,
     *   regresi: array,
     *   moving_average: array,
     *   chart_aktual: array<int,int>,
     *   chart_prediksi_regresi: array<int,float>,
     *   chart_prediksi_moving: array<int,float>,
     *   chart_prediksi_terbaik: array<int,float>,
     *   prediksi_tahun_depan: int,
     *   mape_terbaik: float,
     *   r2_terbaik: float|null
     * }
     */
    public static function hitungUntukSeri(array $tahun, array $y): array
    {
        $regresi = PrediksiService::hitungRegresiLinear($tahun, $y);
        $moving  = PrediksiService::hitungMovingAverage($y);

        $tahunPrediksi = max($tahun) + 1;

        // Bentuk chart prediksi untuk semua tahun + satu titik prediksi depan
        $chartPredRegresi = [];
        $chartPredMoving  = [];

        foreach ($tahun as $index => $t) {
            $chartPredRegresi[] = $regresi['y_pred'][$index] ?? 0;
            $chartPredMoving[]  = $moving['y_pred'][$index] ?? 0;
        }

        $chartPredRegresi[] = $regresi['next_prediction'];
        $chartPredMoving[]  = $moving['next_prediction'];

        // Pilih metode terbaik berdasar MAPE
        if ($regresi['mape'] <= $moving['mape']) {
            $metodeTerbaik       = 'regresi_linear';
            $chartPredTerbaik    = $chartPredRegresi;
            $prediksiTahunDepan  = (int) $regresi['next_prediction'];
            $mapeTerbaik         = $regresi['mape'];
            $r2Terbaik           = $regresi['r2'];
        } else {
            $metodeTerbaik       = 'moving_average';
            $chartPredTerbaik    = $chartPredMoving;
            $prediksiTahunDepan  = (int) $moving['next_prediction'];
            $mapeTerbaik         = $moving['mape'];
            $r2Terbaik           = null;
        }

        return [
            'metode_terbaik'          => $metodeTerbaik,
            'regresi'                 => $regresi,
            'moving_average'          => $moving,
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

