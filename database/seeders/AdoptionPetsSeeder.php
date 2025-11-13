<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Adoption;
use App\Models\Pet;
use App\Models\User;

class AdoptionPetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some sample users
        $users = User::where('role', 'user')->limit(5)->get();
        
        foreach ($users as $user) {
            // Get the user's pets
            $pets = $user->pets->take(2);
            
            foreach ($pets as $pet) {
                // Create adoption listings for some pets
                if (rand(0, 1)) {
                    Adoption::create([
                        'pet_id' => $pet->id,
                        'user_id' => $user->id,
                        'pet_name' => $pet->name ?? 'Unnamed Pet',
                        'breed' => ['Labrador', 'Persian', 'Siamese', 'Golden Retriever', 'Bulldog'][rand(0, 4)] ?? null,
                        'age' => rand(1, 10),
                        'gender' => ['male', 'female'][rand(0, 1)],
                        'description' => 'This is a lovely pet looking for a new home. ' . 
                                        ['Very playful and friendly', 'Loves cuddles and treats', 'Great with kids'][rand(0, 2)],
                        'image_path' => $pet->image_path,
                        'is_adopted' => false,
                    ]);
                }
            }
        }
    }
}