<?php

namespace Tests\Feature;

use App\Livewire\PublishWorkflow;
use App\Models\User;
use App\Models\Group;
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
            ->test(PublishWorkflow::class)
            // Step 1
            ->set('title', 'Ma nouvelle Vibe')
            ->set('short_description', 'Un résumé court et concis de l\'artefact.')
            ->set('review_goals', 'Améliorer la lisibilité du code.')
            ->set('improvement_goals', 'Réduire la complexité cyclomatique.')
            ->call('nextStep')
            ->assertSet('step', 2)
            // Step 2
            ->set('files', [
                ['id' => 'test_1', 'name' => 'test.php', 'content' => '<?php echo "hello";', 'language' => 'php', 'description' => 'Un fichier de test', 'is_duplicate' => false],
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
            ->test(PublishWorkflow::class)
            ->call('importMultipleFiles', [
                ['name' => 'core.php', 'content' => "<?php\n// line 2\n// line 3"],
                ['name' => 'style.css', 'content' => 'body { color: red; }'],
            ])
            ->assertCount('files', 2)
            ->assertSet('files.0.name', 'core.php')
            ->assertSet('files.0.language', 'php')
            ->assertSet('files.1.name', 'style.css')
            ->assertSet('files.1.language', 'css');

        // Check stats telemetry
        $component = new PublishWorkflow;
        $component->files = [
            ['name' => 'core.php', 'content' => "Line 1\nLine 2\nLine 3"],
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
            ->test(PublishWorkflow::class)
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
            ->test(PublishWorkflow::class)
            ->set('files', [
                ['id' => 'f1', 'name' => 'A.php', 'content' => 'A', 'is_duplicate' => false],
                ['id' => 'f2', 'name' => 'B.php', 'content' => 'B', 'is_duplicate' => false],
                ['id' => 'f3', 'name' => 'C.php', 'content' => 'C', 'is_duplicate' => false],
            ])
            ->call('reorderFiles', [2, 0, 1]) // C, A, B
            ->assertSet('files.0.name', 'C.php')
            ->assertSet('files.1.name', 'A.php')
            ->assertSet('files.2.name', 'B.php');
    }

    /**
     * Test Artifacts V3: Moving files up and down directly.
     */
    public function test_files_move_up_down()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(\App\Livewire\PublishWorkflow::class)
            ->set('files', [
                ['id' => 'f1', 'name' => 'A.php', 'content' => 'A', 'is_duplicate' => false],
                ['id' => 'f2', 'name' => 'B.php', 'content' => 'B', 'is_duplicate' => false],
                ['id' => 'f3', 'name' => 'C.php', 'content' => 'C', 'is_duplicate' => false],
            ])
            ->call('moveUp', 1) // Moves B up to 0 -> B, A, C
            ->assertSet('files.0.name', 'B.php')
            ->assertSet('files.1.name', 'A.php')
            ->call('moveDown', 1) // Moves A down to 2 -> B, C, A
            ->assertSet('files.1.name', 'C.php')
            ->assertSet('files.2.name', 'A.php');
    }

    /**
     * Test that a user can publish a private artifact in a group.
     */
    public function test_user_can_publish_private_artifact_in_group()
    {
        $user = User::factory()->create();
        $group = Group::create([
            'name' => 'My Lab',
            'owner_id' => $user->id,
            'slug' => 'my-lab-1',
            'is_private' => true
        ]);
        $user->groups()->attach($group->id, ['role' => 'owner']);

        Livewire::actingAs($user)
            ->test(\App\Livewire\PublishWorkflow::class)
            ->set('title', 'Test Private Lab')
            ->set('short_description', 'Lab Description Minimum Length')
            ->set('review_goals', 'test goals 10 chars minimum')
            ->set('improvement_goals', 'improv goals 10 chars min')
            ->call('nextStep')
            ->assertSet('step', 2)
            ->set('files', [
                ['id' => 'f1', 'name' => 'test.php', 'content' => 'some code', 'language' => 'php', 'description' => '', 'is_duplicate' => false]
            ])
            ->call('nextStep')
            ->assertSet('step', 3)
            ->set('selectedLens', ['security'])
            ->set('visibility', 'private')
            ->set('groupId', $group->id)
            ->call('submit')
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'group_id' => $group->id,
            'title' => 'Test Private Lab',
            'visibility' => 'group',
        ]);
    }
}
