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
        $driver = Socialite::driver('github');

        if (app()->environment('local')) {
            $driver->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
        }

        return $driver->redirect();
    }

    /**
     * Gère l'utilisateur de retour de GitHub.
     */
    public function callback(HandleGithubCallbackAction $handleGithubCallback)
    {
        try {
            $driver = Socialite::driver('github');

            if (app()->environment('local')) {
                $driver->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
            }

            /** @var User $githubUser */
            $githubUser = $driver->user();

            $user = $handleGithubCallback->execute($githubUser);

            Auth::login($user);

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            Log::error('GitHub Auth Error: '.$e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect('/login')->with('error', 'GitHub Error: ' . $e->getMessage());
        }
    }
}
