<?php

declare(strict_types=1);

namespace App\Actions\Posts;

use App\Models\Post;
use App\Models\Snippet;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class CreatePostAction
{
    /**
     * Crée un nouveau Post ainsi que ses Snippets associés au sein d'une transaction.
     *
     * @param array{
     *     title: string,
     *     short_description?: string,
     *     description: string,
     *     review_goals?: string,
     *     improvement_goals?: string,
     *     visibility: string,
     *     group_id?: int,
     *     lens: string,
     *     files: array<int, array{content: string, language: string, name?: string, filename?: string, description?: string}>
     * } $data
     *
     * @throws ValidationException
     */
    public function execute(User $user, array $data): Post
    {
        Validator::make($data, [
            'title' => 'required|string|min:5|max:255',
            'description' => 'nullable|string',
            'visibility' => 'required|in:public,private,group',
            'lens' => 'required|string',
            'files' => 'required|array|min:1',
            'files.*.content' => 'required|string',
        ])->validate();

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
                    'filename' => $file['name'] ?? $file['filename'] ?? 'file_'.($index + 1),
                    'version_number' => 1,
                    'code_content' => $file['content'],
                    'description' => $file['description'] ?? null,
                    'language' => $file['language'],
                    'sort_order' => $index,
                ]);
            }

            $user->recordContribution();

            // Notify group members
            if ($post->group_id) {
                $group = $post->group;
                if ($group) {
                    $members = $group->members()->where('users.id', '!=', $user->id)->get();
                    foreach ($members as $member) {
                        $member->notify(new \App\Notifications\GeneralNotification(
                            title: __('New Lab Activity'),
                            message: __('A new post ":title" has been published in :group.', [
                                'title' => $post->title,
                                'group' => $group->name
                            ]),
                            type: 'info',
                            actionUrl: route('posts.show', $post->id)
                        ));
                    }
                }
            }

            return $post;
        });
    }
}
