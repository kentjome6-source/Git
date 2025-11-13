<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pet;
use App\Models\Appointment;
use App\Models\LostFound;
use App\Models\Adoption;
use App\Models\PetHealthRecord;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if user already exists
        if (User::where('email', 'kenan.petparent.test@gmail.com')->exists()) {
            echo "Test user already exists.\n";
            return;
        }
        
        // Create a pet parent user for testing
        $petParent = User::create([
            'name' => 'Kenan PetParent',
            'email' => 'kenan.petparent.test@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone' => '123-456-7890',
            'address' => '123 Pet Street, Animal City',
            'is_active' => true,
        ]);
        
        // Create some pets for the pet parent
        $pet1 = Pet::create([
            'user_id' => $petParent->id,
            'name' => 'Buddy',
            'description' => 'A friendly golden retriever',
        ]);
        
        $pet2 = Pet::create([
            'user_id' => $petParent->id,
            'name' => 'Whiskers',
            'description' => 'A playful tabby cat',
        ]);
        
        // Create some health records for the pets
        PetHealthRecord::create([
            'user_id' => $petParent->id,
            'name' => 'Buddy',
            'species' => 'Dog',
            'breed' => 'Golden Retriever',
            'age' => 3,
            'weight' => 30.5,
            'condition' => 'Healthy',
            'medical_notes' => 'Annual checkup completed',
        ]);
        
        PetHealthRecord::create([
            'user_id' => $petParent->id,
            'name' => 'Whiskers',
            'species' => 'Cat',
            'breed' => 'Tabby',
            'age' => 2,
            'weight' => 4.2,
            'condition' => 'Healthy',
            'medical_notes' => 'Vaccinations up to date',
        ]);
        
        // Create some appointments for the pet parent
        Appointment::create([
            'user_id' => $petParent->id,
            'pet_id' => $pet1->id,
            'pet_name' => 'Buddy',
            'consultation_type' => 'appointment',
            'urgency_level' => 'medium',
            'status' => 'pending',
            'chief_complaint' => 'Regular checkup',
            'consultation_reason' => 'routine_checkup',
            'appointment_date' => now()->addDays(7),
            'appointment_time' => '10:00:00',
        ]);
        
        Appointment::create([
            'user_id' => $petParent->id,
            'pet_id' => $pet2->id,
            'pet_name' => 'Whiskers',
            'consultation_type' => 'appointment',
            'urgency_level' => 'low',
            'status' => 'completed',
            'chief_complaint' => 'Annual vaccinations',
            'consultation_reason' => 'vaccination',
            'appointment_date' => now()->subDays(30),
            'appointment_time' => '14:30:00',
        ]);
        
        // Create some lost & found listings
        LostFound::create([
            'user_id' => $petParent->id,
            'type' => 'lost',
            'pet_name' => 'Max',
            'pet_type' => 'Dog',
            'breed' => 'Labrador',
            'description' => 'Lost during walk in the park',
            'location' => 'Central Park',
            'date_lost_found' => now()->subDays(5),
            'contact_name' => 'Kenan PetParent',
            'contact_phone' => '123-456-7890',
        ]);
        
        LostFound::create([
            'user_id' => $petParent->id,
            'type' => 'found',
            'pet_type' => 'Cat',
            'breed' => 'Siamese',
            'description' => 'Found near the subway station',
            'location' => 'Downtown Station',
            'date_lost_found' => now()->subDays(2),
            'contact_name' => 'Kenan PetParent',
            'contact_phone' => '123-456-7890',
        ]);
        
        // Create adoption listings
        Adoption::create([
            'user_id' => $petParent->id,
            'uploader_type' => 'user',
            'pet_name' => 'Luna',
            'breed' => 'Mixed Breed',
            'age' => 1,
            'gender' => 'female',
            'description' => 'Playful puppy looking for a loving home',
            'is_adopted' => false,
        ]);
        
        echo "Test pet parent user created with sample data.\n";
    }
}