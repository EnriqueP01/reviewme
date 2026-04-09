<?php

declare(strict_types=1);

namespace App\Actions\Posts;

use App\Models\Post;
use App\Models\Snippet;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class CreatePostAction
{
    /**
     * Crée un nouveau Post ainsi que ses Snippets associés au sein d'une transaction.
     *
     * @param array{
     *     title: string,
     *     description: string,
     *     visibility: string,
     *     goal: string,
     *     context: string,
     *     lens: string,
     *     files: array<int, array{content: string, language: string}>
     * } $data
     */
    public function execute(User $user, array $data): Post
    {
        return DB::transaction(function () use ($user, $data) {
            $post = Post::create([
                'user_id' => $user->id,
                'group_id' => $data['group_id'] ?? null,
                'title' => $data['title'],
                'short_description' => $data['short_description'] ?? null,
                'description' => $data['description'],
                'review_goals' => $data['review_goals'] ?? null,
                'improvement_goals' => $data['improvement_goals'] ?? null,
                'visibility' => $data['visibility'],
                'lens' => $data['lens'],
            ]);

            foreach ($data['files'] as $index => $file) {
                Snippet::create([
                    'post_id' => $post->id,
                    'version_number' => 1,
                    'code_content' => e($file['content']),
                    'description' => $file['description'] ?? null,
                    'language' => $file['language'] ?? 'php',
                    'sort_order' => $index,
                ]);
            }

            return $post;
        });
    }
}
