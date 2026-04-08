<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class UpdateUserPasswordAction
{
    /**
     * Met à jour le mot de passe de l'utilisateur.
     *
     * @param User $user
     * @param string $newPassword
     * @return void
     */
    public function execute(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }
}
