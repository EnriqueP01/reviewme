<?php

declare(strict_types=1);

namespace App\Actions\Reviews;

use App\Models\Review;
use App\Models\User;

final class StoreReviewAction
{
    /**
     * Crée un nouveau commentaire sur un snippet.
     *
     * @param User $user
     * @param int $snippetId
     * @param int|null $lineNumber
     * @param string $content
     * @return Review
     */
    public function execute(User $user, int $snippetId, ?int $lineNumber, string $content): Review
    {
        return Review::create([
            'snippet_id' => $snippetId,
            'user_id' => $user->id,
            'line_number' => $lineNumber,
            'content' => $content,
        ]);
    }
}
