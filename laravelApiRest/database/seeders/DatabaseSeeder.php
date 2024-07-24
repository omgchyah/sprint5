<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Create the admin user
        $admin = User::create([
                    'nickname' => 'admin',
                    'name' => 'Admin User',
                    'email' => 'admin@example.com',
                    'email_verified_at' => now(),
                    'role' => 'admin',
                    'password' => Hash::make('password'),
                    'remember_token' => Str::random(10),
                ]);
        // Generate a token for the admin user
        $adminToken = $admin->createToken('authToken')->accessToken;
        $this->command->info('Admin token: ' . $adminToken);
        // User::factory(10)->create();

        $users = User::factory()
            ->count(20)
            ->hasGames(10)
            ->create();

                    // Generate tokens for each user and output them
        foreach ($users as $user) {
            $userToken = $user->createToken('authToken')->accessToken;
            $this->command->info("User {$user->nickname} token: " . $userToken);
        }
    }
}
