<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Adoption;
use App\Models\User;

class UpdateAdoptionUploaderTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all adoption records
        $adoptions = Adoption::all();
        
        foreach ($adoptions as $adoption) {
            // Get the user who created the adoption
            $user = User::find($adoption->user_id);
            
            if ($user) {
                // Set uploader_type based on user role
                if ($user->role === 'vet') {
                    $adoption->uploader_type = 'vet';
                } else {
                    $adoption->uploader_type = 'user';
                }
                
                $adoption->save();
            }
        }
    }
}