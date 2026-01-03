<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Url;
use App\Models\Visit;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShortURLVisited
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The short URL that was visited.
     */
    public Url $shortURL;

    /**
     * Details of the visitor that visited the short URL.
     */
    public Visit $shortURLVisit;

    public function __construct(Url $shortURL, Visit $shortURLVisit)
    {
        $this->shortURL = $shortURL;
        $this->shortURLVisit = $shortURLVisit;
    }
}
