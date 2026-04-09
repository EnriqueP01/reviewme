<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GroupFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->company().' Devs';

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'owner_id' => User::factory(),
        ];
    }
}
