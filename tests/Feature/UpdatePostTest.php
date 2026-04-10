<?php

namespace Tests\Feature;

use App\Livewire\UpdatePost;
use App\Models\Post;
use App\Models\Snippet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class UpdatePostTest extends TestCase
{
    use RefreshDatabase;

    public function test_author_can_access_update_page()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        Snippet::factory()->create(['post_id' => $post->id, 'version_number' => 1]);

        $response = $this->actingAs($user)->get(route('posts.update', $post->id));

        $response->assertStatus(200);
        $response->assertSeeLivewire(UpdatePost::class);
    }

    public function test_non_author_cannot_access_update_page()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $otherUser->id]);
        Snippet::factory()->create(['post_id' => $post->id, 'version_number' => 1]);

        $response = $this->actingAs($user)->get(route('posts.update', $post->id));

        $response->assertStatus(403);
    }

    public function test_author_can_submit_new_version()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $snippet1 = Snippet::factory()->create([
            'post_id' => $post->id,
            'version_number' => 1,
            'filename' => 'old.php',
            'code_content' => 'old logic',
        ]);

        Livewire::actingAs($user)
            ->test(UpdatePost::class, ['postId' => $post->id])
            ->set('files', [
                [
                    'id' => (string) Str::uuid(),
                    'name' => 'new.php',
                    'content' => 'new logic',
                    'language' => 'php',
                    'description' => 'Updated snippet',
                ],
            ])
            ->call('submit')
            ->assertRedirect(route('posts.detail', $post->id));

        $this->assertDatabaseHas('snippets', [
            'post_id' => $post->id,
            'version_number' => 2,
            'filename' => 'new.php',
            'code_content' => 'new logic',
            'description' => 'Updated snippet',
        ]);

        $this->assertDatabaseCount('snippets', 2);
    }
}
