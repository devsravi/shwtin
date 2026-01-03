<?php

namespace App\Services;

use Carbon\Carbon;

class DateRangeService
{
    /**
     * Calculate date range based on filters
     *
     * @return array ['startDate' => string, 'endDate' => string]
     */
    public function calculateDateRange(array $filters): array
    {
        $dateRange = $filters['dateRange'] ?? 'today';

        switch ($dateRange) {
            case 'today':
                $startDate = Carbon::today()->startOfDay()->toDateTimeString();
                $endDate = Carbon::today()->endOfDay()->toDateTimeString();
                break;

            case 'yesterday':
                $startDate = Carbon::yesterday()->startOfDay()->toDateTimeString();
                $endDate = Carbon::yesterday()->endOfDay()->toDateTimeString();
                break;

            case 'this_week':
                $startDate = Carbon::now()->startOfWeek()->startOfDay()->toDateTimeString();
                $endDate = Carbon::now()->endOfWeek()->endOfDay()->toDateTimeString();
                break;

            case 'last_week':
                $startDate = Carbon::now()->subWeek()->startOfWeek()->startOfDay()->toDateTimeString();
                $endDate = Carbon::now()->subWeek()->endOfWeek()->endOfDay()->toDateTimeString();
                break;

            case 'this_month':
                $startDate = Carbon::now()->startOfMonth()->startOfDay()->toDateTimeString();
                $endDate = Carbon::now()->endOfMonth()->endOfDay()->toDateTimeString();
                break;

            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth()->startOfDay()->toDateTimeString();
                $endDate = Carbon::now()->subMonth()->endOfMonth()->endOfDay()->toDateTimeString();
                break;

            case 'this_year':
                $startDate = Carbon::now()->startOfYear()->startOfDay()->toDateTimeString();
                $endDate = Carbon::now()->endOfYear()->endOfDay()->toDateTimeString();
                break;

            case 'last_year':
                $startDate = Carbon::now()->subYear()->startOfYear()->startOfDay()->toDateTimeString();
                $endDate = Carbon::now()->subYear()->endOfYear()->endOfDay()->toDateTimeString();
                break;

            case 'last_7_days':
                $startDate = Carbon::now()->subDays(6)->startOfDay()->toDateTimeString();
                $endDate = Carbon::now()->endOfDay()->toDateTimeString();
                break;

            case 'last_30_days':
                $startDate = Carbon::now()->subDays(29)->startOfDay()->toDateTimeString();
                $endDate = Carbon::now()->endOfDay()->toDateTimeString();
                break;

            case 'last_90_days':
                $startDate = Carbon::now()->subDays(89)->startOfDay()->toDateTimeString();
                $endDate = Carbon::now()->endOfDay()->toDateTimeString();
                break;

            case 'custom':
                $startDate = isset($filters['startDate'])
                    ? Carbon::parse($filters['startDate'])->startOfDay()->toDateTimeString()
                    : Carbon::today()->startOfDay()->toDateTimeString();
                $endDate = isset($filters['endDate'])
                    ? Carbon::parse($filters['endDate'])->endOfDay()->toDateTimeString()
                    : Carbon::today()->endOfDay()->toDateTimeString();
                break;

            default:
                $startDate = Carbon::today()->startOfDay()->toDateTimeString();
                $endDate = Carbon::today()->endOfDay()->toDateTimeString();
                break;
        }

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }

