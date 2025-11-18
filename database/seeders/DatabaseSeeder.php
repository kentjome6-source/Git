<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Only create essential accounts
        // Admin account
        if (!User::where('email', 'admin@pawportal.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@pawportal.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]);
        }

        // Vet account
        if (!User::where('email', 'vet@pawportal.com')->exists()) {
            User::create([
                'name' => 'Vet User',
                'email' => 'vet@pawportal.com',
                'password' => Hash::make('vet123'),
                'role' => 'vet',
            ]);
        }

        // Seed shelters and users
        $this->call([
            UserSeeder::class, // Add our new UserSeeder
            ShelterSeeder::class,
            // PetsTableSeeder::class,  // Commented out to prevent sample pets
            // AdoptionPetsSeeder::class, // Commented out to prevent sample adoption listings
            PostSeeder::class, // Add our new PostSeeder
        ]);
    }
}