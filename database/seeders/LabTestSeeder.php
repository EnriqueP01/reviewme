<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Post;
use App\Models\Snippet;
use App\Models\User;
use Illuminate\Database\Seeder;

class LabTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Director
        $director = User::factory()->create([
            'name' => 'Dr. Neural',
            'email' => 'director@lab.com',
        ]);

        // 2. Create Operative
        $operative = User::factory()->create([
            'name' => 'Agent Smith',
            'email' => 'smith@lab.com',
        ]);

        // 3. Create Lab
        $lab = Group::create([
            'name' => 'Quantum Intelligence Unit',
            'slug' => 'quantum-intelligence-unit',
            'description' => 'Focusing on zero-latency recursive algorithms.',
            'owner_id' => $director->id,
        ]);

        // 4. Attach members
        $lab->members()->attach($director->id, ['role' => 'moderator']);
        $lab->members()->attach($operative->id, ['role' => 'member']);

        // 5. Create Lab-only Artifact
        $artifact = Post::create([
            'user_id' => $director->id,
            'group_id' => $lab->id,
            'title' => 'Recursive Memory Leak in BFS',
            'short_description' => 'Auditing the recursive depth of our neural engine.',
            'description' => 'Full technical documentation of the memory leak observed in cycle 4.',
            'review_goals' => 'Identify the recursion base case failure.',
            'improvement_goals' => 'Optimize stack usage.',
            'visibility' => 'private',
            'lens' => 'performance,security',
        ]);

        Snippet::create([
            'post_id' => $artifact->id,
            'version_number' => 1,
            'code_content' => e('function bfs($node) { bfs($node); } // Leak!'),
            'language' => 'php',
            'description' => 'The problematic recursion logic.',
        ]);

        // 6. Create Public Artifact
        $public = Post::create([
            'user_id' => $operative->id,
            'title' => 'Transparent Social Graph',
            'short_description' => 'A public implementation of 3D nodes.',
            'description' => 'Sharing with the global community for architectural feedback.',
            'review_goals' => 'Check the elegance of the data mapping.',
            'improvement_goals' => 'Cleaner variable naming.',
            'visibility' => 'public',
            'lens' => 'clarity',
        ]);

        Snippet::create([
            'post_id' => $public->id,
            'version_number' => 1,
            'code_content' => e('class Node { public $links = []; }'),
            'language' => 'php',
        ]);
    }
}
