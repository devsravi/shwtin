<?php

namespace App\Providers;

use App\Classes\Builder;
use App\Classes\KeyGenerator;
use App\Classes\UlidKeyGenerator;
use App\Interfaces\UrlKeyGenerator;
use App\Interfaces\UserAgentDriver;
use App\Models\User;
use Hashids\Hashids;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/short-url.php', 'short-url');

        $this->app->bind(UserAgentDriver::class, config('short-url.user_agent_driver'));
        $this->app->bind(UrlKeyGenerator::class, config('short-url.url_key_generator'));

        $this->app->bind('short-url.builder', function (Application $app): Builder {
            return new Builder(
                urlKeyGenerator: $app->make(UrlKeyGenerator::class),
            );
        });

        $this->app->when(KeyGenerator::class)
            ->needs(Hashids::class)
            ->give(fn(): Hashids => new Hashids(
                salt: config('short-url.key_salt'),
                minHashLength: (int) config('short-url.key_length'),
                alphabet: config('short-url.alphabet')
            ));


        // Binding for new ULID-based KeyGenerator
        $this->app->when(UlidKeyGenerator::class)
            ->needs('$keyLength')
            ->give(fn(): int => (int) config('short-url.key_length', 7));

        $this->app->when(UlidKeyGenerator::class)
            ->needs('$maxAttempts')
            ->give(fn(): int => 5);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
