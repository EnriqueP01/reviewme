<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\HandleGithubCallbackAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;

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
            /** @var User $githubUser */
            $githubUser = Socialite::driver('github')->user();

            $user = $handleGithubCallback->execute($githubUser);

            Auth::login($user);

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            Log::error('GitHub Auth Error: '.$e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect('/login')->with('error', __('Authentication failed. Please try again or contact support.'));
        }
    }
}
