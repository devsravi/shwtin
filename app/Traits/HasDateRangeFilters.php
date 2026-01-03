<?php

namespace App\Traits;

use App\Services\DateRangeService;

trait HasDateRangeFilters
{
    protected ?DateRangeService $dateRangeService = null;

    /**
     * Get the DateRangeService instance
     *
     * @return DateRangeService
     */
    protected function dateRangeService(): DateRangeService
    {
        if (!$this->dateRangeService) {
            $this->dateRangeService = app(DateRangeService::class);
        }

        return $this->dateRangeService;
    }

    /**
     * Calculate date range based on filters
     *
     * @param array|null $filters
     * @return array
     */
    protected function calculateDateRange(?array $filters = null): array
    {
        $filters = $filters ?? $this->getFilters();
        return $this->dateRangeService()->calculateDateRange($filters);
    }

    /**
     * Calculate previous date range for comparison
     *
     * @param array|null $filters
     * @return array
     */
    protected function calculatePreviousDateRange(?array $filters = null): array
    {
        $filters = $filters ?? $this->getFilters();
        return $this->dateRangeService()->calculatePreviousDateRange($filters);
    }

    /**
     * Get start date
     *
     * @param array|null $filters
     * @return string
     */
    protected function getStartDate(?array $filters = null): string
    {
        $filters = $filters ?? $this->getFilters();
        return $this->dateRangeService()->getStartDate($filters);
    }

    /**
     * Get end date
     *
     * @param array|null $filters
     * @return string
     */
    protected function getEndDate(?array $filters = null): string
    {
        $filters = $filters ?? $this->getFilters();
        return $this->dateRangeService()->getEndDate($filters);
    }

    /**
     * Get previous start date
     *
     * @param array|null $filters
     * @return string
     */
    protected function getPreviousStartDate(?array $filters = null): string
    {
        $filters = $filters ?? $this->getFilters();
        return $this->dateRangeService()->getPreviousStartDate($filters);
    }

    /**
     * Get previous end date
     *
     * @param array|null $filters
     * @return string
     */
    protected function getPreviousEndDate(?array $filters = null): string
    {
        $filters = $filters ?? $this->getFilters();
        return $this->dateRangeService()->getPreviousEndDate($filters);
    }

    /**
     * Get all date ranges (current and previous)
     *
     * @param array|null $filters
     * @return array
     */
    protected function getAllDateRanges(?array $filters = null): array
    {
        $filters = $filters ?? $this->getFilters();
        return $this->dateRangeService()->getAllDateRanges($filters);
    }

    /**
     * Get filters (override this method in your class if needed)
     *
     * @return array
     */
    protected function getFilters(): array
    {
        return $this->filters ?? [];
    }


    /**
     * Get human-readable date range description
     *
     * @param array|null $filters
     * @return string
     */
    protected function getHumanReadableDateRange(?array $filters = null): string
    {
        $filters = $filters ?? $this->getFilters();
        return $this->dateRangeService()->getHumanReadableDateRange($filters);
    }

    /**
     * Get short date range description
     *
     * @param array|null $filters
     * @return string
     */
    protected function getShortDateRange(?array $filters = null): string
    {
        $filters = $filters ?? $this->getFilters();
        return $this->dateRangeService()->getShortDateRange($filters);
    }

    /**
     * Get detailed date range with actual dates
     *
     * @param array|null $filters
     * @return string
     */
    protected function getDetailedDateRange(?array $filters = null): string
    {
        $filters = $filters ?? $this->getFilters();
        return $this->dateRangeService()->getDetailedDateRange($filters);
    }

    /**
     * Get comparison period description
     *
     * @param array|null $filters
     * @return string
     */
    protected function getComparisonPeriodDescription(?array $filters = null): string
    {
        $filters = $filters ?? $this->getFilters();
        return $this->dateRangeService()->getComparisonPeriodDescription($filters);
    }
}
