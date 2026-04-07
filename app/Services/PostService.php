<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostService
{
    /**
     * Crée un nouveau snippet de code avec détection automatique (simulée).
     */
    public function createPost(array $data)
    {
        return Post::create([
            'user_id' => Auth::id(),
            'group_id' => $data['group_id'] ?? null,
            'content' => $data['content'],
            'intention' => $data['intention'],
            'language' => $data['language'] ?? 'plaintext',
            'is_certified' => false,
        ]);
    }

    /**
     * Certifie une réponse si l'utilisateur est un superviseur.
     */
    public function certifyReview(Post $post, $reviewId)
    {
        // Logique de vérification de rôle à implémenter (Middleware/Gate)
        $post->update(['is_certified' => true]);
    }
}
