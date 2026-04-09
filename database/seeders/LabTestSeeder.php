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
            'name' => 'Dr. Neurone',
            'email' => 'directeur@lab.fr',
        ]);

        // 2. Create Operative
        $operative = User::factory()->create([
            'name' => 'Agent Dupont',
            'email' => 'dupont@lab.fr',
        ]);

        // 3. Create Lab
        $lab = Group::create([
            'name' => 'Unité d\'Intelligence Quantique',
            'slug' => 'unite-intelligence-quantique',
            'description' => 'Recherche sur les algorithmes récursifs à latence zéro.',
            'owner_id' => $director->id,
        ]);

        // 4. Attach members
        $lab->members()->attach($director->id, ['role' => 'moderator']);
        $lab->members()->attach($operative->id, ['role' => 'member']);

        // 5. Create Lab-only Artifact
        $artifact = Post::create([
            'user_id' => $director->id,
            'group_id' => $lab->id,
            'title' => 'Fuite de mémoire récursive dans BFS',
            'short_description' => 'Audit de la profondeur récursive de notre moteur IA.',
            'description' => 'Documentation technique complète de la fuite de mémoire observée au cycle 4.',
            'review_goals' => 'Identifier l\'échec de la condition d\'arrêt récursive.',
            'improvement_goals' => 'Optimiser l\'utilisation de la pile.',
            'visibility' => 'private',
            'lens' => 'performance,security',
        ]);

        Snippet::create([
            'post_id' => $artifact->id,
            'version_number' => 1,
            'code_content' => e('function bfs($node) { bfs($node); } // Fuite mémoire !'),
            'language' => 'php',
            'description' => 'La logique récursive problématique.',
        ]);

        // 6. Create Public Artifact
        $public = Post::create([
            'user_id' => $operative->id,
            'title' => 'Graphe Social Transparent',
            'short_description' => 'Implémentation publique de nœuds 3D.',
            'description' => 'Partage avec la communauté mondiale pour obtenir des retours sur l\'architecture.',
            'review_goals' => 'Vérifier l\'élégance du mappage des données.',
            'improvement_goals' => 'Des noms de variables plus clairs.',
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
