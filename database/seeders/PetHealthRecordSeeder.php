<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PetHealthRecord;
use App\Models\User;
use Carbon\Carbon;

class PetHealthRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (assuming there's at least one user)
        $user = User::first();
        
        if (!$user) {
            $this->command->info('No users found. Please create a user first.');
            return;
        }

        // Sample pet health records with complete data
        $records = [
            [
                'user_id' => $user->id,
                'name' => 'Buddy',
                'species' => 'Dog',
                'breed' => 'Golden Retriever',
                'age' => 3,
                'weight' => 28.5,
                'condition' => 'Healthy',
                'medical_notes' => 'Regular checkups, no known allergies. Very active and playful.',
                'diagnosed_at' => Carbon::now()->subMonths(2)->toDateString(),
                'vaccine_name' => 'DHPP + Rabies',
                'date_given' => Carbon::now()->subMonths(3)->toDateString(),
                'next_due' => Carbon::now()->addMonths(9)->toDateString(),
                'vaccine_status' => 'Up to date',
            ],
            [
                'user_id' => $user->id,
                'name' => 'Whiskers',
                'species' => 'Cat',
                'breed' => 'Persian',
                'age' => 5,
                'weight' => 4.2,
                'condition' => 'Under treatment',
                'medical_notes' => 'Mild respiratory issues, requires daily medication. Indoor cat only.',
                'diagnosed_at' => Carbon::now()->subWeeks(3)->toDateString(),
                'vaccine_name' => 'FVRCP',
                'date_given' => Carbon::now()->subMonths(6)->toDateString(),
                'next_due' => Carbon::now()->addMonths(6)->toDateString(),
                'vaccine_status' => 'Due soon',
            ],
            [
                'user_id' => $user->id,
                'name' => 'Charlie',
                'species' => 'Dog',
                'breed' => 'Beagle',
                'age' => 7,
                'weight' => 15.8,
                'condition' => 'Healthy',
                'medical_notes' => 'Senior dog, regular dental cleanings recommended. Good appetite and energy.',
                'diagnosed_at' => Carbon::now()->subMonths(1)->toDateString(),
                'vaccine_name' => 'Bordetella',
                'date_given' => Carbon::now()->subMonths(4)->toDateString(),
                'next_due' => Carbon::now()->addMonths(8)->toDateString(),
                'vaccine_status' => 'Up to date',
            ],
        ];

        foreach ($records as $record) {
            PetHealthRecord::create($record);
        }

        $this->command->info('Pet health records seeded successfully!');
    }
}
