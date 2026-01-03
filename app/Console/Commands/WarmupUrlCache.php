<?php

namespace App\Console\Commands;

use App\Models\Url;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class WarmupUrlCache extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'cache:warmup-urls
                            {--fresh : Clear existing cache before warming up}';

    /**
     * The console command description.
     */
    protected $description = 'Cache all active URLs for faster redirects';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->option('fresh')) {
            $this->info('Clearing existing URL cache...');
            // Clear all URL caches if using tags (requires Redis/Memcached)
            Cache::tags(['urls'])->flush();
        }

        $this->info('Warming up URL cache...');

        $urls = Url::query()
            ->whereNull('deactivated_at')
            ->orWhere('deactivated_at', '>', now())
            ->get();

        $bar = $this->output->createProgressBar($urls->count());
        $bar->start();

        $cached = 0;
        foreach ($urls as $url) {
            $url->cacheUrl();
            $cached++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("✓ Successfully cached {$cached} URLs");

        return Command::SUCCESS;
    }
}
