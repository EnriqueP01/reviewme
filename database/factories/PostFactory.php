<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'group_id' => null,
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'visibility' => fake()->randomElement(['public', 'private', 'group']),
        ];
    }
}
