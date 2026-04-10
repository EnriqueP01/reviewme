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

        $data = [
            'github_id' => $githubUser->getId(),
            'name' => $githubUser->getNickname() ?? $githubUser->getName(),
            'avatar' => $githubUser->getAvatar(),
            'bio' => $githubUser->user['bio'] ?? null,
            'email_verified_at' => $user?->email_verified_at ?? now(),
        ];

        if (! $user) {
            $data['email'] = $githubUser->getEmail();
            $data['password'] = bcrypt(Str::random(24));

            // Génération d'un handle unique en cas de collision
            $handle = $baseHandle;
            $counter = 1;
            while (User::where('handle', $handle)->exists()) {
                $handle = $baseHandle.$counter++;
            }
            $data['handle'] = $handle;

            return User::create($data);
        }

        // Si l'utilisateur existe mais n'a pas de handle (cas rare)
        if (! $user->handle) {
            $handle = $baseHandle;
            $counter = 1;
            while (User::where('handle', $handle)->where('id', '!=', $user->id)->exists()) {
                $handle = $baseHandle.$counter++;
            }
            $data['handle'] = $handle;
        }

        $user->update($data);

        return $user;
    }
}
