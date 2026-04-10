<?php

namespace Database\Seeders;

use App\Models\FullReview;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Reaction;
use App\Models\Snippet;
use App\Models\User;
use App\Models\UserSkill;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserProfileSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['handle' => 'enriquep011'],
            [
                'name' => 'Enrique P.',
                'password' => Hash::make('password'),
                'bio' => 'Developer & Platform Owner. Passionné par l\'IA et le Vibe Coding.',
                'reputation_score' => 2450, // Elite level
            ]
        );

        // Nettoyage pour éviter les doublons lors des re-runs
        $user->posts()->delete();
        $user->skills()->delete();

        // 1. SKILLS
        $skills = [
            ['lens' => 'logic', 'score' => 850],
            ['lens' => 'performance', 'score' => 920],
            ['lens' => 'security', 'score' => 680],
        ];

        foreach ($skills as $skill) {
            UserSkill::create(array_merge($skill, ['user_id' => $user->id]));
        }

        // 2. GÉNÉRATION DE L'HISTORIQUE D'ACTIVITÉ (Heatmap)
        $now = now();
        $startDate = (clone $now)->subYear();

        for ($i = 0; $i < 20; $i++) {
            $postDate = (clone $startDate)->addDays(rand(0, 360));
            if ($postDate->isAfter($now)) continue;

            $post = Post::create([
                'user_id' => $user->id,
                'title' => 'Artifact #' . ($i + 1) . ': ' . collect(['Middleware Optimization', 'Docker Layering', 'Kernel Patch', 'Neural Bridge'])->random(),
                'short_description' => 'A technical deep dive into logical optimizations.',
                'description' => 'Exploring the impact of modern architectural patterns on system throughput.',
                'visibility' => 'public',
                'lens' => collect(['logic', 'security', 'performance', 'elegant'])->random(),
                'created_at' => $postDate,
            ]);

            Snippet::create([
                'post_id' => $post->id,
                'filename' => 'CoreLogic.php',
                'language' => 'php',
                'code_content' => "<?php\n\nnamespace App\Core;\n\nclass Kernel {\n    public function process(\$request) {\n        // Optimized via Vibe Coding\n        return response()->json(['status' => 'success']);\n    }\n}",
                'created_at' => $postDate,
            ]);

            // Ajouter des réactions reçues sur ces anciens posts
            $reviewers = User::where('id', '!=', $user->id)->inRandomOrder()->take(5)->pluck('id');
            foreach ($reviewers as $reviewerId) {
                Reaction::updateOrCreate(
                    [
                        'user_id' => $reviewerId,
                        'reactable_id' => $post->id,
                        'reactable_type' => Post::class,
                    ],
                    [
                        'type' => collect(['mindblown', 'upvote', 'optimisable'])->random(),
                        'created_at' => $postDate->subMinutes(rand(60, 1440)),
                    ]
                );
            }
        }

        // 3. POSTS RÉCENTS AVEC CONTENU RÉEL
        $p1 = Post::create([
            'user_id' => $user->id,
            'title' => 'High-Performance Cache Invalidation',
            'short_description' => 'How we achieved 40% reduction in TTFB using Selective Eager Loading.',
            'description' => 'A detailed analysis of Livewire computed properties and their impact on database pressure.',
            'visibility' => 'public',
            'lens' => 'performance',
            'created_at' => now()->subHours(5),
        ]);

        Snippet::create([
            'post_id' => $p1->id,
            'filename' => 'Profile.php',
            'language' => 'php',
            'code_content' => "public function getStatsProperty()\n{\n    return Cache::remember(\"user_stats_{\$this->user->id}\", 3600, function() {\n        return [\n            'karma' => \$this->user->reputation_score,\n            'posts' => \$this->user->posts()->count(),\n        ];\n    });\n}",
            'created_at' => $p1->created_at,
        ]);

        $p2 = Post::create([
            'user_id' => $user->id,
            'title' => 'Atomic Voting Security',
            'short_description' => 'Preventing race conditions in karma transactions.',
            'description' => 'Implementation of pessimistic locking and atomic increments for reputation integrity.',
            'visibility' => 'public',
            'lens' => 'security',
            'created_at' => now()->subDays(2),
        ]);

        Snippet::create([
            'post_id' => $p2->id,
            'filename' => 'VoteAction.php',
            'language' => 'php',
            'code_content' => "DB::transaction(function () use (\$user, \$points) {\n    \$user->lockForUpdate()->increment('reputation_score', \$points);\n    KarmaTransaction::create([\n        'user_id' => \$user->id,\n        'points' => \$points,\n    ]);\n});",
            'created_at' => $p2->created_at,
        ]);

        // 4. AJOUT DE REVIEWS SUR LES POSTS RÉCENTS
        $reviewer = User::where('id', '!=', $user->id)->first() ?? User::factory()->create();
        
        FullReview::create([
            'user_id' => $reviewer->id,
            'post_id' => $p1->id,
            'description' => 'L\'approche est solide, mais attention à l\'invalidation du cache lors des mises à jour de profil.',
            'score' => 85,
            'created_at' => now()->subHours(2),
        ]);

        $this->command->info('Profile enrichment completed for enriquep011.');
    }
}
