<?php

namespace Tests\Feature;

use App\Livewire\PostDetail;
use App\Models\Post;
use App\Models\Snippet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PostDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that user can view vibe details.
     */
    public function test_user_can_view_vibe()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'title' => 'Vibe de Test',
            'user_id' => $user->id,
        ]);
        Snippet::factory()->create(['post_id' => $post->id]);

        $response = $this->actingAs($user)->get(route('posts.detail', $post->id));

        $response->assertStatus(200);
        $response->assertSee('Vibe de Test');
    }

    /**
     * Test user can add a review comment.
     */
    public function test_user_can_add_review()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $snippet = Snippet::factory()->create(['post_id' => $post->id]);

        Livewire::actingAs($user)
            ->test(PostDetail::class, ['postId' => $post->id])
            ->set('activeSnippetId', $snippet->id)
            ->set('suggestingLine', 5)
            ->set('suggestingEndLine', 5)
            ->set('originalContent', 'old')
            ->set('suggestedContent', 'new')
            ->set('suggestionDescription', 'Ceci est une superbe review')
            ->call('saveInlineSuggestion')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('inline_suggestions', [
            'snippet_id' => $snippet->id,
            'user_id' => $user->id,
            'description' => 'Ceci est une superbe review',
            'line_number' => 5,
        ]);
    }

    /**
     * Test user can react to a vibe.
     */
    public function test_user_can_react()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        Snippet::factory()->create(['post_id' => $post->id]);

        Livewire::actingAs($user)
            ->test(PostDetail::class, ['postId' => $post->id])
            ->call('vote', $post->id, 'up');

        $this->assertDatabaseHas('reactions', [
            'reactable_id' => $post->id,
            'reactable_type' => Post::class,
            'user_id' => $user->id,
            'type' => 'mindblown',
        ]);
    }
}
