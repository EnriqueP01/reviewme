<?php

declare(strict_types=1);

namespace App\Actions\Reviews;

use App\Models\FullReview;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class StoreFullReviewAction
{
    /**
     * Crée une review complète avec modifications de plusieurs fichiers.
     *
     * $filesData: [ ['snippet_id' => 1, 'content' => '...', 'description' => '...'], ... ]
     */
    public function execute(User $user, int $postId, string $description, array $filesData): FullReview
    {
        return DB::transaction(function () use ($user, $postId, $description, $filesData) {
            $fullReview = FullReview::create([
                'user_id' => $user->id,
                'post_id' => $postId,
                'description' => $description,
            ]);

            foreach ($filesData as $file) {
                $fullReview->modifiedSnippets()->create([
                    'snippet_id' => $file['snippet_id'],
                    'modified_content' => $file['content'],
                    'description' => $file['description'] ?? null,
                ]);
            }

            $user->recordContribution();

            // Notify post author
            $post = \App\Models\Post::find($postId);
            if ($post && $post->user_id !== $user->id) {
                $post->user->notify(new \App\Notifications\GeneralNotification(
                    title: __('New Expert Review'),
                    message: __(':name has just posted a full architectural review on your post.', ['name' => $user->name]),
                    type: 'review',
                    actionUrl: route('posts.detail', $post->id)
                ));
            }

            return $fullReview;
        });
    }
}
