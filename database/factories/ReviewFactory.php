<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Snippet;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'snippet_id' => Snippet::factory(),
            'user_id' => User::factory(),
            'line_number' => fake()->numberBetween(1, 5),
            'content' => fake()->randomElement([
                "Cette approche est très propre, bravo ! ✨",
                "Tu pourrais optimiser cette boucle en utilisant array_map. 🚀",
                "Attention ici, risque potentiel de sécurité (SQL Injection ?). 🛡️",
                "J'adore l'usage du pattern ici. 🤯",
                "Est-ce que tu as pensé à gérer le cas où \$data est null ?",
                "C'est du clean code, très facile à lire."
            ]),
        ];
    }
}