    /**
     * Calculate previous date range for comparison
     *
     * @return array ['previousStartDate' => string, 'previousEndDate' => string]
     */
    public function calculatePreviousDateRange(array $filters): array
    {
        $dateRange = $filters['dateRange'] ?? 'today';

        switch ($dateRange) {
            case 'today':
                $startDate = Carbon::yesterday()->startOfDay()->toDateTimeString();
                $endDate = Carbon::yesterday()->endOfDay()->toDateTimeString();
                break;

            case 'yesterday':
                $startDate = Carbon::yesterday()->subDay()->startOfDay()->toDateTimeString();
                $endDate = Carbon::yesterday()->subDay()->endOfDay()->toDateTimeString();
                break;

            case 'this_week':
                $startDate = Carbon::now()->subWeek()->startOfWeek()->startOfDay()->toDateTimeString();
                $endDate = Carbon::now()->subWeek()->endOfWeek()->endOfDay()->toDateTimeString();
                break;

            case 'last_week':
                $startDate = Carbon::now()->subWeeks(2)->startOfWeek()->startOfDay()->toDateTimeString();
                $endDate = Carbon::now()->subWeeks(2)->endOfWeek()->endOfDay()->toDateTimeString();
                break;

            case 'this_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth()->startOfDay()->toDateTimeString();
                $endDate = Carbon::now()->subMonth()->endOfMonth()->endOfDay()->toDateTimeString();
                break;

            case 'last_month':
                $startDate = Carbon::now()->subMonths(2)->startOfMonth()->startOfDay()->toDateTimeString();
                $endDate = Carbon::now()->subMonths(2)->endOfMonth()->endOfDay()->toDateTimeString();
                break;

            case 'this_year':
                $startDate = Carbon::now()->subYear()->startOfYear()->startOfDay()->toDateTimeString();
                $endDate = Carbon::now()->subYear()->endOfYear()->endOfDay()->toDateTimeString();
                break;

            case 'last_year':
                $startDate = Carbon::now()->subYears(2)->startOfYear()->startOfDay()->toDateTimeString();
                $endDate = Carbon::now()->subYears(2)->endOfYear()->endOfDay()->toDateTimeString();
                break;

            case 'last_7_days':
                $startDate = Carbon::now()->subDays(13)->startOfDay()->toDateTimeString();
                $endDate = Carbon::now()->subDays(7)->endOfDay()->toDateTimeString();
                break;

            case 'last_30_days':
                $startDate = Carbon::now()->subDays(59)->startOfDay()->toDateTimeString();
                $endDate = Carbon::now()->subDays(30)->endOfDay()->toDateTimeString();
                break;

            case 'last_90_days':
                $startDate = Carbon::now()->subDays(179)->startOfDay()->toDateTimeString();
                $endDate = Carbon::now()->subDays(90)->endOfDay()->toDateTimeString();
                break;

            case 'custom':
                if (isset($filters['startDate']) && isset($filters['endDate'])) {
                    $start = Carbon::parse($filters['startDate']);
                    $end = Carbon::parse($filters['endDate']);
                    $daysDiff = $start->diffInDays($end);

                    $startDate = $start->copy()->subDays($daysDiff + 1)->startOfDay()->toDateTimeString();
                    $endDate = $start->copy()->subDay()->endOfDay()->toDateTimeString();
                } else {
                    $startDate = Carbon::yesterday()->startOfDay()->toDateTimeString();
                    $endDate = Carbon::yesterday()->endOfDay()->toDateTimeString();
                }
                break;

            default:
                $startDate = Carbon::yesterday()->startOfDay()->toDateTimeString();
                $endDate = Carbon::yesterday()->endOfDay()->toDateTimeString();
                break;
        }

        return [
            'previousStartDate' => $startDate,
            'previousEndDate' => $endDate,
        ];
    }

    /**
     * Get only start date
     */
    public function getStartDate(array $filters): string
    {
        $dates = $this->calculateDateRange($filters);

        return $dates['startDate'];
    }

    /**
     * Get only end date
     */
    public function getEndDate(array $filters): string
    {
        $dates = $this->calculateDateRange($filters);

        return $dates['endDate'];
    }

    /**
     * Get only previous start date
     */
    public function getPreviousStartDate(array $filters): string
    {
        $dates = $this->calculatePreviousDateRange($filters);

        return $dates['previousStartDate'];
    }

    /**
     * Get only previous end date
     */
    public function getPreviousEndDate(array $filters): string
    {
        $dates = $this->calculatePreviousDateRange($filters);

        return $dates['previousEndDate'];
    }

    /**
     * Get all date ranges (current and previous)
     */
    public function getAllDateRanges(array $filters): array
    {
        return array_merge(
            $this->calculateDateRange($filters),
            $this->calculatePreviousDateRange($filters)
        );
    }

