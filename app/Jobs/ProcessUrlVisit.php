<?php

namespace App\Jobs;

use App\Classes\Resolver;
use App\Models\Url;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessUrlVisit implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $ip;
    public $userAgent;
    public $referer;
    public $headers;
    public $url;

    /**
     * Create a new job instance.
     */
    public function __construct(
        string $ip,
        ?string $userAgent,
        ?string $referer,
        array $headers,
        Url $url
    ) {
        $this->ip = $ip;
        $this->userAgent = $userAgent;
        $this->referer = $referer;
        $this->headers = $headers;
        $this->url = $url;
    }

    /**
     * Execute the job.
     */
    public function handle(Resolver $resolver): void
    {
        try {
            $resolver->handleVisit(
                ip: $this->ip,
                userAgent: $this->userAgent,
                referer: $this->referer,
                headers: $this->headers,
                shortURL: $this->url
            );
        } catch (Throwable $th) {
            Log::alert("Failed to process URL visit for {$this->url->key}", [
                'error' => $th->getMessage(),
                'ip' => $this->ip,
                'trace' => $th->getTraceAsString()
            ]);
            throw $th;
        }
    }
}
