<?php

namespace Tests\Feature;

use App\Actions\Posts\SearchPostsAction;
use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_view_group_they_do_not_belong_to(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();

        $group = Group::create([
            'name' => 'Secret Lab',
            'slug' => 'secret-lab',
            'owner_id' => $owner->id,
        ]);

        $this->actingAs($stranger);

        // On simule l'accès via la Policy
        $this->assertFalse($stranger->can('view', $group));
    }

    public function test_user_can_view_group_they_belong_to(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $group = Group::create([
            'name' => 'Collaborative Lab',
            'slug' => 'collab-lab',
            'owner_id' => $owner->id,
        ]);

        $group->members()->attach($member->id, ['role' => 'member']);

        $this->actingAs($member);
        $this->assertTrue($member->can('view', $group));
    }

    public function test_only_owner_can_delete_group(): void
    {
        $owner = User::factory()->create();
        $moderator = User::factory()->create();

        $group = Group::create([
            'name' => 'Fragile Lab',
            'slug' => 'fragile-lab',
            'owner_id' => $owner->id,
        ]);

        $group->members()->attach($moderator->id, ['role' => 'moderator']);

        $this->actingAs($moderator);
        $this->assertFalse($moderator->can('delete', $group));

        $this->actingAs($owner);
        $this->assertTrue($owner->can('delete', $group));
    }

    public function test_private_posts_are_invisible_to_non_lab_members(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();

        $group = Group::create([
            'name' => 'Invisible Lab',
            'slug' => 'invisible-lab',
            'owner_id' => $owner->id,
        ]);

        $post = Post::create([
            'user_id' => $owner->id,
            'group_id' => $group->id,
            'title' => 'Top Secret Code',
            'description' => 'Shhh',
            'visibility' => 'private',
            'lens' => 'security',
        ]);

        $this->actingAs($stranger);

        // On simule l'action de recherche pour vérifier le filtrage
        $action = new SearchPostsAction;
        $results = $action->execute()->get();

        $this->assertFalse($results->contains($post));
    }
}
