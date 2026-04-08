<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;
use Mockery;

class GithubAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_github_redirect_works()
    {
        $response = $this->get(route('login.github'));

        // On vérifie que ça redirige bien vers GitHub
        $response->assertRedirectContains('github.com/login/oauth/authorize');
    }

    public function test_github_callback_logs_in_user()
    {
        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
        $abstractUser->shouldReceive('getId')->andReturn('123456');
        $abstractUser->shouldReceive('getEmail')->andReturn('test@example.com');
        $abstractUser->shouldReceive('getNickname')->andReturn('testuser');
        $abstractUser->shouldReceive('getName')->andReturn('Test User');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://avatar.url');
        $abstractUser->user = ['bio' => 'A developer'];

        Socialite::shouldReceive('driver')->with('github')->andReturn(Mockery::mock('Laravel\Socialite\Contracts\Provider', [
            'user' => $abstractUser,
        ]));

        $response = $this->get('/auth/github/callback');

        $this->assertAuthenticated();
        
        $user = User::where('github_id', '123456')->first();
        $this->assertNotNull($user);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertNotNull($user->email_verified_at);

        $response->assertRedirect('/dashboard');
    }

    public function test_github_auth_fails_gracefully()
    {
        Socialite::shouldReceive('driver')->with('github')->andThrow(new \Exception('Mocked error'));

        $response = $this->get('/auth/github/callback');

        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
    }
}
