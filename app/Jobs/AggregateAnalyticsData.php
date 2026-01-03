<?php

// app/Jobs/AggregateAnalyticsDataJob.php

namespace App\Jobs;

use App\Models\User;
use App\Models\Visit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class AggregateAnalyticsData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public $date = null,
        public $userId = null
    ) {
        $this->date = $date ?: now()->format('Y-m-d');
    }

    public function handle(): void
    {
        $users = $this->userId ? [$this->userId] : User::pluck('id');

        foreach ($users as $userId) {
            $this->aggregateUserData($userId, $this->date);
        }
    }

    private function aggregateUserData($userId, $date)
    {
        // Get total visits for the user and date
        $totalVisits = Visit::where('user_id', $userId)
            ->count();

        if ($totalVisits === 0) {
            return;
        }

        // Aggregate by different types
        $this->aggregateByType($userId, $date, $totalVisits, 'country', 'country');
        $this->aggregateByType($userId, $date, $totalVisits, 'city', 'city', 'country');
        $this->aggregateByType($userId, $date, $totalVisits, 'browser', 'browser');
        $this->aggregateByType($userId, $date, $totalVisits, 'os', 'operating_system');
        $this->aggregateByType($userId, $date, $totalVisits, 'device', 'device_type');
    }

    private function aggregateByType($userId, $date, $totalVisits, $type, $column, $parentColumn = null)
    {
        $query = Visit::where('user_id', $userId)
            ->whereDate('visited_at', $date)
            ->whereNull('deleted_at')
            ->whereNotNull($column)
            ->where($column, '!=', '');

        // Select columns based on type
        $selectColumns = [
            DB::raw("'{$userId}' as user_id"),
            DB::raw("'{$date}' as date"),
            DB::raw("'{$type}' as type"),
            "{$column} as name",
            DB::raw('COUNT(*) as total_visits'),
            DB::raw('ROUND((COUNT(*) * 100.0 / ?), 2) as share_percentage'),
        ];

        if ($parentColumn) {
            $selectColumns[] = "{$parentColumn} as parent";
        } else {
            $selectColumns[] = DB::raw('NULL as parent');
        }

        $results = $query
            ->select($selectColumns)
            ->addBinding([$totalVisits], 'select')
            ->groupBy($column, $parentColumn ?: DB::raw('NULL'))
            ->get();

        // Prepare data for bulk insert/update
        $dataToInsert = [];
        foreach ($results as $result) {
            $dataToInsert[] = [
                'user_id' => $userId,
                'date' => $date,
                'type' => $type,
                'name' => $result->name,
                'parent' => $result->parent,
                'total_visits' => $result->total_visits,
                'share_percentage' => $result->share_percentage,
                'metadata' => $this->getMetadata($type, $result),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Bulk upsert
        if (! empty($dataToInsert)) {
        }
    }

    private function getMetadata($type, $result)
    {
        switch ($type) {
            case 'city':
                return ['country' => $result->parent];
            case 'browser':
                return ['browser_version' => $result->browser_version ?? null];
            case 'os':
                return [
                    'os_alias' => $result->operating_system_alias ?? null,
                    'os_version' => $result->operating_system_version ?? null,
                ];
            case 'device':
                return [
                    'device_manufacturer' => $result->device_manufacturer ?? null,
                    'device_model' => $result->device_model ?? null,
                ];
            default:
                return null;
        }
    }
}
