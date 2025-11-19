<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vetshop;

class VetShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vetshop::create([
            'name' => 'San Francisco Veterinary Clinic',
            'type' => 'veterinarian',
            'description' => 'Full service veterinary clinic providing comprehensive care for all pets.',
            'address' => 'Purok 5, Poblacion',
            'city' => 'San Francisco',
            'province' => 'Agusan Del Sur',
            'phone' => '(085) 123-4567',
            'email' => 'clinic@sanfranciscovet.com',
            'operating_hours' => [
                'monday' => '8:00 AM - 5:00 PM',
                'tuesday' => '8:00 AM - 5:00 PM',
                'wednesday' => '8:00 AM - 5:00 PM',
                'thursday' => '8:00 AM - 5:00 PM',
                'friday' => '8:00 AM - 5:00 PM',
                'saturday' => '9:00 AM - 12:00 PM',
                'sunday' => 'Closed'
            ],
            'latitude' => 8.3450,
            'longitude' => 125.9800,
            'is_active' => true
        ]);

        Vetshop::create([
            'name' => 'Paws & Claws Pet Shop',
            'type' => 'pet_shop',
            'description' => 'Your one-stop shop for pet supplies, food, and accessories.',
            'address' => 'Main Street, Poblacion',
            'city' => 'San Francisco',
            'province' => 'Agusan Del Sur',
            'phone' => '(085) 987-6543',
            'email' => 'info@pawsandclaws.com',
            'operating_hours' => [
                'monday' => '9:00 AM - 6:00 PM',
                'tuesday' => '9:00 AM - 6:00 PM',
                'wednesday' => '9:00 AM - 6:00 PM',
                'thursday' => '9:00 AM - 6:00 PM',
                'friday' => '9:00 AM - 6:00 PM',
                'saturday' => '10:00 AM - 4:00 PM',
                'sunday' => 'Closed'
            ],
            'latitude' => 8.3460,
            'longitude' => 125.9810,
            'is_active' => true
        ]);
    }
}