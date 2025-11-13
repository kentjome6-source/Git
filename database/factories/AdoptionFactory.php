<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Adoption;
use App\Models\Pet;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Adoption>
 */
class AdoptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pet_id' => Pet::factory(),
            'user_id' => User::factory(),
            'pet_name' => $this->faker->name,
            'breed' => $this->faker->randomElement(['Labrador', 'Persian', 'Siamese', 'Golden Retriever', 'Bulldog', 'Poodle', 'Maine Coon']),
            'age' => $this->faker->numberBetween(1, 15),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'description' => $this->faker->sentence,
            'image_path' => null,
            'is_adopted' => false,
            'adopter_id' => null,
            'adoption_request_status' => 'pending',
            'adoption_requested_at' => null,
        ];
    }
}