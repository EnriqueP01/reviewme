<?php

namespace Tests\Feature;

use App\Actions\Reactions\GrantKarmaAction;
use App\Actions\Reactions\ToggleReactionAction;
use App\Actions\Reactions\UpdateUserReputationAction;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class KarmaSystemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que le niveau change selon le score de réputation.
     */
    public function test_user_level_changes_with_reputation_score(): void
    {
        $user = User::factory()->create(['reputation_score' => 0]);
        $this->assertEquals('Apprenti', $user->karma_level['label']);

        $user->reputation_score = 15;
        $this->assertEquals('Contributeur', $user->karma_level['label']);

        $user->reputation_score = 150;
        $this->assertEquals('Reviewer Certifié', $user->karma_level['label']);
    }

    /**
     * Test que le downvote est bloqué pour un nouvel utilisateur.
     */
    public function test_downvote_is_blocked_for_low_karma_users(): void
    {
        $user = User::factory()->create(['reputation_score' => 0]);
        $post = Post::factory()->create();

        $action = app(ToggleReactionAction::class);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Niveau insuffisant pour voter DOWN');

        $action->execute($user, $post, 'down');
    }

    /**
     * Test que l'historique des transactions est bien enregistré.
     */
    public function test_karma_transactions_are_logged(): void
    {
        $author = User::factory()->create(['reputation_score' => 0]);
        $user = User::factory()->create(['reputation_score' => 50]); // Déjà contributeur
        $post = Post::factory()->create(['user_id' => $author->id]);

        $action = app(ToggleReactionAction::class);
        $action->execute($user, $post, 'up');

        $this->assertDatabaseHas('karma_transactions', [
            'user_id' => $author->id,
            'points' => 10,
            'type' => 'reaction_add',
        ]);

        $this->assertEquals(10, $author->fresh()->reputation_score);
    }

    /**
     * Test du middleware sur la route des groupes.
     */
    public function test_groups_route_is_protected_by_karma(): void
    {
        $user = User::factory()->create(['reputation_score' => 20]); // Trop bas pour 100 requis

        $response = $this->actingAs($user)->getJson('/groups');

        $response->assertStatus(403);
        $response->assertJsonPath('message', 'Vous devez être au moins \'Reviewer Certifié\' (100 Karma) pour effectuer cette action.');
    }

    /**
     * Test du Daily Cap (Max 200 pts/jour).
     */
    public function test_daily_karma_cap_is_enforced(): void
    {
        $user = User::factory()->create(['reputation_score' => 0]);

        // Simuler un gain massif (25 fois +10)
        for ($i = 0; $i < 25; $i++) {
            app(GrantKarmaAction::class)->execute(
                $user,
                10,
                'test_gain',
                description: "Gain #{$i}"
            );
        }

        // Le score doit être bloqué à 200 (et non 250)
        $this->assertEquals(200, $user->fresh()->reputation_score);
    }

    /**
     * Test du Bonus de Qualité (> 500 chars).
     */
    public function test_quality_bonus_is_applied_to_long_posts(): void
    {
        $author = User::factory()->create(['reputation_score' => 0]);
        $user = User::factory()->create(['reputation_score' => 50]);

        // Post très long
        $longPost = Post::factory()->create([
            'user_id' => $author->id,
            'description' => str_repeat('Contenu de qualité supérieure. ', 20), // ~600 chars
        ]);

        app(UpdateUserReputationAction::class)->execute($author, 'up', 'add', source: $longPost);

        // Normalement +10, mais ici doublé à +20
        $this->assertEquals(20, $author->fresh()->reputation_score);
    }

    /**
     * Test de la commande karma:rebuild.
     */
    public function test_rebuild_command_restores_integrity(): void
    {
        $user = User::factory()->create(['reputation_score' => 0]);

        // On crée une transaction manuelle de 50 pts
        $user->karmaTransactions()->create([
            'points' => 50,
            'type' => 'manual',
            'description' => 'Bonus manuel',
        ]);

        // On reset manuellement le score à 0
        $user->update(['reputation_score' => 0]);

        // On lance la commande
        Artisan::call('karma:rebuild', ['--user' => $user->id]);

        // Le score doit être remonté à 50
        $this->assertEquals(50, $user->fresh()->reputation_score);
    }

    /**
     * Test que le vote DOWN ne coûte rien au votant.
     */
    public function test_downvote_is_free_for_voter(): void
    {
        $author = User::factory()->create(['reputation_score' => 0]);
        $voter = User::factory()->create(['reputation_score' => 50]); // Déjà contributeur
        $post = Post::factory()->create(['user_id' => $author->id]);

        $action = app(ToggleReactionAction::class);
        $action->execute($voter, $post, 'down');

        // L'auteur perd des points (le downvote est à -2 dans la config ou le code)
        $this->assertEquals(-2, $author->fresh()->reputation_score);

        // LE VOTANT NE PERD RIEN
        $this->assertEquals(50, $voter->fresh()->reputation_score);
    }
}
