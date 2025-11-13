<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LostFound;
use App\Models\User;

class LostFoundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users to associate with the listings
        $users = User::where('role', 'user')->take(5)->get();
        
        if ($users->count() > 0) {
            // Create sample lost pet listings
            LostFound::create([
                'user_id' => $users->random()->id,
                'type' => 'lost',
                'pet_name' => 'Buddy',
                'pet_type' => 'dog',
                'breed' => 'Golden Retriever',
                'color' => 'Golden',
                'size' => 'large',
                'age' => 3,
                'gender' => 'male',
                'description' => 'Friendly golden retriever, wearing a blue collar with a tag. Very social and loves treats.',
                'location' => 'Central Park, San Francisco',
                'latitude' => 8.3480,
                'longitude' => 125.9790,
                'date_lost_found' => now()->subDays(2),
                'contact_name' => 'John Smith',
                'contact_phone' => '09123456789',
                'contact_email' => 'john.smith@example.com',
                'is_resolved' => false,
            ]);

            LostFound::create([
                'user_id' => $users->random()->id,
                'type' => 'lost',
                'pet_name' => 'Whiskers',
                'pet_type' => 'cat',
                'breed' => 'Persian',
                'color' => 'White',
                'size' => 'medium',
                'age' => 2,
                'gender' => 'female',
                'description' => 'Beautiful white Persian cat with blue eyes. Shy but very affectionate once comfortable.',
                'location' => 'Downtown San Francisco',
                'latitude' => 8.3420,
                'longitude' => 125.9850,
                'date_lost_found' => now()->subDays(5),
                'contact_name' => 'Maria Garcia',
                'contact_phone' => '09123456790',
                'contact_email' => 'maria.garcia@example.com',
                'is_resolved' => false,
            ]);

            // Create sample found pet listings
            LostFound::create([
                'user_id' => $users->random()->id,
                'type' => 'found',
                'pet_name' => 'Max',
                'pet_type' => 'dog',
                'breed' => 'Labrador',
                'color' => 'Black',
                'size' => 'large',
                'age' => 4,
                'gender' => 'male',
                'description' => 'Large black Labrador, appears to be well-trained. Found near the riverbank.',
                'location' => 'Riverbank Park',
                'latitude' => 8.3500,
                'longitude' => 125.9750,
                'date_lost_found' => now()->subDays(1),
                'contact_name' => 'Robert Johnson',
                'contact_phone' => '09123456791',
                'contact_email' => 'robert.johnson@example.com',
                'is_resolved' => false,
            ]);

            LostFound::create([
                'user_id' => $users->random()->id,
                'type' => 'found',
                'pet_name' => 'Luna',
                'pet_type' => 'cat',
                'breed' => 'Siamese',
                'color' => 'Cream and brown',
                'size' => 'small',
                'age' => 1,
                'gender' => 'female',
                'description' => 'Young Siamese kitten, very playful. Found in the neighborhood near the school.',
                'location' => 'San Francisco Elementary School',
                'latitude' => 8.3400,
                'longitude' => 125.9800,
                'date_lost_found' => now(),
                'contact_name' => 'Sarah Williams',
                'contact_phone' => '09123456792',
                'contact_email' => 'sarah.williams@example.com',
                'is_resolved' => false,
            ]);
        }
    }
}