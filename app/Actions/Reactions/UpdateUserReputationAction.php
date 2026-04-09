<?php

declare(strict_types=1);

namespace App\Actions\Reactions;

use App\Models\User;

final class UpdateUserReputationAction
{
    /**
     * Calcule et met à jour le score de réputation d'un utilisateur.
     *
     * @param  User  $user  L'utilisateur qui reçoit la réputation (auteur du post)
     * @param  string  $type  Le type de réaction (mindblown, optimisable)
     * @param  string  $action  'add' | 'remove' | 'switch'
     */
    public function execute(User $user, string $type, string $action): void
    {
        $points = match ($type) {
            'mindblown', 'clean', 'security' => 10,
            'optimisable' => -2,
            default => 0,
        };

        $delta = match ($action) {
            'add' => $points,
            'remove' => -$points,
            'switch' => $this->calculateSwitchDelta($type),
            default => 0,
        };

        $user->increment('reputation_score', $delta);
    }

    /**
     * Calcule le delta lors d'un changement de type de réaction.
     */
    private function calculateSwitchDelta(string $newType): int
    {
        // Si on passe de 'optimisable' (-2) à 'mindblown' (+10) : Delta = 10 - (-2) = 12
        // Si on passe de 'mindblown' (+10) à 'optimisable' (-2) : Delta = -2 - 10 = -12
        return $newType === 'mindblown' ? 12 : -12;
    }
}
