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
        return User::updateOrCreate([
            'github_id' => $githubUser->getId(),
        ], [
            'name' => $githubUser->getNickname() ?? $githubUser->getName(),
            'handle' => $githubUser->getNickname() ? Str::slug($githubUser->getNickname(), '') : Str::slug($githubUser->getName(), ''),
            'email' => $githubUser->getEmail(),
            'avatar' => $githubUser->getAvatar(),
            'bio' => $githubUser->user['bio'] ?? null,
            'password' => bcrypt(Str::random(24)),
            'email_verified_at' => now(),
        ]);
    }
}
