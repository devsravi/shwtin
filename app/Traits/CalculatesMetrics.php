<?php

namespace App\Traits;

use Illuminate\Support\Number;

trait CalculatesMetrics
{
    /**
     * Calculates the percentage change between a current and a previous value.
     *
     * The result is formatted as a string with one decimal place and a percent sign.
     * A plus sign (+) is added for positive changes.
     * 
     * Example outputs: "+12.5%", "-8.3%", "0%"
     *
     * @param float|int $current  The current value.
     * @param float|int $previous The previous value.
     * 
     * @return string The formatted percentage change (e.g. "+15.2%").
     */
    protected function calculatePercentageChange($current, $previous): string
    {
        if ($previous == 0) {
            return $current > 0 ? '+100%' : '0%';
        }

        $percentage = (($current - $previous) / $previous) * 100;
        return ($percentage >= 0 ? '+' : '') . number_format($percentage, 1) . '%';
    }

    /**
     * Calculate growth rate (raw percentage without formatting)
     *
     * Returns the raw growth rate as a float. Useful for comparisons or further calculations.
     * 
     * Example outputs: 12.5, -8.3, 0.0
     *
     * @param float|int $current  The current value.
     * @param float|int $previous The previous value.
     * 
     * @return float The growth rate as a percentage (e.g. 15.2 for 15.2% growth).
     */
    protected function calculateGrowthRate($current, $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round(($current - $previous / $previous) * 100, 2);
    }

    /**
     * Calculate the absolute difference between two values
     *
     * @param float|int $current  The current value.
     * @param float|int $previous The previous value.
     * 
     * @return float|int The absolute difference.
     */
    protected function calculateAbsoluteDifference($current, $previous): float|int
    {
        return $current - $previous;
    }

    /**
     * Calculate average (mean) of an array of values
     *
     * @param array $values Array of numeric values.
     * 
     * @return float The average value.
     */
    protected function calculateAverage(array $values): float
    {
        if (empty($values)) {
            return 0.0;
        }

        return round(array_sum($values) / count($values), 2);
    }

    /**
     * Calculate conversion rate
     *
     * @param float|int $conversions Total conversions/clicks.
     * @param float|int $total       Total impressions/views.
     * 
     * @return float Conversion rate as a percentage.
     */
    protected function calculateConversionRate($conversions, $total): float
    {
        if ($total === 0) {
            return 0.0;
        }

        return round($conversions / $total * 100, 2);
    }

    /**
     * Calculate click-through rate (CTR)
     *
     * @param float|int $clicks Total clicks.
     * @param float|int $views  Total views/impressions.
     * 
     * @return string Formatted CTR with percentage sign (e.g. "5.2%").
     */
    protected function calculateCTR($clicks, $views): string
    {
        if ($views === 0) {
            return '0%';
        }

        $ctr = $clicks / $views * 100;
        return number_format($ctr, 2) . '%';
    }

    /**
     * Calculate sum of array values
     *
     * @param array $values Array of numeric values.
     * 
     * @return float|int The sum of all values.
     */
    protected function calculateSum(array $values): float|int
    {
        return array_sum($values);
    }

    /**
     * Calculate percentage of total
     *
     * @param float|int $part  The part value.
     * @param float|int $total The total value.
     * 
     * @return float Percentage of total.
     */
    protected function calculatePercentageOfTotal($part, $total): float
    {
        if ($total === 0) {
            return 0.0;
        }

        return round($part / $total * 100, 2);
    }

    /**
     * Format number with abbreviations (K, M, B)
     *
     * @param float|int $number The number to format.
     * 
     * @return string Formatted number (e.g. "1.5K", "2.3M").
     */
    protected function formatNumberShort($number): string
    {
        // Using Laravel's Number class for abbreviation
        return (string) Number::abbreviate($number);
    }

    /**
     * Calculate trend direction
     *
     * @param float|int $current  The current value.
     * @param float|int $previous The previous value.
     * 
     * @return string 'up', 'down', or 'neutral'.
     */
    protected function calculateTrend($current, $previous): string
    {
        if ($current > $previous) {
            return 'up';
        }
        if ($current < $previous) {
            return 'down';
        }

        return 'neutral';
    }

