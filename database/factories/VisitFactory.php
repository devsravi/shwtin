<?php

namespace Database\Factories;

use App\Models\Visit;
use App\Models\User;
use App\Models\Url;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Visit>
 */
class VisitFactory extends Factory
{
    protected $model = Visit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera'];
        $operatingSystems = ['Windows', 'macOS', 'Linux', 'iOS', 'Android'];
        $deviceTypes = ['desktop', 'mobile', 'tablet'];
        $deviceManufacturers = ['Apple', 'Samsung', 'Lenovo', 'HP', 'ACER', 'ASUS', 'MOTOROLA'];
        $ips = collect(range(1, 200))
            ->map(fn() => fake()->ipv4())
            ->toArray();

        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'url_id' => Url::inRandomOrder()->first()->id,
            'ip_address' =>  fn() => $ips[array_rand($ips)],
            'user_agent' => fake()->userAgent(),
            'operating_system' => $os = fake()->randomElement($operatingSystems),
            'operating_system_alias' => $os = fake()->randomElement($operatingSystems),
            'operating_system_version' => $this->getOsVersion($os),
            'browser' => $browser = fake()->randomElement($browsers),
            'browser_version' => fake()->numberBetween(80, 120) . '.' . fake()->numberBetween(0, 9),
            'engine' => 'Blink',
            'device_type' => fake()->randomElement($deviceTypes),
            'device_manufacturer' => fake()->randomElement($deviceManufacturers),
            'device_model' => fake()->randomElement($deviceManufacturers),
            'iso_code' => fake()->countryCode(),
            'country' => fake()->country(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'timezone' => fake()->timezone(),
            'lat' => fake()->latitude(),
            'long' => fake()->longitude(),
            'continent' => fake()->randomElement(['Africa', 'Antarctica', 'Asia', 'Europe', 'North America', 'Oceania', 'South America']),
            'currency' => json_encode([
                'code' => fake()->currencyCode(),
                'symbol' => fake()->randomElement(['$', '€', '£', '¥', '₹']),
                'name' => fake()->randomElement(['US Dollar', 'Euro', 'British Pound', 'Japanese Yen', 'Indian Rupee'])
            ]),
            'is_default' => fake()->randomElement(['yes', 'no', null]),
            'referer_url' => fake()->optional(0.7)->url(),
            'visited_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Indicate that the visit has no user (anonymous).
     */
    public function anonymous(): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => null,
        ]);
    }

    /**
     * Indicate that the visit is for a specific user.
     */
    public function forUser(User|int $user): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => $user instanceof User ? $user->id : $user,
        ]);
    }

    /**
     * Indicate that the visit is for a specific URL.
     */
    public function forUrl(Url|int $url): static
    {
        return $this->state(fn(array $attributes) => [
            'url_id' => $url instanceof Url ? $url->id : $url,
        ]);
    }

    /**
     * Indicate that the visit is from a mobile device.
     */
    public function mobile(): static
    {
        return $this->state(fn(array $attributes) => [
            'device_type' => 'mobile',
            'operating_system' => fake()->randomElement(['iOS', 'Android']),
            'operating_system_version' => fake()->randomElement(['14.0', '15.0', '16.0', '17.0']),
        ]);
    }

    /**
     * Indicate that the visit is from a desktop device.
     */
    public function desktop(): static
    {
        return $this->state(fn(array $attributes) => [
            'device_type' => 'desktop',
            'operating_system' => fake()->randomElement(['Windows', 'macOS', 'Linux']),
        ]);
    }

    /**
     * Indicate that the visit is from a specific country.
     */
    public function fromCountry(string $country, string $isoCode): static
    {
        return $this->state(fn(array $attributes) => [
            'country' => $country,
            'iso_code' => $isoCode,
        ]);
    }

    /**
     * Indicate that the visit occurred recently.
     */
    public function recent(): static
    {
        return $this->state(fn(array $attributes) => [
            'visited_at' => fake()->dateTimeBetween('-7 days', 'now'),
        ]);
    }

    /**
     * Indicate that the visit occurred today.
     */
    public function today(): static
    {
        return $this->state(fn(array $attributes) => [
            'visited_at' => fake()->dateTimeBetween('today', 'now'),
        ]);
    }

    /**
     * Get OS version based on operating system.
     */
    private function getOsVersion(string $os): string
    {
        return match ($os) {
            'Windows' => fake()->randomElement(['10', '11']),
            'macOS' => fake()->randomElement(['12.0', '13.0', '14.0', '15.0']),
            'Linux' => fake()->randomElement(['5.4', '5.15', '6.1']),
            'iOS' => fake()->randomElement(['15.0', '16.0', '17.0', '18.0']),
            'Android' => fake()->randomElement(['11', '12', '13', '14']),
            default => fake()->randomElement(['1.0', '2.0', '3.0']),
        };
    }
}
