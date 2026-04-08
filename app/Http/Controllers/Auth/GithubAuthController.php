<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GithubAuthController extends Controller
{
    /**
     * Redirige l'utilisateur vers la page d'authentification GitHub.
     */
    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Gère l'utilisateur de retour de GitHub.
     */
    public function callback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();

            $user = User::updateOrCreate([
                'github_id' => $githubUser->getId(),
            ], [
                'name' => $githubUser->getNickname() ?? $githubUser->getName(),
                'email' => $githubUser->getEmail(),
                'avatar' => $githubUser->getAvatar(),
                'bio' => $githubUser->user['bio'] ?? null,
                'password' => bcrypt(Str::random(24)),
                'email_verified_at' => now(), // On considère que GitHub a déjà vérifié l'email
            ]);

            Auth::login($user);

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('GitHub Auth Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Erreur lors de la connexion avec GitHub : ' . $e->getMessage());
        }
    }
}
