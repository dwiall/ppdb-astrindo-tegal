<?php

namespace App\Services;

class PrediksiService
{
    public const MOVING_AVERAGE_WINDOW = 3;

    /**
     * Hitung regresi linear untuk satu seri data.
     *
     * @param  array<int, float|int>  $x  (tidak dipakai, tetap untuk kompatibilitas)
     * @param  array<int, float|int>  $y
     */
    public static function hitungRegresiLinear(array $x, array $y): array
    {
        $n = count($y);

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

        // 🔥 FIX UTAMA: pakai INDEX (1,2,3,...)
        $xIndex = range(1, $n);

        $sumX  = array_sum($xIndex);
        $sumY  = array_sum($y);
        $sumXY = 0.0;
        $sumX2 = 0.0;

        for ($i = 0; $i < $n; $i++) {
            $xi = (float) $xIndex[$i];
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

        // 🔥 Prediksi historis pakai index
        $yPred = [];
        for ($i = 0; $i < $n; $i++) {
            $yPred[] = $a + ($b * $xIndex[$i]);
        }

        // R²
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
        $valid = 0;

        for ($i = 0; $i < $n; $i++) {
            if ($y[$i] != 0) {
                $mapeSum += abs(($y[$i] - $yPred[$i]) / $y[$i]);
                $valid++;
            }
        }

        $mape = $valid > 0 ? ($mapeSum / $valid) * 100 : 0.0;

        // 🔥 Prediksi tahun berikutnya pakai index (n+1)
        $nextX = $n + 1;
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
     * Moving Average (TIDAK DIUBAH)
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

        $yPred = array_fill(0, $n, 0.0);

        for ($i = $window - 1; $i < $n; $i++) {
            $sum = 0.0;
            for ($j = $i - $window + 1; $j <= $i; $j++) {
                $sum += (float) $y[$j];
            }
            $yPred[$i] = $sum / $window;
        }

        // MAPE
        $mapeSum = 0.0;
        $valid = 0;

        for ($i = $window - 1; $i < $n; $i++) {
            if ($y[$i] != 0) {
                $mapeSum += abs(($y[$i] - $yPred[$i]) / $y[$i]);
                $valid++;
            }
        }

        $mape = $valid > 0 ? ($mapeSum / $valid) * 100 : 0.0;

        // Prediksi berikutnya
        $nextPrediction = array_sum(array_slice($y, -$window)) / $window;

        return [
            'y_pred' => $yPred,
            'mape' => round($mape, 4),
            'next_prediction' => round($nextPrediction),
        ];
    }
}