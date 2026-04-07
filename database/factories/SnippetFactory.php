<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class SnippetFactory extends Factory
{
    public function definition(): array
    {
        $samples = [
            "function calculateTotal(\$items) {\n    return array_reduce(\$items, fn(\$carry, \$item) => \$carry + \$item->price, 0);\n}",
            "const getData = async () => {\n    const response = await fetch('/api/v1/users');\n    return response.json();\n};",
            "public function handle() {\n    Log::info('Task started');\n    // TODO: optimization needed here\n    sleep(1);\n}",
            "SELECT id, name FROM users WHERE active = true ORDER BY created_at DESC LIMIT 10;",
            "body {\n    display: flex;\n    justify-content: center;\n    align-items: center;\n    height: 100vh;\n}"
        ];

        return [
            'post_id' => Post::factory(),
            'version_number' => 1,
            'code_content' => fake()->randomElement($samples),
            'language' => fake()->randomElement(['php', 'javascript', 'sql', 'css']),
        ];
    }
}
