<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\HandleGithubCallbackAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

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
    public function callback(HandleGithubCallbackAction $handleGithubCallback)
    {
        try {
            /** @var \Laravel\Socialite\Two\User $githubUser */
            $githubUser = Socialite::driver('github')->user();

            $user = $handleGithubCallback->execute($githubUser);

            Auth::login($user);

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            Log::error('GitHub Auth Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Erreur lors de la connexion avec GitHub : ' . $e->getMessage());
        }
    }
}
