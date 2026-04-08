<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PublishWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a user can publish a vibe.
     */
    public function test_user_can_publish_vibe()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\PublishWorkflow::class)
            ->set('title', 'Ma nouvelle Vibe')
            ->set('description', 'Une super description')
            ->set('goal', 'Apprendre Laravel')
            ->set('files', [
                ['name' => 'test.php', 'content' => '<?php echo "hello";', 'language' => 'php']
            ])
            ->set('lens', 'elegant')
            ->call('submit')
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'title' => 'Ma nouvelle Vibe',
            'goal' => 'Apprendre Laravel',
            'lens' => 'elegant',
        ]);

        $this->assertDatabaseHas('snippets', [
            'code_content' => '<?php echo "hello";',
        ]);
    }

    /**
     * Test validation fails if fields are missing.
     */
    public function test_validation_fails_on_empty_fields()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\PublishWorkflow::class)
            ->set('title', '')
            ->call('submit')
            ->assertHasErrors(['title' => 'required']);
    }
}
