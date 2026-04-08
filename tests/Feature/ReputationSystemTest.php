<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReputationSystemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a user gains reputation when their post receives a reaction.
     */
    public function test_author_gains_reputation_on_reaction()
    {
        // 1. Création de l'auteur
        $author = User::factory()->create(['reputation_score' => 0]);

        // 2. Création du post
        $post = Post::factory()->create(['user_id' => $author->id]);

        // 3. Création du réacteur
        $reactor = User::factory()->create();

        // 4. Action : Le réacteur ajoute une réaction au post
        Reaction::factory()->create([
            'user_id' => $reactor->id,
            'reactable_id' => $post->id,
            'reactable_type' => Post::class,
            'type' => 'clean'
        ]);

        // 5. Vérification : Le score de l'auteur doit être de 10
        $author->refresh();
        $this->assertEquals(10, $author->reputation_score, "Le score de réputation de l'auteur devrait être de 10 après un like.");
    }

    /**
     * Test that multiple reactions stack up reputation.
     */
    public function test_reputation_stacks_with_multiple_reactions()
    {
        $author = User::factory()->create(['reputation_score' => 0]);
        $post = Post::factory()->create(['user_id' => $author->id]);

        // 3 réactions
        Reaction::factory()->count(3)->create([
            'reactable_id' => $post->id,
            'reactable_type' => Post::class,
        ]);

        $author->refresh();
        $this->assertEquals(30, $author->reputation_score);
    }
}
