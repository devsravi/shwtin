<?php

namespace Database\Seeders;

use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed admin user first
        $this->call([
            UserSeeder::class,
        ]);
        // Create users
        // User::factory()->count(10000)->create();
        // Create urls
        // Url::factory()->count(2000)->create();

        // Create visits
        // Visit::factory()->createMany(5000);

        // Anonymous visit
        // Visit::factory()->count(500)->anonymous()->create();

        // Visit for specific user and URL
        // Visit::factory()
        //     ->count(100)
        //     ->forUser(1)
        //     ->forUrl(1)
        //     ->create();

        // Mobile visit from today
        // Visit::factory()
        //     ->count(150)
        //     ->mobile()
        //     ->today()
        //     ->create();

        // Create 10 visits for analytics testing
        // Visit::factory()
        //     ->count(1000)
        //     ->recent()
        //     ->create();
    }
}
