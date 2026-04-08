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
     * @param User $user
     * @param array{
     *     title: string,
     *     description: string,
     *     visibility: string,
     *     goal: string,
     *     context: string,
     *     lens: string,
     *     files: array<int, array{content: string, language: string}>
     * } $data
     * @return Post
     */
    public function execute(User $user, array $data): Post
    {
        return DB::transaction(function () use ($user, $data) {
            $post = Post::create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'visibility' => $data['visibility'],
                'goal' => $data['goal'],
                'context' => $data['context'],
                'lens' => $data['lens'],
            ]);

            foreach ($data['files'] as $file) {
                Snippet::create([
                    'post_id' => $post->id,
                    'version_number' => 1,
                    'code_content' => $file['content'],
                    'language' => $file['language'] ?? 'javascript',
                ]);
            }

            return $post;
        });
    }
}
