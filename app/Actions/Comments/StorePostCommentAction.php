<?php

declare(strict_types=1);

namespace App\Actions\Comments;

use App\Models\PostComment;
use App\Models\User;

final class StorePostCommentAction
{
    /**
     * Crée un nouveau commentaire global ou une réponse.
     */
    public function execute(User $user, int $postId, string $content, ?int $parentId = null, ?int $fullReviewId = null): PostComment
    {
        $comment = PostComment::create([
            'user_id' => $user->id,
            'post_id' => $postId,
            'parent_id' => $parentId,
            'full_review_id' => $fullReviewId,
            'content' => $content,
        ]);

        $user->recordContribution();

        return $comment;
    }
}
