<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Socialite\Two\User as SocialiteUser;

final class HandleGithubCallbackAction
{
    /**
     * Gère la création ou la mise à jour de l'utilisateur à partir des données GitHub.
     */
    public function execute(SocialiteUser $githubUser): User
    {
        // 1. Chercher par github_id d'abord
        $user = User::where('github_id', $githubUser->getId())->first();

        if (!$user && $githubUser->getEmail()) {
            // 2. Chercher par email pour lier un compte existant
            $user = User::where('email', $githubUser->getEmail())->first();
        }

        $githubHandle = $githubUser->getNickname() ? Str::slug($githubUser->getNickname(), '') : Str::slug($githubUser->getName(), '');

        if (!$user) {
            // 3. Si on crée un nouvel utilisateur, on vérifie si le handle github est déjà pris
            $finalHandle = $githubHandle;
            $count = 1;
            while (User::where('handle', $finalHandle)->exists()) {
                $finalHandle = $githubHandle . $count++;
            }
            
            $user = User::create([
                'github_id' => $githubUser->getId(),
                'name' => $githubUser->getNickname() ?? $githubUser->getName(),
                'handle' => $finalHandle,
                'email' => $githubUser->getEmail(),
                'avatar' => $githubUser->getAvatar(),
                'bio' => $githubUser->user['bio'] ?? null,
                'password' => bcrypt(Str::random(24)),
                'email_verified_at' => now(),
            ]);
        } else {
            // 4. Mise à jour de l'utilisateur existant (on ne touche pas au handle existant)
            $user->update([
                'github_id' => $githubUser->getId(),
                'avatar' => $githubUser->getAvatar(),
                'bio' => $githubUser->user['bio'] ?? $user->bio,
            ]);
        }

        return $user;
    }
}
