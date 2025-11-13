<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shelter;

class SanFranciscoVetLocationsSeeder extends Seeder
{
    /**
     * Run the database seeds for San Francisco, Agusan del Sur veterinary locations.
     */
    public function run(): void
    {
        $vetLocations = [
            [
                'name' => 'Boss Jack Pet Grooming Services',
                'type' => 'grooming',
                'description' => 'Pet grooming and veterinary services. Owner: Jessie Surya',
                'address' => 'Purok 1 Barangay 4',
                'city' => 'San Francisco',
                'province' => 'Agusan Del Sur',
                'phone' => '09265317386',
                'email' => null,
                'operating_hours' => json_encode([
                    'thursday' => 'Open 24 hours',
                    'friday' => 'Open 24 hours',
                    'saturday' => 'Open 24 hours',
                    'sunday' => 'Open 24 hours',
                    'monday' => 'Open 24 hours',
                    'tuesday' => 'Open 24 hours',
                    'wednesday' => 'Open 24 hours'
                ]),
                'latitude' => 8.50712537504509,
                'longitude' => 125.97732043268097,
                'is_active' => true
            ],
            [
                'name' => 'Happy Pets Veterinary Clinic & Pet Grooming Services',
                'type' => 'veterinarian',
                'description' => 'Veterinary clinic and pet grooming services. Owner: Dr. Maria Elisa A. Gelacio',
                'address' => 'Purok 2B Casera Barangay 1',
                'city' => 'San Francisco',
                'province' => 'Agusan Del Sur',
                'phone' => '09098250841',
                'email' => null,
                'operating_hours' => json_encode([
                    'thursday' => '8 AM–5 PM',
                    'friday' => '8 AM–5 PM',
                    'saturday' => '8 AM–5 PM',
                    'sunday' => '9 AM–4 PM',
                    'monday' => '8 AM–5 PM',
                    'tuesday' => '8 AM–5 PM',
                    'wednesday' => '8 AM–5 PM'
                ]),
                'latitude' => 8.509510173919576,
                'longitude' => 125.97534918109636,
                'is_active' => true
            ],
            [
                'name' => 'Pawsitive Point Veterinary Services and Supplies',
                'type' => 'veterinarian',
                'description' => 'Veterinary services and supplies. Owner: Dr. Regine P. Dioneda, DVM',
                'address' => 'Barangay 2, Public Market, 2nd floor Diwata Building',
                'city' => 'San Francisco',
                'province' => 'Agusan Del Sur',
                'phone' => '09124610876',
                'email' => null,
                'operating_hours' => null,
                'latitude' => 8.508275601088428,
                'longitude' => 125.97752621956413,
                'is_active' => true
            ],
            [
                'name' => 'Whiskers & Wags Veterinary Services',
                'type' => 'veterinarian',
                'description' => 'Veterinary services for pets. Owner: Dr. Gieselle J. Calundre',
                'address' => 'Barangay 5',
                'city' => 'San Francisco',
                'province' => 'Agusan Del Sur',
                'phone' => '09127091999, 09156789867',
                'email' => null,
                'operating_hours' => null,
                'latitude' => 8.504957776261762,
                'longitude' => 125.97904158053043,
                'is_active' => true
            ]
        ];

        foreach ($vetLocations as $location) {
            // Check if the shelter already exists and update it, otherwise create new
            $existing = Shelter::where('name', $location['name'])
                              ->where('city', $location['city'])
                              ->where('province', $location['province'])
                              ->first();
            
            if ($existing) {
                $existing->update($location);
            } else {
                Shelter::create($location);
            }
        }
    }
}