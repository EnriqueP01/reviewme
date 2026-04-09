<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Log;

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
        $allowed = $user->id === $post->user_id;

        if (! $allowed) {
            Log::warning('Unauthorized deletion attempt', [
                'user_id' => $user->id,
                'post_id' => $post->id,
                'ip' => request()->ip(),
            ]);
        }

        return $allowed;
    }

    /**
     * Détermine si l'utilisateur peut voir un post privé.
     */
    public function view(User $user, Post $post): bool
    {
        if ($post->visibility === 'public') {
            return true;
        }

        if ($post->visibility === 'group' && $post->group_id) {
            $isMember = $user->id === $post->user_id
                || $post->group->members()->where('user_id', $user->id)->exists();

            if (! $isMember) {
                Log::warning('Access denied to group post', [
                    'user_id' => $user->id,
                    'post_id' => $post->id,
                    'group_id' => $post->group_id,
                ]);
            }

            return $isMember;
        }

        $allowed = $user->id === $post->user_id;

        if (! $allowed) {
            Log::warning('Access denied to private post', [
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
        }

        return $allowed;
    }
}
