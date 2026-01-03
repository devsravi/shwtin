<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Url;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Url>
 */
class UrlFactory extends Factory
{
    protected $model = Url::class;

    public function definition(): array
    {

        // 20% of URLs are guest-created
        $isGuest = $this->faker->boolean(20);

        // 10% chance deactivated
        $isDeactivated = $this->faker->boolean(10);

        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'is_guest' => $isGuest,
            'name' => $this->faker->sentence(2),
            'domain' => $this->faker->domainName(),
            'destination_url' => $this->faker->url(),
            'url_key' => $this->generateUrlKey(),
            'default_short_url' => $this->buildShortUrl($this->generateUrlKey()),
            'single_use' => $this->faker->boolean(30),
            'forward_query_params' => $this->faker->boolean(40),
            'track_visits' => $this->faker->boolean(90),
            'redirect_status_code' => 302,
            'track_ip_address' => $this->faker->boolean(80),
            'track_operating_system' => $this->faker->boolean(85),
            'track_operating_system_version' => $this->faker->boolean(85),
            'track_browser' => $this->faker->boolean(85),
            'track_browser_version' => $this->faker->boolean(85),
            'track_referer_url' => $this->faker->boolean(85),
            'track_device_type' => $this->faker->boolean(85),
            'referer_url' => $this->faker->optional()->url(),
            'activated_at' => now(),
            'deactivated_at' => $isDeactivated ? now()->subDays(rand(1, 30)) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Generate a unique url_key
     */
    private function generateUrlKey(): string
    {
        return $this->faker->unique()->regexify('[A-Za-z0-9]{6}');
    }

    /**
     * Build the full short URL including base URL, optional prefix, and key
     */
    private function buildShortUrl(string $urlKey): string
    {
        $baseUrl = rtrim(config('short-url.default_url') ?? config('app.url'), '/');
        $prefix = trim(config('short-url.prefix', ''), '/');

        $shortUrl = $baseUrl.'/';
        if ($prefix !== '') {
            $shortUrl .= $prefix.'/';
        }
        $shortUrl .= $urlKey;

        return $shortUrl;
    }

    // Optional factory states
    public function deactivated(): self
    {
        return $this->state(fn () => ['deactivated_at' => now()->subDay()]);
    }

    public function inactive(): self
    {
        return $this->state(fn () => [
            'activated_at' => null,
            'deactivated_at' => null,
        ]);
    }

    public function forUser(User $user): self
    {
        return $this->state(fn () => [
            'user_id' => $user->id,
            'is_guest' => false,
        ]);
    }
}
