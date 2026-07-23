<?php

namespace App\Services\Benchmark;

use InvalidArgumentException;

/**
 * Descriptive Statistics Calculator for OTP Benchmark Latency Data.
 * 
 * Computes Sample Mean, Sample Standard Deviation, Coefficient of Variation (CV%),
 * and Five-Number Summary (Min, Q1, Median, Q3, Max) using Linear Interpolation
 * (R-7 / Excel PERCENTILE.INC method) for quartiles.
 */
class StatisticsCalculator
{
    /**
     * Calculate descriptive statistics for an array of latency numbers.
     *
     * @param array<int|float> $data Array of latency measurements in milliseconds
     * @return array{
     *     samples: int,
     *     mean: float,
     *     std_dev: float,
     *     cv_percent: float,
     *     min: float,
     *     q1: float,
     *     median: float,
     *     q3: float,
     *     max: float
     * }
     * @throws InvalidArgumentException If data array is empty
     */
    public function calculate(array $data): array
    {
        $n = count($data);
        if ($n === 0) {
            throw new InvalidArgumentException('Cannot calculate statistics for an empty data array.');
        }

        // Convert to floats and sort ascending
        $values = array_map('floatval', array_values($data));
        sort($values, SORT_NUMERIC);

        // 1. Min & Max
        $min = $values[0];
        $max = $values[$n - 1];

        // 2. Sample Mean
        $sum = array_sum($values);
        $mean = $sum / $n;

        // 3. Sample Standard Deviation (n - 1 denominator)
        $stdDev = 0.0;
        if ($n > 1) {
            $varianceSum = 0.0;
            foreach ($values as $val) {
                $varianceSum += ($val - $mean) ** 2;
            }
            $stdDev = sqrt($varianceSum / ($n - 1));
        }

        // 4. Coefficient of Variation (CV %)
        $cvPercent = ($mean > 0.0) ? ($stdDev / $mean) * 100.0 : 0.0;

        // 5. Quartiles using Linear Interpolation Method (R-7 / Excel PERCENTILE.INC)
        $q1 = $this->calculatePercentile($values, 0.25);
        $median = $this->calculatePercentile($values, 0.50);
        $q3 = $this->calculatePercentile($values, 0.75);

        return [
            'samples' => $n,
            'mean' => round($mean, 6),
            'std_dev' => round($stdDev, 6),
            'cv_percent' => round($cvPercent, 4),
            'min' => round($min, 6),
            'q1' => round($q1, 6),
            'median' => round($median, 6),
            'q3' => round($q3, 6),
            'max' => round($max, 6),
        ];
    }

    /**
     * Calculate percentile using Linear Interpolation Method (R-7 / Excel PERCENTILE.INC).
     *
     * Formula:
     * k = (n - 1) * p
     * index = floor(k)
     * fraction = k - index
     * value = sorted[index] + fraction * (sorted[index + 1] - sorted[index])
     *
     * @param float[] $sortedValues Sorted array of numeric values
     * @param float $p Percentile value between 0.0 and 1.0 (e.g. 0.25 for Q1)
     * @return float
     */
    private function calculatePercentile(array $sortedValues, float $p): float
    {
        $n = count($sortedValues);
        if ($n === 1) {
            return $sortedValues[0];
        }

        $k = ($n - 1) * $p;
        $index = (int) floor($k);
        $fraction = $k - $index;

        if ($index >= $n - 1) {
            return $sortedValues[$n - 1];
        }

        return $sortedValues[$index] + $fraction * ($sortedValues[$index + 1] - $sortedValues[$index]);
    }
}
