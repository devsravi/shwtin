<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::table('users')->updateOrInsert(
                [
                    'email' => 'ravi.kumar@tri.me'
                ],
                [
                    'name' => 'Ravi Kunar',
                    'first_name' => 'Ravi',
                    'last_name' => 'Kunar',
                    'phone_number' => '+916388778933',
                    'password' => static::$password ??= Hash::make('ravi.kumar@tri.me'),
                    'admin' => 1,
                    'email_verified_at' => now()
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
