<?php

namespace Tests\Unit\Actions\Posts;

use App\Actions\Posts\AddPostVersionAction;
use App\Models\Post;
use App\Models\Snippet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddPostVersionActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_adds_a_new_version_and_increments_version_number()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        // Setup initial version
        Snippet::factory()->create([
            'post_id' => $post->id,
            'version_number' => 1,
            'filename' => 'test.php',
        ]);

        $action = new AddPostVersionAction;

        $payload = [
            'files' => [
                [
                    'filename' => 'test_updated.php',
                    'content' => 'updated content',
                    'language' => 'php',
                    'description' => 'New version description',
                ],
            ],
        ];

        $action->execute($post, $payload);

        $this->assertDatabaseHas('snippets', [
            'post_id' => $post->id,
            'version_number' => 2,
            'filename' => 'test_updated.php',
            'code_content' => 'updated content',
            'language' => 'php',
            'description' => 'New version description',
            'sort_order' => 0,
        ]);

        $this->assertDatabaseCount('snippets', 2);
    }
}
