<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Snippet;
use App\Models\Review;
use App\Models\Reaction;
use App\Models\Group;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MasterTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the Main Users
        $master = User::updateOrCreate(
            ['email' => 'master@reviewme.com'],
            [
                'name' => 'Master Curator',
                'password' => Hash::make('password'),
                'reputation_score' => 750,
                'bio' => 'Architecte système spécialisé dans le Neural Link Paradigm.',
                'avatar' => 'https://ui-avatars.com/api/?name=Master+Curator&background=0D8ABC&color=fff',
            ]
        );

        $senior = User::updateOrCreate(
            ['email' => 'senior@reviewme.com'],
            [
                'name' => 'Senior Reviewer',
                'password' => Hash::make('password'),
                'reputation_score' => 1200,
                'bio' => 'Ancien ingénieur Core chez PHP. Je vois les bugs avant qu\'ils ne soient écrits.',
                'avatar' => 'https://ui-avatars.com/api/?name=Senior+Reviewer&background=6b46c1&color=fff',
            ]
        );

        $junior = User::updateOrCreate(
            ['email' => 'junior@reviewme.com'],
            [
                'name' => 'Junior Coder',
                'password' => Hash::make('password'),
                'reputation_score' => 45,
                'bio' => 'Apprend le métier. Aime les frameworks JS à la mode.',
                'avatar' => 'https://ui-avatars.com/api/?name=Junior+Coder&background=f6e05e&color=000',
            ]
        );

        // 2. Create Labs (Groups)
        $eliteForge = Group::updateOrCreate(
            ['slug' => 'elite-forge'],
            [
                'name' => 'The Elite Forge',
                'description' => 'Unité de recherche sur l\'optimisation algorithmique avancée.',
                'owner_id' => $master->id,
            ]
        );

        $neuralNet = Group::updateOrCreate(
            ['slug' => 'neural-network'],
            [
                'name' => 'The Neural Network',
                'description' => 'Collaboration globale sur les interfaces matricielles.',
                'owner_id' => $senior->id,
            ]
        );

        // Attach Users to Labs
        DB::table('group_user')->updateOrInsert(['group_id' => $eliteForge->id, 'user_id' => $master->id], ['role' => 'moderateur']);
        DB::table('group_user')->updateOrInsert(['group_id' => $eliteForge->id, 'user_id' => $senior->id], ['role' => 'membre']);
        
        DB::table('group_user')->updateOrInsert(['group_id' => $neuralNet->id, 'user_id' => $senior->id], ['role' => 'moderateur']);
        DB::table('group_user')->updateOrInsert(['group_id' => $neuralNet->id, 'user_id' => $master->id], ['role' => 'membre']);
        DB::table('group_user')->updateOrInsert(['group_id' => $neuralNet->id, 'user_id' => $junior->id], ['role' => 'membre']);

        // 3. Create Complex Posts for Master
        // Post 1: Multi-file Public Artifact
        $post1 = Post::create([
            'user_id' => $master->id,
            'title' => 'Neural Link Core V1',
            'short_description' => 'Implémentation du pont neuronal entre le DOM et l\'Audio API.',
            'description' => 'Cet artefact contient l\'orchestration complète du système HUD dynamique utilisé dans ReviewMe.',
            'review_goals' => 'Vérifier la latence audio et l\'efficacité de la grille interactive.',
            'improvement_goals' => 'Réduire le nombre de reflows CSS.',
            'visibility' => 'public',
            'lens' => 'performance,logic'
        ]);

        Snippet::create([
            'post_id' => $post1->id,
            'description' => 'Logique de synchronisation Audio',
            'code_content' => "class AudioManager {\n  constructor() {\n    this.ctx = new AudioContext();\n    this.osc = this.ctx.createOscillator();\n  }\n  playBeep() {\n    this.osc.start();\n  }\n}",
            'language' => 'javascript'
        ]);

        $snippetCSS = Snippet::create([
            'post_id' => $post1->id,
            'description' => 'Styles HUD Glassmorphism',
            'code_content' => ".hud-toast {\n  filter: backdrop-blur(10px);\n  background: rgba(255, 255, 255, 0.1);\n  border: 1px solid rgba(255, 255, 255, 0.2);\n}",
            'language' => 'css'
        ]);

        // Post 2: Shared in a Lab (Elite Forge)
        $post2 = Post::create([
            'user_id' => $master->id,
            'group_id' => $eliteForge->id,
            'title' => 'Secret Sorting Algorithm',
            'short_description' => 'Un algorithme de tri en O(log log n).',
            'description' => 'Algorithme expérimental pour les structures de données massives.',
            'visibility' => 'private',
            'lens' => 'logic'
        ]);

        Snippet::create([
            'post_id' => $post2->id,
            'code_content' => "function secretSort(arr) {\n  // Implementation restricted\n  return arr.sort();\n}",
            'language' => 'javascript'
        ]);

        // 4. Create a Post for the Junior that Master will review
        $postJunior = Post::create([
            'user_id' => $junior->id,
            'group_id' => $neuralNet->id,
            'title' => 'Mon premier essai en PHP',
            'short_description' => 'Besoin d\'aide pour gérer ma base de données.',
            'description' => 'J\'essaie de faire un forum mais c\'est très lent.',
            'visibility' => 'private',
            'lens' => 'logic'
        ]);

        $snippetJunior = Snippet::create([
            'post_id' => $postJunior->id,
            'code_content' => "foreach(\$users as \$user) {\n    \$posts = DB::query(\"SELECT * FROM posts WHERE user_id = \" . \$user->id);\n    // etc\n}",
            'language' => 'php'
        ]);

        // 5. Interactions (Reviews & Reactions)
        
        // Senior reviews Master's CSS
        Review::create([
            'snippet_id' => $snippetCSS->id,
            'user_id' => $senior->id,
            'line_number' => 2,
            'content' => 'Le backdrop-blur est très coûteux sur mobile, attention à la compatibilité Safari.'
        ]);

        // Master reviews Junior's PHP (Conversation start)
        Review::create([
            'snippet_id' => $snippetJunior->id,
            'user_id' => $master->id,
            'line_number' => 2,
            'content' => 'Attention au N+1 et aux injections SQL ! Utilise l\'ORM Eloquent.'
        ]);

        // Junior answers to Master (Simulated conversation)
        Review::create([
            'snippet_id' => $snippetJunior->id,
            'user_id' => $junior->id,
            'line_number' => 2,
            'content' => 'Merci Master ! Comment puis-je réécrire cela avec Eloquent ?'
        ]);

        // Reactions
        Reaction::create([
            'user_id' => $senior->id,
            'reactable_id' => $post1->id,
            'reactable_type' => Post::class,
            'type' => 'mindblown'
        ]);

        Reaction::create([
            'user_id' => $junior->id,
            'reactable_id' => $post1->id,
            'reactable_type' => Post::class,
            'type' => 'clean'
        ]);

        Reaction::create([
            'user_id' => $master->id,
            'reactable_id' => $postJunior->id,
            'reactable_type' => Post::class,
            'type' => 'optimisable'
        ]);
    }
}
