<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

final class UpdateUserProfileAction
{
    /**
     * Exécute la mise à jour du profil utilisateur.
     *
     * @param  array<string, mixed>  $data
     */
    public function execute(User $user, array $data): User
    {
        // On gère la photo de profil
        if (isset($data['photo'])) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $data['profile_photo_path'] = $data['photo']->store('profile-photos', 'public');
            unset($data['photo']);
        }

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
