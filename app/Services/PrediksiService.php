<?php

namespace App\Services;

class PrediksiService
{
    public const MOVING_AVERAGE_WINDOW = 3;

    /**
     * REGRESI LINEAR (TIDAK DIUBAH)
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

        $yPred = [];
        for ($i = 0; $i < $n; $i++) {
            $yPred[] = $a + ($b * $xIndex[$i]);
        }

        // R2
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

        // Prediksi tahun berikutnya
        $nextPrediction = $a + ($b * ($n + 1));

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
     * ✅ MOVING AVERAGE FINAL (1 TITIK SESUAI SKRIPSI)
     */
    public static function hitungMovingAverage(array $y, int $window = self::MOVING_AVERAGE_WINDOW): array
    {
        $n = count($y);

        // Minimal 4 data untuk MA-3
        if ($n < 4) {
            return [
                'y_pred' => [],
                'mape' => 0.0,
                'next_prediction' => 0.0,
                'ma_2025' => 0.0,
                'error_2025' => 0.0,
            ];
        }

        // Data
        $y1 = (float) $y[0];
        $y2 = (float) $y[1];
        $y3 = (float) $y[2];
        $y4 = (float) $y[3];

        // ✅ MA 2025 (pakai 3 tahun sebelumnya)
        $ma2025 = ($y1 + $y2 + $y3) / 3;

        // ✅ Error
        $error = abs($y4 - $ma2025);
        $errorPersen = $y4 != 0 ? ($error / $y4) * 100 : 0;

        // ✅ MAPE (1 data)
        $mape = $errorPersen;

        // ✅ Prediksi 2026
        $nextPrediction = ($y2 + $y3 + $y4) / 3;

        // Untuk chart (hanya 2025 yang ada nilai)
        $yPred = array_fill(0, $n, null);
        $yPred[$n - 1] = $ma2025;

        return [
            'y_pred' => $yPred,
            'mape' => round($mape, 4),
            'next_prediction' => round($nextPrediction),
            'ma_2025' => round($ma2025, 4),
            'error_2025' => round($errorPersen, 4),
        ];
    }
}