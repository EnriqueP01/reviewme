<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\Review;
use App\Models\Snippet;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MasterTestSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');

        // Create main user for testing
        $celestin = User::updateOrCreate(['email' => 'celestin@reviewme.io'], [
            'name' => 'Célestin Dev',
            'password' => Hash::make('password'),
            'reputation_score' => 5000,
            'bio' => 'Développeur Fullstack passionné. J\'adore optimiser les requêtes SQL et construire des architectures solides et scalables.',
            'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Celestin&backgroundColor=1a1b26',
        ]);

        // Creating more specific users to interact with
        $users = [$celestin];
        $roles = ['Architecte Backend', 'Senior Frontend Dev', 'Junior Dev', 'Lead Tech', 'Ingénieur DevOps'];

        for ($i = 0; $i < 30; $i++) {
            $firstName = $faker->firstName;
            $users[] = User::create([
                'name' => $firstName.' '.$faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'reputation_score' => rand(10, 3000),
                'bio' => $faker->randomElement($roles).'. '.$faker->realText(80),
                'avatar' => "https://api.dicebear.com/7.x/avataaars/svg?seed={$firstName}&backgroundColor=24283b",
            ]);
        }
        $usersCollection = collect($users);

        // Define options for randomizations
        $lenses = ['performance', 'logic', 'security', 'elegant', 'readability', 'clean', 'mindblown'];
        $langs = ['php', 'javascript', 'css', 'blade', 'html'];
        $reactionTypes = ['clean', 'optimisable', 'mindblown', 'security'];

        // Create Groups
        $groups = [];
        $groupNames = ['Laravel France', 'Optimisation Extrême', 'Design System & UI', 'DevOps & Architecture', 'Sécurité Web Avancée'];
        foreach ($groupNames as $gName) {
            $owner = $usersCollection->random();
            $group = Group::create([
                'name' => $gName,
                'slug' => Str::slug($gName),
                'description' => 'Le repaire des experts en '.strtolower($gName).'. '.$faker->realText(100),
                'owner_id' => $owner->id,
            ]);

            // Add members
            $membersCount = rand(5, 20);
            $members = $usersCollection->random($membersCount);
            foreach ($members as $mem) {
                $group->members()->attach($mem->id, ['role' => ($mem->id === $owner->id) ? 'admin' : 'member']);
            }

            // Explicitly ensure celestin is in some groups
            if (! $group->members->contains($celestin->id)) {
                if (rand(0, 1)) {
                    $group->members()->attach($celestin->id, ['role' => 'member']);
                }
            }

            $groups[] = $group;
        }

        $groupsCollection = collect($groups);

        // Helper to generate French post content
        $generatePostContent = function () use ($faker, $lenses) {
            $topics = [
                'Refactorisation d\'un contrôleur massif',
                'Optimisation des requêtes Eloquent (N+1)',
                'Ajout de cache Redis sur une route API critique',
                'Implémentation du pattern Strategy',
                'Animation CSS sans JavaScript (Pur CSS)',
                'Sécurisation complète d\'un endpoint',
                'Amélioration de la lisibilité des middlewares',
                'Dockerisation du workflow de CI/CD',
                'Passage de Blade à Vue.js sur un composant',
                'Résolution de fuites de mémoire en PHP',
            ];

            return [
                'title' => $faker->randomElement($topics).' - '.substr($faker->sentence(2), 0, -1),
                'short_description' => $faker->realText(60),
                'description' => "Salut l'équipe ! J'ai rencontré ce défi technique récemment : \n\n".$faker->realText(250)."\n\nEst-ce que quelqu'un aurait une approche plus élégante ou performante pour traiter cela ?",
                'review_goals' => "J'aimerais surtout des retours critiques sur la ".$faker->randomElement(['sécurité', 'performance', 'lisibilité', 'modularité', 'maintenabilité'])." et l'architecture globale.",
                'improvement_goals' => "L'objectif principal est de réduire la dette technique avant la mise en production.",
                'context' => "Ceci est lié à notre branche 'feat/core-refactor' du projet.",
                'visibility' => 'public',
                'lens' => $faker->randomElement($lenses),
            ];
        };

        // Create posts for all users
        $allPosts = [];

        foreach ($usersCollection as $user) {
            // Give each user 1 to 3 posts, but Celestin gets more
            $postCount = ($user->id === $celestin->id) ? 8 : rand(1, 3);

            for ($i = 0; $i < $postCount; $i++) {
                $postData = $generatePostContent();
                $postData['user_id'] = $user->id;

                // 50% chance to be in a group
                if (rand(0, 1)) {
                    $postData['group_id'] = $groupsCollection->random()->id;
                }

                $post = Post::create($postData);
                $allPosts[] = $post;

                // Create snippets for post
                $numSnippets = rand(1, 2);
                $language = $faker->randomElement($langs);
                for ($j = 1; $j <= $numSnippets; $j++) {
                    $code = "<?php\n\n/**\n * Version $j de l'implémentation\n * Optimisé pour la performance et la lisibilité\n */\nfunction processData".ucfirst(Str::camel($faker->word))."(\$input) {\n    // Validation des données d'entrée\n    if (!\$input) {\n        throw new InvalidArgumentException('Input requis pour le traitement');\n    }\n\n    \$result = array_map(function(\$item) {\n        return is_numeric(\$item) ? \$item * 2 : strtoupper(\$item);\n    }, \$input);\n\n    return \$result;\n}\n";
                    if ($language === 'javascript') {
                        $code = "/**\n * Handler pour ".Str::camel($faker->word)."\n * Version $j\n */\nconst ".Str::camel($faker->word)."Handler = async (payload) => {\n  if (!payload) {\n    console.error('Payload manquant');\n    return null;\n  }\n\n  try {\n    const response = await fetch('/api/process', {\n      method: 'POST',\n      body: JSON.stringify(payload)\n    });\n    return await response.json();\n  } catch (err) {\n    return { success: false, error: err.message };\n  }\n};";
                    } elseif ($language === 'css') {
                        $code = '/* Container principal pour '.Str::slug($faker->word)." */\n.card-container {\n  display: flex;\n  flex-direction: column;\n  gap: 1.5rem;\n  padding: 2rem;\n  background: rgba(26, 27, 38, 0.8);\n  backdrop-filter: blur(10px);\n  border: 1px solid rgba(255, 255, 255, 0.1);\n  border-radius: 1rem;\n  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);\n}\n\n.card-container:hover {\n  transform: translateY(-4px);\n}";
                    } elseif ($language === 'blade') {
                        $code = "<div class=\"flex flex-col space-y-6 p-4\">\n    <header class=\"border-b border-white/10 pb-4\">\n        <h2 class=\"text-xl font-bold text-white\">{{ \$title }}</h2>\n        <p class=\"text-sm text-gray-400\">Version $j</p>\n    </header>\n\n    <main class=\"grid grid-cols-1 md:grid-cols-2 gap-4\">\n        @foreach (\$items as \$item)\n            <x-ui.card :data=\"\$item\" />\n        @endforeach\n    </main>\n</div>";
                    }

                    $snippet = Snippet::create([
                        'post_id' => $post->id,
                        'version_number' => $j,
                        'description' => 'Fichier '.Str::slug($faker->word).".$language",
                        'language' => $language,
                        'code_content' => $code,
                        'sort_order' => $j,
                    ]);

                    // Add Reviews to the snippet
                    // Give more reviews to Celestin's posts
                    $chanceReview = ($user->id === $celestin->id) ? 90 : 40;
                    if (rand(1, 100) <= $chanceReview) {
                        $reviewerCount = rand(1, 3);
                        for ($k = 0; $k < $reviewerCount; $k++) {
                            $reviewer = $usersCollection->random();

                            // Prevent self-reviewing
                            if ($reviewer->id === $user->id) {
                                continue;
                            }

                            $review = Review::create([
                                'snippet_id' => $snippet->id,
                                'user_id' => $reviewer->id,
                                'line_number' => rand(1, 4),
                                'content' => 'Super approche, cependant, as-tu pensé à vérifier cela ? '.$faker->realText(80),
                            ]);

                            // Add reactions to the review
                            if (rand(0, 1)) {
                                Reaction::create([
                                    'user_id' => $usersCollection->random()->id,
                                    'reactable_id' => $review->id,
                                    'reactable_type' => Review::class,
                                    'type' => $faker->randomElement($reactionTypes),
                                ]);
                            }
                        }
                    }
                }

                // Add reactions to the post itself
                $reactionCount = rand(2, 6);
                for ($r = 0; $r < $reactionCount; $r++) {
                    Reaction::create([
                        'user_id' => $usersCollection->random()->id,
                        'reactable_id' => $post->id,
                        'reactable_type' => Post::class,
                        'type' => $faker->randomElement($reactionTypes),
                    ]);
                }
            }
        }

        // Specific interactions: Celestin comments on others' posts
        $otherPosts = array_filter($allPosts, fn ($p) => $p->user_id !== $celestin->id);
        shuffle($otherPosts);
        $sampledOtherPosts = array_slice($otherPosts, 0, 10);

        foreach ($sampledOtherPosts as $post) {
            $snippet = $post->snippets()->first();
            if ($snippet) {
                Review::create([
                    'snippet_id' => $snippet->id,
                    'user_id' => $celestin->id,
                    'line_number' => 2,
                    'content' => "C'est une implémentation très propre ! Personnellement j'aurais utilisé un Early Return ici pour éviter la pyramide de l'enfer. Sinon super !",
                ]);

                Reaction::create([
                    'user_id' => $celestin->id,
                    'reactable_id' => $post->id,
                    'reactable_type' => Post::class,
                    'type' => $faker->randomElement($reactionTypes),
                ]);
            }
        }
    }
}
