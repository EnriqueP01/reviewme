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
        // 1. Recherche prioritaire par github_id
        $user = User::where('github_id', $githubUser->getId())->first();

        // 2. Recherche par email si non trouvé par github_id
        if (! $user && $githubUser->getEmail()) {
            $user = User::where('email', $githubUser->getEmail())->first();
        }

        $baseHandle = $githubUser->getNickname() ? Str::slug($githubUser->getNickname(), '') : Str::slug($githubUser->getName(), '');

        if (! $user) {
            // 3. Création d'un nouvel utilisateur avec handle unique
            $handle = $baseHandle;
            $counter = 1;
            while (User::where('handle', $handle)->exists()) {
                $handle = $baseHandle.$counter++;
            }

            return User::create([
                'github_id' => $githubUser->getId(),
                'name' => $githubUser->getNickname() ?? $githubUser->getName(),
                'handle' => $handle,
                'email' => $githubUser->getEmail(),
                'avatar' => $githubUser->getAvatar(),
                'bio' => $githubUser->user['bio'] ?? null,
                'password' => bcrypt(Str::random(24)),
                'email_verified_at' => now(),
            ]);
        }

        // 4. Mise à jour de l'utilisateur existant
        $updateData = [
            'github_id' => $githubUser->getId(),
            'avatar' => $githubUser->getAvatar(),
            'bio' => $githubUser->user['bio'] ?? $user->bio,
        ];

        // Sécurité : si l'utilisateur n'a pas de handle on lui en génère un
        if (! $user->handle) {
            $handle = $baseHandle;
            $counter = 1;
            while (User::where('handle', $handle)->where('id', '!=', $user->id)->exists()) {
                $handle = $baseHandle.$counter++;
            }
            $updateData['handle'] = $handle;
        }

        $user->update($updateData);

        return $user;
    }
}
