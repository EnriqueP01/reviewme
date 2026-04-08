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
                ['name' => 'test.php', 'content' => '<?php echo "hello";', 'language' => 'php', 'description' => 'Un fichier de test', 'is_duplicate' => false]
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
     * Test Artifacts V3: Multi-file import and telemetry.
     */
    public function test_artifacts_v3_multi_import_and_telemetry()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\PublishWorkflow::class)
            ->call('importMultipleFiles', [
                ['name' => 'core.php', 'content' => "<?php\n// line 2\n// line 3"],
                ['name' => 'style.css', 'content' => "body { color: red; }"],
            ])
            ->assertCount('files', 2)
            ->assertSet('files.0.name', 'core.php')
            ->assertSet('files.0.language', 'php')
            ->assertSet('files.1.name', 'style.css')
            ->assertSet('files.1.language', 'css');

        // Check stats telemetry
        $component = new \App\Livewire\PublishWorkflow();
        $component->files = [
            ['name' => 'core.php', 'content' => "Line 1\nLine 2\nLine 3"]
        ];
        $stats = $component->getFileStats(0);
        
        $this->assertEquals(3, $stats['lines']);
        $this->assertStringContainsString('B', $stats['size']);
    }

    /**
     * Test Artifacts V3: Duplicate detection.
     */
    public function test_duplicate_filename_detection()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\PublishWorkflow::class)
            ->set('files.0.name', 'file.php')
            ->call('addFile')
            ->call('importFile', 1, 'file.php', 'some content')
            ->assertSet('files.1.is_duplicate', true)
            ->assertSet('files.0.is_duplicate', true);
    }

    /**
     * Test Artifacts V3: Reordering files.
     */
    public function test_files_reordering()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\PublishWorkflow::class)
            ->set('files', [
                ['name' => 'A.php', 'content' => 'A'],
                ['name' => 'B.php', 'content' => 'B'],
                ['name' => 'C.php', 'content' => 'C'],
            ])
            ->call('reorderFiles', [2, 0, 1]) // C, A, B
            ->assertSet('files.0.name', 'C.php')
            ->assertSet('files.1.name', 'A.php')
            ->assertSet('files.2.name', 'B.php');
    }
}

