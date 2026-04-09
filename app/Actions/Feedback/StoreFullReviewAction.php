<?php

declare(strict_types=1);

namespace App\Actions\Feedback;

use App\Models\FullReview;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class StoreFullReviewAction
{
    /**
     * Crée une revue complète avec modifications de plusieurs fichiers.
     *
     * @param  array  $filesData  Format: [ ['snippet_id' => int, 'content' => string, 'description' => ?string], ... ]
     */
    public function execute(User $user, int $postId, string $description, array $filesData): FullReview
    {
        return DB::transaction(function () use ($user, $postId, $description, $filesData) {
            Log::info("Executing StoreFullReviewAction for user {$user->id} on post {$postId}");

            $fullReview = FullReview::create([
                'user_id' => $user->id,
                'post_id' => $postId,
                'description' => $description,
            ]);

            foreach ($filesData as $file) {
                // On ne stocke que si le contenu est différent ou spécifié
                $fullReview->modifiedSnippets()->create([
                    'snippet_id' => $file['snippet_id'],
                    'modified_content' => $file['content'],
                    'description' => $file['description'] ?? null,
                ]);
            }

            Log::info("FullReview created with ID: {$fullReview->id}");

            return $fullReview;
        });
    }
}
