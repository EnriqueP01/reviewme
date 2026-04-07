<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use App\Models\Post;
use App\Models\Snippet;
use App\Models\Review;
use App\Models\Reaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Créer quelques utilisateurs spécifiques
        $admin = User::factory()->create([
            'name' => 'Lead Dev',
            'email' => 'lead@reviewme.io',
            'reputation_score' => 9999,
        ]);

        // 2. Créer 20 autres utilisateurs
        $users = User::factory(20)->create();
        $allUsers = $users->concat([$admin]);

        // 3. Créer des groupes
        $groups = Group::factory(5)->create([
            'owner_id' => $admin->id,
        ]);

        // 4. Créer des posts (Vibes) pour chaque utilisateur
        $allUsers->each(function ($user) use ($groups) {
            $posts = Post::factory(rand(2, 4))->create([
                'user_id' => $user->id,
                'group_id' => rand(0, 1) ? $groups->random()->id : null,
            ]);

            $posts->each(function ($post) use ($user) {
                // Créer V1
                $s1 = Snippet::factory()->create([
                    'post_id' => $post->id,
                    'version_number' => 1,
                ]);

                // Ajouter des reviews sur V1 par d'autres utilisateurs
                Review::factory(rand(1, 4))->create([
                    'snippet_id' => $s1->id,
                ]);

                // Parfois créer une V2
                if (rand(0, 1)) {
                    $s2 = Snippet::factory()->create([
                        'post_id' => $post->id,
                        'version_number' => 2,
                        'code_content' => $s1->code_content . "\n// Optimized Version\n",
                    ]);
                    
                    Review::factory(rand(1, 2))->create([
                        'snippet_id' => $s2->id,
                    ]);
                }

                // Ajouter des réactions
                Reaction::factory(rand(2, 6))->create([
                    'reactable_id' => $post->id,
                    'reactable_type' => Post::class,
                ]);
            });
        });
    }
}
