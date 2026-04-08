<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Models\User;

final class UpdateUserProfileAction
{
    /**
     * Exécute la mise à jour du profil utilisateur.
     *
     * @param User $user
     * @param array<string, mixed> $data
     * @return User
     */
    public function execute(User $user, array $data): User
    {
        // On remplit le modèle avec les données validées
        $user->fill($data);

        // Si l'email a été modifié, on invalide la vérification
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $user;
    }
}
