<?php

declare(strict_types=1);

namespace App\Actions\Reactions;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

final class UpdateUserReputationAction
{
    public function __construct(
        private readonly GrantKarmaAction $grantKarma
    ) {}

    /**
     * Calcule et met à jour le score de réputation d'un utilisateur.
     *
     * @param  User  $user  L'utilisateur qui reçoit la réputation
     * @param  string  $type  Le type de réaction
     * @param  string  $action  'add' | 'remove' | 'switch'
     * @param  string|null  $oldType  L'ancien type de réaction
     * @param  Model|null  $source  La source du vote (Post, etc.)
     */
    public function execute(User $user, string $type, string $action, ?string $oldType = null, ?Model $source = null): void
    {
        $points = $this->getPointsForType($type);
        $oldPoints = $oldType ? $this->getPointsForType($oldType) : 0;

        $delta = match ($action) {
            'add' => $points,
            'remove' => -$points,
            'switch' => $points - $oldPoints,
            default => 0,
        };

        if ($delta === 0) {
            return;
        }

        // BONUS DE QUALITÉ : Doubler les points si le contenu est riche (> 500 chars)
        if ($delta > 0 && $source && isset($source->description) && strlen((string) $source->description) > 500) {
            $delta *= 2;
        }

        // Détection de la Lens si la source est un Post
        $lens = null;
        if ($source instanceof Post) {
            $lens = $source->lens;
        }

        $this->grantKarma->execute(
            $user,
            $delta,
            "reaction_{$action}",
            $source,
            $lens,
            "Reaction {$type} ".($action === 'switch' ? "(from {$oldType})" : '').($delta > 20 ? ' [Quality Bonus]' : '')
        );
    }

    /**
     * Retourne les points associés à un type de réaction.
     */
    private function getPointsForType(string $type): int
    {
        return match ($type) {
            'mindblown', 'clean', 'security', 'up' => 10,
            'optimisable', 'down' => -2,
            default => 0,
        };
    }
}
