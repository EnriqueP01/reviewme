<?php

declare(strict_types=1);

namespace App\Actions\Reviews;

use App\Models\Review;
use App\Models\User;

final class StoreReviewAction
{
    /**
     * Crée un nouveau commentaire sur un snippet.
     */
    public function execute(User $user, int $snippetId, ?int $lineNumber, string $content): Review
    {
        $review = Review::create([
            'snippet_id' => $snippetId,
            'user_id' => $user->id,
            'line_number' => $lineNumber,
            'content' => $content,
        ]);

        // Notify post author
        $snippet = \App\Models\Snippet::with('post.user')->find($snippetId);
        if ($snippet && $snippet->post->user_id !== $user->id) {
            $snippet->post->user->notify(new \App\Notifications\GeneralNotification(
                title: __('New Comment'),
                message: __(':name commented on your code.', ['name' => $user->name]),
                type: 'review',
                actionUrl: route('posts.show', $snippet->post_id)
            ));
        }

        return $review;
    }
}
