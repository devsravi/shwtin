<?php

namespace App\Jobs;

use App\Models\Scopes\OwnerScope;
use App\Models\Url;
use App\Models\Visit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AssignGuestShortUrlsToUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $userId,
        public array $shortUrlKeys
    ) {
        $this->onQueue('default');
    }

    public function handle(): void
    {
        if (empty($this->shortUrlKeys)) {
            return;
        }

        // Fetch URL IDs once
        $urlIds = Url::withoutGlobalScope(OwnerScope::class)->whereIn('url_key', $this->shortUrlKeys)
            ->where('user_id', null)
            ->pluck('id')
            ->toArray();
        if (empty($urlIds)) {
            return;
        }

        // Assign URLs to user
        Url::withoutGlobalScope(OwnerScope::class)->whereIn('id', $urlIds)
            ->where('user_id', null)
            ->update(['user_id' => $this->userId]);

        // Assign visits to user
        Visit::withoutGlobalScope(OwnerScope::class)->whereIn('url_id', $urlIds)
            ->where('user_id', null)
            ->update(['user_id' => $this->userId]);
    }
}
