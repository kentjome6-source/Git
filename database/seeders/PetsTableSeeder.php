<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pet;
use App\Models\User;

class PetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some sample users
        $users = User::where('role', 'user')->limit(5)->get();
        
        foreach ($users as $user) {
            // Create 2-3 pets for each user
            for ($i = 0; $i < rand(2, 3); $i++) {
                Pet::create([
                    'user_id' => $user->id,
                    'name' => ['Buddy', 'Luna', 'Max', 'Bella', 'Charlie', 'Lucy', 'Cooper', 'Daisy'][rand(0, 7)],
                    'description' => 'This is a wonderful pet that loves to play and interact with people. ' . 
                                    ['Very active and playful', 'Calm and affectionate', 'Intelligent and trainable'][rand(0, 2)],
                ]);
            }
        }
    }
}