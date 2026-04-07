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
                'github_id' => $githubUser->id,
            ], [
                'name' => $githubUser->getNickname() ?? $githubUser->getName(),
                'email' => $githubUser->getEmail(),
                'avatar' => $githubUser->getAvatar(),
                'bio' => $githubUser->user['bio'] ?? null,
                // On génère un mot de passe aléatoire car Socialite n'en fournit pas
                'password' => bcrypt(Str::random(24)),
            ]);

            Auth::login($user);

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Erreur lors de la connexion avec GitHub.');
        }
    }
}
