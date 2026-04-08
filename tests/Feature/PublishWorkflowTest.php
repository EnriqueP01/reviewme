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
     * Test that a user can publish a vibe (Artifact).
     */
    public function test_user_can_publish_artifact()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\PublishWorkflow::class)
            // Step 1
            ->set('title', 'Ma nouvelle Vibe')
            ->set('short_description', 'Un résumé court et concis de l\'artefact.')
            ->set('review_goals', 'Améliorer la lisibilité du code.')
            ->set('improvement_goals', 'Réduire la complexité cyclomatique.')
            ->call('nextStep')
            ->assertSet('step', 2)
            // Step 2
            ->set('files', [
                ['name' => 'test.php', 'content' => '<?php echo "hello";', 'language' => 'php', 'description' => 'Un fichier de test']
            ])
            ->call('nextStep')
            ->assertSet('step', 3)
            // Step 3
            ->set('selectedLens', ['logic', 'clarity'])
            ->set('visibility', 'public')
            ->call('submit')
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'title' => 'Ma nouvelle Vibe',
            'short_description' => 'Un résumé court et concis de l\'artefact.',
        ]);

        $this->assertDatabaseHas('snippets', [
            'code_content' => e('<?php echo "hello";'), // On n'oublie pas le escaping fait dans l'Action
        ]);
    }

    /**
     * Test validation fails if fields are missing on step 1.
     */
    public function test_validation_fails_on_step_1()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\PublishWorkflow::class)
            ->set('title', 'Shor') // 4 caractères, min est 5
            ->call('nextStep')
            ->assertHasErrors(['title', 'short_description', 'review_goals', 'improvement_goals']);
    }
}