    /**
     * Calculate trend direction
     *
     * @param float|int $current  The current value.
     * @param float|int $previous The previous value.
     * 
     * @return string 'success', 'danger', or 'info'.
     */
    protected function calculateTrendColor($current, $previous): string
    {
        if ($current > $previous) {
            return 'success';
        }
        if ($current < $previous) {
            return 'danger';
        }
        return 'info';
    }


    /**
     * Calculate variance
     *
     * @param array $values Array of numeric values.
     * 
     * @return float The variance.
     */
    protected function calculateVariance(array $values): float
    {
        if (empty($values)) {
            return 0.0;
        }

        $mean = $this->calculateAverage($values);
        $variance = array_sum(array_map(function ($value) use ($mean) {
            return pow($value - $mean, 2);
        }, $values)) / count($values);

        return round($variance, 2);
    }

    /**
     * Calculate standard deviation
     *
     * @param array $values Array of numeric values.
     * 
     * @return float The standard deviation.
     */
    protected function calculateStandardDeviation(array $values): float
    {
        return round(sqrt($this->calculateVariance($values)), 2);
    }

    /**
     * Calculate median
     *
     * @param array $values Array of numeric values.
     * 
     * @return float The median value.
     */
    protected function calculateMedian(array $values): float
    {
        if (empty($values)) {
            return 0.0;
        }

        sort($values);
        $count = count($values);
        $middle = floor($count / 2);

        if ($count % 2 === 0) {
            return ($values[$middle - 1] + $values[$middle]) / 2;
        }

        return $values[$middle];
    }

    /**
     * Calculate compound annual growth rate (CAGR)
     *
     * @param float|int $endValue   The ending value.
     * @param float|int $startValue The starting value.
     * @param int       $years      Number of years.
     * 
     * @return float CAGR as a percentage.
     */
    protected function calculateCAGR($endValue, $startValue, int $years): float
    {
        if ($startValue === 0 || $years === 0) {
            return 0.0;
        }

        $growthRate = pow($endValue / $startValue, 1 / $years);
        $cagr = ($growthRate - 1) * 100;

        return round($cagr, 2);
    }

    /**
     * Check if value is increasing
     *
     * @param float|int $current  The current value.
     * @param float|int $previous The previous value.
     * 
     * @return bool True if increasing, false otherwise.
     */
    protected function isIncreasing($current, $previous): bool
    {
        return $current > $previous;
    }

    /**
     * Check if value is decreasing
     *
     * @param float|int $current  The current value.
     * @param float|int $previous The previous value.
     * 
     * @return bool True if decreasing, false otherwise.
     */
    protected function isDecreasing($current, $previous): bool
    {
        return $current < $previous;
    }

    /**
     * Format currency value
     *
     * @param float|int $amount       The amount to format.
     * @param string    $currency     The currency symbol (default: '$').
     * @param int       $decimals     Number of decimal places (default: 2).
     * 
     * @return string Formatted currency (e.g. "$1,234.56").
     */
    protected function formatCurrency($amount, string $currency = '$', int $decimals = 2): string
    {
        return $currency . number_format($amount, $decimals);
    }


    /**
     * Calculate average rate (division with handling for zero)
     *
     * @param float|int $total    The total value (numerator).
     * @param float|int $count    The count value (denominator).
     * @param int       $decimals Number of decimal places (default: 1).
     * 
     * @return float The average rate.
     */
    protected function calculateAverageRate($total, $count, int $decimals = 1): float
    {
        if ($count === 0) {
            return 0.0;
        }

        return round($total / $count, $decimals);
    }


    /**
     * Calculate average visits per URL
     *
     * @param int $totalVisits Total number of visits.
     * @param int $uniqueUrls  Number of unique URLs.
     * 
     * @return float Average visits per URL.
     */
    protected function calculateAvgVisitsPerUrl(int $totalVisits, int $uniqueUrls): float
    {
        return $this->calculateAverageRate($totalVisits, $uniqueUrls, 1);
    }

    /**
     * Calculate average visits per visitor
     *
     * @param int $totalVisits     Total number of visits.
     * @param int $uniqueVisitors  Number of unique visitors.
     * 
     * @return float Average visits per visitor.
     */
    protected function calculateAvgVisitsPerVisitor(int $totalVisits, int $uniqueVisitors): float
    {
        return $this->calculateAverageRate($totalVisits, $uniqueVisitors, 1);
    }
}