    /**
     * Get human-readable date range description
     */
    public function getHumanReadableDateRange(array $filters): string
    {
        $dateRange = $filters['dateRange'] ?? 'today';

        return match ($dateRange) {
            'today' => 'Today',
            'yesterday' => 'Yesterday',
            'this_week' => 'This Week',
            'last_week' => 'Last Week',
            'this_month' => 'This Month',
            'last_month' => 'Last Month',
            'this_year' => 'This Year',
            'last_year' => 'Last Year',
            'last_7_days' => 'Last 7 Days',
            'last_30_days' => 'Last 30 Days',
            'last_90_days' => 'Last 90 Days',
            'custom' => $this->formatCustomDateRange($filters),
            default => 'Today',
        };
    }

    /**
     * Format custom date range as human-readable string
     */
    protected function formatCustomDateRange(array $filters): string
    {
        if (! isset($filters['startDate']) || ! isset($filters['endDate'])) {
            return 'Custom Range';
        }

        $start = Carbon::parse($filters['startDate']);
        $end = Carbon::parse($filters['endDate']);

        // Same day
        if ($start->isSameDay($end)) {
            return $start->format('M d, Y');
        }

        // Same month
        if ($start->isSameMonth($end)) {
            return $start->format('M d').' - '.$end->format('d, Y');
        }

        // Same year
        if ($start->isSameYear($end)) {
            return $start->format('M d').' - '.$end->format('M d, Y');
        }

        // Different years
        return $start->format('M d, Y').' - '.$end->format('M d, Y');
    }

    /**
     * Get short human-readable date range (for compact display)
     */
    public function getShortDateRange(array $filters): string
    {
        $dateRange = $filters['dateRange'] ?? 'today';

        return match ($dateRange) {
            'today' => 'Today',
            'yesterday' => 'Yesterday',
            'this_week' => 'This Week',
            'last_week' => 'Last Week',
            'this_month' => 'This Month',
            'last_month' => 'Last Month',
            'this_year' => 'This Year',
            'last_year' => 'Last Year',
            'last_7_days' => 'Last 7d',
            'last_30_days' => 'Last 30d',
            'last_90_days' => 'Last 90d',
            'custom' => $this->formatCustomDateRangeShort($filters),
            default => 'Today',
        };
    }

    /**
     * Format custom date range as short string
     */
    protected function formatCustomDateRangeShort(array $filters): string
    {
        if (! isset($filters['startDate']) || ! isset($filters['endDate'])) {
            return 'Custom';
        }

        $start = Carbon::parse($filters['startDate']);
        $end = Carbon::parse($filters['endDate']);

        return $start->format('M d').' - '.$end->format('M d');
    }

    /**
     * Get detailed date range with actual dates
     */
    public function getDetailedDateRange(array $filters): string
    {
        $dates = $this->calculateDateRange($filters);
        $start = Carbon::parse($dates['startDate']);
        $end = Carbon::parse($dates['endDate']);
        $label = $this->getHumanReadableDateRange($filters);

        return "{$label} ({$start->format('M d, Y')} - {$end->format('M d, Y')})";
    }

    /**
     * Get comparison period description
     */
    public function getComparisonPeriodDescription(array $filters): string
    {
        $dateRange = $filters['dateRange'] ?? 'today';

        return match ($dateRange) {
            'today' => 'vs Yesterday',
            'yesterday' => 'vs Previous Day',
            'this_week' => 'vs Last Week',
            'last_week' => 'vs 2 Weeks Ago',
            'this_month' => 'vs Last Month',
            'last_month' => 'vs 2 Months Ago',
            'this_year' => 'vs Last Year',
            'last_year' => 'vs 2 Years Ago',
            'last_7_days' => 'vs Previous 7 Days',
            'last_30_days' => 'vs Previous 30 Days',
            'last_90_days' => 'vs Previous 90 Days',
            'custom' => 'vs Previous Period',
            default => 'vs Previous Period',
        };
    }
}
