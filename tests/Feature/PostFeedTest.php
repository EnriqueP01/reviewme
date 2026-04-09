<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostFeedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that guests are redirected from the feed.
     */
    public function test_guests_are_redirected_from_feed()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test that authenticated users can see public posts in the feed.
     */
    public function test_authenticated_users_can_see_public_posts()
    {
        $user = User::factory()->create();

        $publicPost = Post::factory()->create([
            'title' => 'Vibe Publique',
            'visibility' => 'public',
        ]);

        $privatePostByOther = Post::factory()->create([
            'title' => 'Vibe Privée Secrète',
            'visibility' => 'private',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Vibe Publique');
        $response->assertDontSee('Vibe Privée Secrète');
    }

    /**
     * Test that authenticated users can see their own private posts.
     */
    public function test_authenticated_users_can_see_their_own_private_posts()
    {
        $user = User::factory()->create();

        $privatePost = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Ma Vibe Secrète',
            'visibility' => 'private',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Ma Vibe Secrète');
    }
}
