<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reactable_id' => Post::factory(),
            'reactable_type' => Post::class,
            'type' => fake()->randomElement(['clean', 'optimisable', 'mindblown', 'security']),
        ];
    }
}
