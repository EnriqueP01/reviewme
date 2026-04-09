<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Reaction;
use App\Models\Snippet;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MasterTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the Main Users
        $users = [
            'master' => User::updateOrCreate(['email' => 'master@reviewme.com'], [
                'name' => 'Master Curator',
                'password' => Hash::make('password'),
                'reputation_score' => 2500,
                'bio' => 'Lead Architect @ Neural-X. I don\'t write code, I curate logic.',
                'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Master&backgroundColor=0d0e14',
            ]),
            'senior' => User::updateOrCreate(['email' => 'senior@reviewme.com'], [
                'name' => 'Senior Reviewer',
                'password' => Hash::make('password'),
                'reputation_score' => 1800,
                'bio' => 'Ex-Core Maintainer. Silence is code, code is risk.',
                'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Senior&backgroundColor=1a1b26',
            ]),
            'junior' => User::updateOrCreate(['email' => 'junior@reviewme.com'], [
                'name' => 'Junior Coder',
                'password' => Hash::make('password'),
                'reputation_score' => 150,
                'bio' => 'Exploring the intersection of AI and Frontend.',
                'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Junior&backgroundColor=24283b',
            ]),
        ];

        // 2. Create dynamic users for scale
        for ($i = 1; $i <= 10; $i++) {
            $users[] = User::create([
                'name' => "DevNode_$i",
                'email' => "dev_$i@network.com",
                'password' => Hash::make('password'),
                'reputation_score' => rand(50, 1000),
                'avatar' => "https://api.dicebear.com/7.x/avataaars/svg?seed=Dev$i",
            ]);
        }

        // 3. Create Posts logic
        $lenses = ['performance', 'logic', 'security', 'elegant', 'readability', 'clean', 'mindblown'];
        $langs = ['php', 'javascript', 'css', 'blade', 'html'];

        // --- HIGH QUALITY HAND-CRAFTED POSTS (Large, Medium, Small) ---

        // Large
        $p1 = Post::create([
            'user_id' => $users['master']->id,
            'title' => 'Quantum State Orchestrator',
            'short_description' => 'A complex state machine implementation for low-latency neural interfaces.',
            'description' => 'This artifact replaces legacy centralized managers with a decentralized, event-streamed approach.',
            'review_goals' => 'Analyze memory leaks and thread-safety.',
            'improvement_goals' => 'Optimize bitwise operations.',
            'context' => 'Neural-Core Project.',
            'visibility' => 'public',
            'lens' => 'performance,logic',
        ]);
        foreach (['Dispatcher.php' => 'php', 'Serializer.js' => 'javascript', 'HUD.css' => 'css'] as $desc => $lang) {
            Snippet::create(['post_id' => $p1->id, 'description' => $desc, 'language' => $lang, 'code_content' => "// Source code for $desc\n// Logic goes here..."]);
        }

        // Medium
        $p2 = Post::create([
            'user_id' => $users['senior']->id,
            'title' => 'Vault Security Protocol',
            'short_description' => 'Advanced JWT encryption and validation middleware.',
            'description' => 'Refactoring EdDSA signatures.',
            'review_goals' => 'Verify key rotation.',
            'context' => 'Admin portal.',
            'visibility' => 'public',
            'lens' => 'security,logic',
        ]);
        Snippet::create(['post_id' => $p2->id, 'description' => 'Auth.php', 'language' => 'php', 'code_content' => '// Security implementation...']);

        // Small
        $p3 = Post::create([
            'user_id' => $users['junior']->id,
            'title' => 'Smooth Cursor Glow',
            'short_description' => 'Mini fragment for hover effect.',
            'description' => 'A CSS-based cursor glow effect using radial gradients and variable injection.',
            'context' => 'Dashboard UI.',
            'visibility' => 'public',
            'lens' => 'elegant',
        ]);
        Snippet::create(['post_id' => $p3->id, 'description' => 'Glow.js', 'language' => 'javascript', 'code_content' => '// Hover logic...']);

        // 4. GENERATE 30+ ADDITIONAL POSTS
        for ($i = 1; $i <= 30; $i++) {
            $user = (array_values($users))[rand(0, count($users) - 1)];
            $lensSelection = [$lenses[rand(0, 6)]];
            if (rand(0, 3) > 2) {
                $lensSelection[] = $lenses[rand(0, 6)];
            } // sometimes 2 lenses

            $post = Post::create([
                'user_id' => $user->id,
                'title' => 'Artifact_Ref_'.strtoupper(bin2hex(random_bytes(3))),
                'short_description' => "Refactoring segment $i for improved ".$lensSelection[0].'.',
                'description' => "Deep dive into the structural challenges of node $i.",
                'review_goals' => 'Evaluate the '.$lensSelection[0].' metrics.',
                'context' => "Production module segment $i.",
                'visibility' => 'public',
                'lens' => implode(',', array_unique($lensSelection)),
            ]);

            $numSnippets = rand(1, 4);
            for ($j = 1; $j <= $numSnippets; $j++) {
                $lang = $langs[rand(0, 4)];
                Snippet::create([
                    'post_id' => $post->id,
                    'description' => "file_$j.$lang",
                    'language' => $lang,
                    'code_content' => "// Technical implementation $j\nfunction optimizeNode$i() {\n  return 0xFF;\n}",
                ]);
            }

            // Random reactions
            if (rand(0, 1)) {
                Reaction::create([
                    'user_id' => $users['master']->id,
                    'reactable_id' => $post->id,
                    'reactable_type' => Post::class,
                    'type' => $lenses[rand(0, 6)] === 'security' ? 'optimisable' : 'clean',
                ]);
            }
        }
    }
}
