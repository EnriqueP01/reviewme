<?php

declare(strict_types=1);

namespace App\Actions\Reactions;

use App\Models\Reaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

final class ToggleReactionAction
{
    public function __construct(
        private readonly UpdateUserReputationAction $updateReputation
    ) {}

    /**
     * Ajoute, met à jour ou supprime (toggle) une réaction sur un modèle.
     * Si la réaction existante est du même type, elle est supprimée.
     *
     * @param User $user Celui qui réagit
     * @param Model $reactable Le post ou snippet reactable
     * @param string $type Le type de réaction
     * @return Reaction|null
     */
    public function execute(User $user, Model $reactable, string $type): ?Reaction
    {
        // On récupère l'auteur de la ressource pour mettre à jour sa réputation
        $author = null;
        if ($reactable instanceof \App\Models\Post) {
            $author = $reactable->user;
        } elseif ($reactable instanceof \App\Models\Snippet) {
            $author = $reactable->post->user;
        }

        $existing = Reaction::where([
            'user_id' => $user->id,
            'reactable_id' => $reactable->getKey(),
            'reactable_type' => $reactable->getMorphClass(),
        ])->first();

        // Cas 1 : Suppression (Toggle off)
        if ($existing && $existing->type === $type) {
            $existing->delete();
            if ($author) {
                $this->updateReputation->execute($author, $type, 'remove');
            }
            return null;
        }

        // Cas 2 : Changement de type (Switch)
        if ($existing && $existing->type !== $type) {
            $existing->update(['type' => $type]);
            if ($author) {
                $this->updateReputation->execute($author, $type, 'switch');
            }
            return $existing;
        }

        // Cas 3 : Ajout pur
        $reaction = Reaction::create([
            'user_id' => $user->id,
            'reactable_id' => $reactable->getKey(),
            'reactable_type' => $reactable->getMorphClass(),
            'type' => $type
        ]);

        if ($author) {
            $this->updateReputation->execute($author, $type, 'add');
        }

        return $reaction;
    }
}
