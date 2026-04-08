<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Reactions;

use App\Actions\Reactions\ToggleReactionAction;
use App\Actions\Reactions\UpdateUserReputationAction;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToggleReactionActionTest extends TestCase
{
    use RefreshDatabase;

    private ToggleReactionAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        // On laisse Laravel injecter les dépendances automatiquement pour tester l'orchestration réelle
        $this->action = app(ToggleReactionAction::class);
    }

    /** @test */
    public function it_adds_a_reaction_and_updates_reputation(): void
    {
        $voter = User::factory()->create();
        $author = User::factory()->create(['reputation_score' => 0]);
        $post = Post::factory()->create(['user_id' => $author->id]);

        $this->action->execute($voter, $post, 'mindblown');

        $this->assertDatabaseHas('reactions', [
            'user_id' => $voter->id,
            'reactable_id' => $post->id,
            'reactable_type' => Post::class,
            'type' => 'mindblown'
        ]);

        $this->assertEquals(10, $author->fresh()->reputation_score);
    }

    /** @test */
    public function it_removes_a_reaction_on_toggle_off(): void
    {
        $voter = User::factory()->create();
        $author = User::factory()->create(['reputation_score' => 10]);
        $post = Post::factory()->create(['user_id' => $author->id]);
        
        // Setup existing reaction
        Reaction::create([
            'user_id' => $voter->id,
            'reactable_id' => $post->id,
            'reactable_type' => Post::class,
            'type' => 'mindblown'
        ]);

        $this->action->execute($voter, $post, 'mindblown');

        $this->assertDatabaseMissing('reactions', [
            'user_id' => $voter->id,
            'reactable_id' => $post->id
        ]);

        $this->assertEquals(0, $author->fresh()->reputation_score);
    }

    /** @test */
    public function it_switches_reaction_type_and_calculates_delta(): void
    {
        $voter = User::factory()->create();
        $author = User::factory()->create(['reputation_score' => -2]); // Auteur d'un post jugé optimisable
        $post = Post::factory()->create(['user_id' => $author->id]);
        
        Reaction::create([
            'user_id' => $voter->id,
            'reactable_id' => $post->id,
            'reactable_type' => Post::class,
            'type' => 'optimisable'
        ]);

        // On change en 'mindblown' (Delta attendu: +12)
        $this->action->execute($voter, $post, 'mindblown');

        $this->assertEquals(10, $author->fresh()->reputation_score);
        $this->assertEquals('mindblown', $post->reactions()->first()->type);
    }
}
