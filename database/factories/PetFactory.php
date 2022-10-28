<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $status = ['available' , 'pending' , 'sold'] ;

        return [
            'name' => fake()->name(),
            'available_count' => fake()->numberBetween(1,5) ,
            'user_id' => User::inRandomOrder()->first()->id,
            'status' => $status[array_rand($status)]
        ];
    }
}
