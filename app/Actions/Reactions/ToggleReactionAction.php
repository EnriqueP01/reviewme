<?php

declare(strict_types=1);

namespace App\Actions\Reactions;

use App\Models\FullReview;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Reaction;
use App\Models\Review;
use App\Models\Snippet;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class ToggleReactionAction
{
    public function __construct(
        private readonly UpdateUserReputationAction $updateReputation
    ) {}

    /**
     * Ajoute, met à jour ou supprime (toggle) une réaction sur un modèle.
     * Si la réaction existante est du même type, elle est supprimée.
     *
     * @param  User  $user  Celui qui réagit
     * @param  Model  $reactable  Le modèle reactable
     * @param  string  $type  Le type de réaction
     */
    public function execute(User $user, Model $reactable, string $type): ?Reaction
    {
        // PROTECTION KARMA : Seuls les "Contributeurs" peuvent voter DOWN
        if (in_array($type, ['optimisable', 'down']) && ! $user->hasKarmaPermission('post.vote_down')) {
            throw new \Exception(__('Niveau insuffisant pour voter DOWN. (Requis: Contributeur)'));
        }

        // On récupère l'auteur de la ressource pour mettre à jour sa réputation
        $author = null;
        if ($reactable instanceof Post) {
            $author = $reactable->user;
        } elseif ($reactable instanceof Snippet) {
            $author = $reactable->post->user;
        } elseif ($reactable instanceof PostComment) {
            $author = $reactable->user;
        } elseif ($reactable instanceof Review) {
            $author = $reactable->user;
        } elseif ($reactable instanceof FullReview) {
            $author = $reactable->user;
        }

        // PROTECTION : Interdiction de voter sur ses propres ressources
        if ($author && $author->id === $user->id) {
            return null;
        }

        return DB::transaction(function () use ($user, $reactable, $type, $author) {
            $existing = Reaction::where([
                'user_id' => $user->id,
                'reactable_id' => $reactable->getKey(),
                'reactable_type' => $reactable->getMorphClass(),
            ])->lockForUpdate()->first();

            // Cas 1 : Suppression (Toggle off)
            if ($existing && $existing->type === $type) {
                $existing->delete();
                if ($author) {
                    $this->updateReputation->execute($author, $type, 'remove', null, $reactable);
                }

                return null;
            }

            // Cas 2 : Changement de type (Switch)
            if ($existing && $existing->type !== $type) {
                $oldType = $existing->type;
                $existing->update(['type' => $type]);
                if ($author) {
                    // On passe l'ancien type pour un calcul de delta précis
                    $this->updateReputation->execute($author, $type, 'switch', $oldType, $reactable);
                }

                return $existing;
            }

            // Cas 3 : Ajout pur
            $reaction = Reaction::create([
                'user_id' => $user->id,
                'reactable_id' => $reactable->getKey(),
                'reactable_type' => $reactable->getMorphClass(),
                'type' => $type,
            ]);

            if ($author) {
                $this->updateReputation->execute($author, $type, 'add', null, $reactable);
            }

            return $reaction;
        });
    }
}
