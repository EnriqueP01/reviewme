<?php

declare(strict_types=1);

namespace App\Actions\Reactions;

use App\Models\KarmaTransaction;
use App\Models\User;
use App\Models\UserSkill;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

final class GrantKarmaAction
{
    /**
     * Accorde ou retire du Karma à un utilisateur avec traçabilité complète.
     *
     * @param  User  $user  L'utilisateur qui reçoit le Karma
     * @param  int  $points  Nombre de points (positif ou négatif)
     * @param  string  $type  Type d'action (post_upvote, review_bonus, etc.)
     * @param  Model|null  $source  L'objet source (Post, Review, etc.)
     * @param  string|null  $lens  La catégorie concernée (Security, Logic, etc.)
     * @param  string|null  $description  Commentaire optionnel
     */
    public function execute(
        User $user,
        int $points,
        string $type,
        ?Model $source = null,
        ?string $lens = null,
        ?string $description = null
    ): void {
        if ($points === 0) {
            return;
        }

        // ANTI-FARMING : Limite journalière de gain
        if ($points > 0 && $this->isAtDailyCap($user)) {
            return;
        }

        DB::transaction(function () use ($user, $points, $type, $source, $lens, $description) {
            // 1. Créer la transaction d'audit
            KarmaTransaction::create([
                'user_id' => $user->id,
                'points' => $points,
                'type' => $type,
                'description' => $description,
                'source_type' => $source ? $source->getMorphClass() : null,
                'source_id' => $source ? $source->id : null,
                'metadata' => $lens ? ['lens' => $lens] : null,
            ]);

            // 2. Mettre à jour le score global
            $user->increment('reputation_score', $points);

            // 3. Mettre à jour le score de compétence si applicable
            if ($lens) {
                UserSkill::updateOrCreate(
                    ['user_id' => $user->id, 'lens' => $lens],
                    ['score' => DB::raw("score + $points")]
                );
            }
        });

        // NOTIFICATION UI (Livewire Event)
        // Utilisation d'un check sécurisé pour éviter de casser les tests hors-contexte Livewire
        if ($points > 0 && class_exists(Livewire::class)) {
            try {
                Livewire::dispatch('karma-updated', [
                    'points' => $points,
                    'total' => $user->fresh()->reputation_score,
                    'type' => $type,
                ]);
            } catch (\Throwable $e) {
                // Ignore errors in non-livewire contexts (tests, console)
            }
        }
    }

    /**
     * Vérifie si l'utilisateur a atteint son plafond de gain quotidien (200 pts).
     */
    private function isAtDailyCap(User $user): bool
    {
        $dailyGain = KarmaTransaction::where('user_id', $user->id)
            ->where('points', '>', 0)
            ->whereDate('created_at', now()->toDateString())
            ->sum('points');

        return $dailyGain >= 200;
    }
}
