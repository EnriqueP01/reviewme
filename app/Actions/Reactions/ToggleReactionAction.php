<?php

declare(strict_types=1);

namespace App\Actions\Reactions;

use App\Models\Reaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

final class ToggleReactionAction
{
    /**
     * Ajoute, met à jour ou supprime (toggle) une réaction sur un modèle.
     * Si la réaction existante est du même type, elle est supprimée.
     *
     * @param User $user
     * @param Model $reactable
     * @param string $type
     * @return Reaction|null
     */
    public function execute(User $user, Model $reactable, string $type): ?Reaction
    {
        $existing = Reaction::where([
            'user_id' => $user->id,
            'reactable_id' => $reactable->getKey(),
            'reactable_type' => $reactable->getMorphClass(),
        ])->first();

        // Comportement de toggle : si c'est la même réaction, on l'enlève
        if ($existing && $existing->type === $type) {
            $existing->delete();
            return null;
        }

        // Sinon on crée ou on écrase avec le nouveau type
        return Reaction::updateOrCreate([
            'user_id' => $user->id,
            'reactable_id' => $reactable->getKey(),
            'reactable_type' => $reactable->getMorphClass(),
        ], [
            'type' => $type
        ]);
    }
}
