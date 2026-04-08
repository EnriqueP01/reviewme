<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Posts;

use App\Actions\Posts\CreatePostAction;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatePostActionTest extends TestCase
{
    use RefreshDatabase;

    private CreatePostAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CreatePostAction();
    }

    /**
     * @test
     */
    public function it_creates_a_post_with_snippets(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $data = [
            'title' => 'Test Artifact',
            'short_description' => 'A short test description.',
            'description' => 'A full test description of the artifact.',
            'visibility' => 'public',
            'lens' => 'clarity,logic',
            'files' => [
                [
                    'content' => '<?php echo "Hello World"; ?>',
                    'language' => 'php',
                    'description' => 'Initial snippet'
                ],
                [
                    'content' => 'console.log("Hello");',
                    'language' => 'javascript',
                    'description' => 'JS snippet'
                ]
            ]
        ];

        $post = $this->action->execute($user, $data);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals($user->id, $post->user_id);
        $this->assertEquals('Test Artifact', $post->title);
        $this->assertCount(2, $post->snippets);
        
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Test Artifact'
        ]);

        $this->assertDatabaseHas('snippets', [
            'post_id' => $post->id,
            'language' => 'php'
        ]);
        
        $this->assertDatabaseHas('snippets', [
            'post_id' => $post->id,
            'language' => 'javascript'
        ]);
    }
}
