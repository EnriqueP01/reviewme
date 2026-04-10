<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function users_can_view_a_profile_by_handle()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'handle' => 'johndoe',
            'bio' => 'Sample bio content',
        ]);

        $response = $this->actingAs($user)
            ->get('/profile/johndoe');

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('johndoe');
        $response->assertSee('Sample bio content');
    }

    #[Test]
    public function users_can_update_their_profile_including_handle_and_bio()
    {
        $user = User::factory()->create([
            'handle' => 'oldhandle',
            'name' => 'Old Name',
        ]);

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'New Name',
            'handle' => 'new-handle',
            'bio' => 'New bio description',
            'email' => $user->email,
        ]);

        $response->assertRedirect('/settings');

        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new-handle', $user->handle);
        $this->assertEquals('New bio description', $user->bio);
    }

    /** @test */
    public function handles_must_be_unique()
    {
        User::factory()->create(['handle' => 'taken']);
        $user = User::factory()->create(['handle' => 'mine']);

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'Name',
            'handle' => 'taken',
            'email' => $user->email,
        ]);

        $response->assertSessionHasErrors('handle');
    }
}
