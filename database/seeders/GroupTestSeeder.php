<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Post;
use App\Models\Snippet;
use App\Models\User;
use Illuminate\Database\Seeder;

class GroupTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Director
        $director = User::factory()->create([
            'name' => 'Lead Architect',
            'email' => 'architect@reviewme.dev',
        ]);

        // 2. Create Developer
        $developer = User::factory()->create([
            'name' => 'Junior Developer',
            'email' => 'dev@reviewme.dev',
        ]);

        // 3. Create Group
        $group = Group::create([
            'name' => 'Core Architecture Team',
            'slug' => 'core-architecture-team',
            'description' => 'Collaboration on high-performance recursive algorithms and zero-latency engines.',
            'owner_id' => $director->id,
        ]);

        // 4. Attach members
        $group->members()->attach($director->id, ['role' => 'admin']);
        $group->members()->attach($developer->id, ['role' => 'member']);

        // 5. Create Group-only Post
        $post = Post::create([
            'user_id' => $director->id,
            'group_id' => $group->id,
            'title' => 'Recursive Memory Leak in BFS',
            'short_description' => 'Review of the recursive depth in our core engine.',
            'description' => 'Technical documentation of the memory leak observed during high-load stress tests.',
            'review_goals' => 'Identify the failure in recursive termination conditions.',
            'improvement_goals' => 'Optimize stack usage and prevent overflow.',
            'visibility' => 'private',
            'lens' => 'performance,security',
        ]);

        Snippet::create([
            'post_id' => $post->id,
            'version_number' => 1,
            'code_content' => 'function bfs($node) { bfs($node); } // Memory leak here!',
            'language' => 'php',
            'description' => 'Problematic recursive logic.',
        ]);

        // 6. Create Public Post
        $public = Post::create([
            'user_id' => $developer->id,
            'title' => 'Transparent Social Graph',
            'short_description' => 'Public implementation of 3D nodes.',
            'description' => 'Sharing with the global community to get feedback on architecture and performance.',
            'review_goals' => 'Verify the elegance of data mapping.',
            'improvement_goals' => 'Closer alignment with PSR-12 and cleaner variable naming.',
            'visibility' => 'public',
            'lens' => 'clean',
        ]);

        Snippet::create([
            'post_id' => $public->id,
            'version_number' => 1,
            'code_content' => 'class Node { public $links = []; }',
            'language' => 'php',
        ]);
    }
}
