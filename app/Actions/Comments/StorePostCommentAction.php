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

        // NOTIFICATION LOGIC
        $post = \App\Models\Post::find($postId);
        $actionUrl = route('posts.detail', $postId) . '#comment-' . $comment->id;

        if ($parentId) {
            // It's a reply: notify parent author
            $parent = PostComment::find($parentId);
            if ($parent && $parent->user_id !== $user->id) {
                $parent->user->notify(new \App\Notifications\GeneralNotification(
                    title: __('New Reply'),
                    message: __(':name replied to your comment.', ['name' => $user->name]),
                    type: 'comment',
                    actionUrl: $actionUrl
                ));
            }
        } elseif ($post && $post->user_id !== $user->id) {
            // It's a direct comment: notify post author
            $post->user->notify(new \App\Notifications\GeneralNotification(
                title: __('New Comment'),
                message: __(':name commented on your post.', ['name' => $user->name]),
                type: 'comment',
                actionUrl: $actionUrl
            ));
        }

        return $comment;
    }
}
