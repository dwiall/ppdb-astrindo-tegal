<?php

namespace App\Services;

class PrediksiService
{
    public const MOVING_AVERAGE_WINDOW = 3;

    /**
     * Hitung regresi linear untuk satu seri data.
     *
     * @param  array<int, float|int>  $x  biasanya tahun
     * @param  array<int, float|int>  $y  nilai data (total siswa)
     * @return array{
     *     a: float,
     *     b: float,
     *     y_pred: array<int, float>,
     *     r2: float,
     *     mape: float,
     *     next_prediction: float
     * }
     */
    public static function hitungRegresiLinear(array $x, array $y): array
    {
        $n = count($x);

        if ($n < 2) {
            return [
                'a' => 0.0,
                'b' => 0.0,
                'y_pred' => [],
                'r2' => 0.0,
                'mape' => 0.0,
                'next_prediction' => 0.0,
            ];
        }

        $sumX  = array_sum($x);
        $sumY  = array_sum($y);
        $sumXY = 0;
        $sumX2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $xi = (float) $x[$i];
            $yi = (float) $y[$i];

            $sumXY += $xi * $yi;
            $sumX2 += $xi * $xi;
        }

        $denom = ($n * $sumX2) - ($sumX ** 2);
        if ($denom == 0.0) {
            return [
                'a' => 0.0,
                'b' => 0.0,
                'y_pred' => $y,
                'r2' => 0.0,
                'mape' => 0.0,
                'next_prediction' => 0.0,
            ];
        }

        $b = (($n * $sumXY) - ($sumX * $sumY)) / $denom;
        $a = ($sumY - ($b * $sumX)) / $n;

        // Prediksi untuk titik-titik yang ada
        $yPred = [];
        for ($i = 0; $i < $n; $i++) {
            $yPred[] = $a + ($b * $x[$i]);
        }

        // R^2
        $meanY = $sumY / $n;
        $ssTot = 0.0;
        $ssRes = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $ssTot += ($y[$i] - $meanY) ** 2;
            $ssRes += ($y[$i] - $yPred[$i]) ** 2;
        }
        $r2 = $ssTot != 0.0 ? 1 - ($ssRes / $ssTot) : 0.0;

        // MAPE
        $mapeSum = 0.0;
        $valid   = 0;
        for ($i = 0; $i < $n; $i++) {
            $actual = (float) $y[$i];
            if ($actual != 0.0) {
                $mapeSum += abs(($actual - $yPred[$i]) / $actual);
                $valid++;
            }
        }
        $mape = $valid > 0 ? ($mapeSum / $valid) * 100.0 : 0.0;

        // Prediksi tahun berikutnya diasumsikan x bertambah 1 dari nilai maksimum
        $nextX = max($x) + 1;
        $nextPrediction = $a + ($b * $nextX);

        return [
            'a' => round($a, 4),
            'b' => round($b, 4),
            'y_pred' => $yPred,
            'r2' => round($r2, 4),
            'mape' => round($mape, 4),
            'next_prediction' => round($nextPrediction),
        ];
    }

    /**
     * Hitung Moving Average sederhana untuk satu seri data.
     *
     * @param  array<int, float|int>  $y
     * @param  int                    $window
     * @return array{
     *     y_pred: array<int, float>,
     *     mape: float,
     *     next_prediction: float
     * }
     */
    public static function hitungMovingAverage(array $y, int $window = self::MOVING_AVERAGE_WINDOW): array
    {
        $n = count($y);

        if ($n === 0 || $window <= 0) {
            return [
                'y_pred' => [],
                'mape' => 0.0,
                'next_prediction' => 0.0,
            ];
        }

        // Prediksi untuk titik-titik historis (mulai dari index window-1)
        $yPred = array_fill(0, $n, 0.0);
        for ($i = $window - 1; $i < $n; $i++) {
            $sum = 0.0;
            for ($j = $i - $window + 1; $j <= $i; $j++) {
                $sum += (float) $y[$j];
            }
            $avg = $sum / $window;
            $yPred[$i] = $avg;
        }

        // MAPE hanya dihitung untuk titik yang punya prediksi (i >= window-1)
        $mapeSum = 0.0;
        $valid   = 0;
        for ($i = $window - 1; $i < $n; $i++) {
            $actual = (float) $y[$i];
            if ($actual != 0.0) {
                $mapeSum += abs(($actual - $yPred[$i]) / $actual);
                $valid++;
            }
        }
        $mape = $valid > 0 ? ($mapeSum / $valid) * 100.0 : 0.0;

        // Prediksi tahun berikutnya: rata-rata window terakhir
        if ($n >= $window) {
            $sumLast = 0.0;
            for ($i = $n - $window; $i < $n; $i++) {
                $sumLast += (float) $y[$i];
            }
            $nextPrediction = $sumLast / $window;
        } else {
            // Jika data lebih sedikit dari window, pakai rata-rata semua
            $nextPrediction = array_sum($y) / $n;
        }

        return [
            'y_pred' => $yPred,
            'mape' => round($mape, 4),
            'next_prediction' => round($nextPrediction),
        ];
    }
}

