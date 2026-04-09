<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\Reactions\ToggleReactionAction;
use App\Models\Post;
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

        // 4. Action via le pattern Action-Domain
        $action = app(ToggleReactionAction::class);
        $action->execute($reactor, $post, 'clean');

        // 5. Vérification
        $author->refresh();
        $this->assertEquals(10, $author->reputation_score);
    }

    /**
     * Test that reputation stacks with multiple reactions.
     */
    public function test_reputation_stacks_with_multiple_reactions()
    {
        $author = User::factory()->create(['reputation_score' => 0]);
        $post = Post::factory()->create(['user_id' => $author->id]);
        $action = app(ToggleReactionAction::class);

        // 3 réacteurs différents
        User::factory()->count(3)->create()->each(function ($user) use ($action, $post) {
            $action->execute($user, $post, 'mindblown');
        });

        $author->refresh();
        $this->assertEquals(30, $author->reputation_score);
    }

    /**
     * Test that reputation is removed when reaction is toggled off.
     */
    public function test_reputation_is_removed_on_toggle_off()
    {
        $author = User::factory()->create(['reputation_score' => 0]);
        $post = Post::factory()->create(['user_id' => $author->id]);
        $reactor = User::factory()->create();
        $action = app(ToggleReactionAction::class);

        // Ajout
        $action->execute($reactor, $post, 'mindblown');
        $author->refresh();
        $this->assertEquals(10, $author->reputation_score);

        // Retrait (Toggle)
        $action->execute($reactor, $post, 'mindblown');
        $author->refresh();
        $this->assertEquals(0, $author->reputation_score);
    }

    /**
     * Test that reputation delta is correct on reaction switch.
     */
    public function test_reputation_delta_is_correct_on_switch()
    {
        $author = User::factory()->create(['reputation_score' => 0]);
        $post = Post::factory()->create(['user_id' => $author->id]);
        $reactor = User::factory()->create();
        $action = app(ToggleReactionAction::class);

        // Passage de 'optimisable' (-2) à 'mindblown' (+10)
        $action->execute($reactor, $post, 'optimisable');
        $author->refresh();
        $this->assertEquals(-2, $author->reputation_score);

        $action->execute($reactor, $post, 'mindblown');
        $author->refresh();
        $this->assertEquals(10, $author->reputation_score); // Delta (+12)
    }
}
