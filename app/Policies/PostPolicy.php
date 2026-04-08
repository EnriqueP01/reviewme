<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

final class PostPolicy
{
    /**
     * Détermine si l'utilisateur peut mettre à jour le post.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer le post.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Détermine si l'utilisateur peut voir un post privé.
     */
    public function view(User $user, Post $post): bool
    {
        if ($post->visibility === 'public') {
            return true;
        }

        return $user->id === $post->user_id;
    }
}
