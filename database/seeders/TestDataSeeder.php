<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Snippet;
use App\Models\Review;
use App\Models\Reaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the Main Test User
        $user = User::updateOrCreate(
            ['email' => 'test@reviewme.com'],
            [
                'name' => 'Lucifer Curator',
                'password' => Hash::make('password'),
                'reputation_score' => 450,
                'bio' => 'Expert en refactorisation brutale et optimisations de bas niveau.',
                'avatar' => 'https://github.com/github.png',
            ]
        );

        // 2. Create another user to interact with Lucifer's posts
        $fan = User::updateOrCreate(
            ['email' => 'fan@reviewme.com'],
            ['name' => 'Admirateur Code', 'password' => Hash::make('password')]
        );

        // 3. Create Posts for Lucifer
        $posts = [
            [
                'title' => 'Optimisation du Garbage Collector en PHP 8.3',
                'description' => 'Une exploration des cycles de collecte pour les applications long-running.',
                'code' => "<?php\n\ngc_collect_cycles();\n// Force collection to free memory leaks in loop\nwhile(\$running) {\n    \$data = process_heavy_batch();\n    unset(\$data);\n    if (memory_get_usage() > \$limit) gc_collect_cycles();\n}",
                'language' => 'php'
            ],
            [
                'title' => 'Shader de distorsion CSS (The Lens Effect)',
                'description' => 'Implémentation d\'une grille déformable avec backdrop-filter.',
                'code' => ".lens-container {\n  backdrop-filter: blur(20px) contrast(1.2);\n  background: radial-gradient(circle at var(--x) var(--y), transparent 0%, rgba(0,0,0,0.5) 100%);\n  transform: perspective(1000px) rotateX(var(--rx));\n}",
                'language' => 'css'
            ],
            [
                'title' => 'Pattern de Middleware récursif',
                'description' => 'Comment gérer une chaîne de responsabilité sans fin.',
                'code' => "export const pipeline = (...funcs) => \n  val => funcs.reduce((v, f) => f(v), val);",
                'language' => 'javascript'
            ]
        ];

        foreach ($posts as $idx => $pData) {
            $post = Post::create([
                'user_id' => $user->id,
                'title' => $pData['title'],
                'description' => $pData['description'],
                'visibility' => 'public',
                'lens' => $idx % 2 == 0 ? 'performance' : 'elegant',
                'goal' => 'Optimiser la mémoire',
                'context' => 'Production Script'
            ]);

            $snippet = Snippet::create([
                'post_id' => $post->id,
                'code_content' => $pData['code'],
                'language' => $pData['language'],
                'version_number' => 1
            ]);

            // Add some reactions from the fan
            Reaction::create([
                'user_id' => $fan->id,
                'reactable_id' => $post->id,
                'reactable_type' => Post::class,
                'type' => 'mindblown'
            ]);

            // Add some reviews (comments)
            Review::create([
                'snippet_id' => $snippet->id,
                'user_id' => $fan->id,
                'line_number' => 4,
                'content' => 'Cette ligne est géniale, j\'adore l\'approche récursive !'
            ]);
        }

        // 4. Create a Post for the fan where Lucifer reacts
        $fanPost = Post::create([
            'user_id' => $fan->id,
            'title' => 'Ma première API Laravel',
            'description' => 'C\'est un peu brouillon, j\'aimerais des conseils.',
            'visibility' => 'public'
        ]);

        $fanSnippet = Snippet::create([
            'post_id' => $fanPost->id,
            'code_content' => "public function index() {\n    return User::all();\n}",
            'language' => 'php'
        ]);

        Reaction::create([
            'user_id' => $user->id,
            'reactable_id' => $fanPost->id,
            'reactable_type' => Post::class,
            'type' => 'optimisable'
        ]);
        
        Review::create([
            'snippet_id' => $fanSnippet->id,
            'user_id' => $user->id,
            'line_number' => 2,
            'content' => 'Attention au N+1 et à la pagination si tu as beaucoup d\'utilisateurs.'
        ]);
    }
}
