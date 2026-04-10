<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Group;
use App\Models\User;

final class GroupPolicy
{
    /**
     * Détermine si l'utilisateur peut voir la liste des groupes (via l'interface Labs).
     */
    public function viewAny(User $user): bool
    {
        return true; // Tous les utilisateurs authentifiés peuvent accéder à l'interface Labs
    }

    /**
     * Détermine si l'utilisateur peut voir les détails d'un groupe spécifique.
     */
    public function view(User $user, Group $group): bool
    {
        return $group->owner_id === $user->id
            || $group->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Détermine si l'utilisateur peut créer un groupe.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Détermine si l'utilisateur peut modifier ou gérer le groupe (membres, etc.).
     */
    public function manage(User $user, Group $group): bool
    {
        $member = $group->members()
            ->where('user_id', $user->id)
            ->first();

        /** @var \App\Models\User|null $member */
        /** @var \Illuminate\Database\Eloquent\Relations\Pivot|null $pivot */
        $pivot = $member?->pivot;

        // On vérifie le rôle sur le pivot
        /** @phpstan-ignore-next-line */
        $userRole = $pivot?->role;

        return $group->owner_id === $user->id || $userRole === 'moderator';
    }

    /**
     * Détermine si l'utilisateur peut supprimer le groupe.
     */
    public function delete(User $user, Group $group): bool
    {
        return $group->owner_id === $user->id;
    }

    /**
     * Détermine si l'utilisateur peut ajouter un membre.
     */
    public function addMember(User $user, Group $group): bool
    {
        return $this->manage($user, $group);
    }

    /**
     * Détermine si l'utilisateur peut retirer un membre.
     */
    public function removeMember(User $user, Group $group, User $memberToRemove): bool
    {
        // On peut s'enlever soi-même, ou être retiré par un gestionnaire (Director/Moderator)
        // Mais on ne peut pas retirer le propriétaire
        if ($memberToRemove->id === $group->owner_id) {
            return false;
        }

        if ($user->id === $memberToRemove->id) {
            return true;
        }

        return $this->manage($user, $group);
    }
}
