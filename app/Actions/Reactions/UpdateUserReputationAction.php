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
     * @param  string  $type  Le type de réaction actuel (mindblown, optimisable, etc.)
     * @param  string  $action  'add' | 'remove' | 'switch'
     * @param  string|null  $oldType  L'ancien type de réaction (utile pour 'switch')
     */
    public function execute(User $user, string $type, string $action, ?string $oldType = null): void
    {
        $points = $this->getPointsForType($type);

        $delta = match ($action) {
            'add' => $points,
            'remove' => -$points,
            'switch' => $points - $this->getPointsForType($oldType ?? ''),
            default => 0,
        };

        if ($delta !== 0) {
            $user->increment('reputation_score', $delta);
        }
    }

    /**
     * Retourne les points associés à un type de réaction.
     */
    private function getPointsForType(string $type): int
    {
        return match ($type) {
            'mindblown', 'clean', 'security' => 10,
            'optimisable' => -2,
            default => 0,
        };
    }
}
