<?php

declare(strict_types=1);

namespace App\Actions\Posts;

use App\Models\Post;
use App\Models\Snippet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class AddPostVersionAction
{
    /**
     * Ajoute une nouvelle version de snippets au code existant.
     *
     * @param array{
     *     files: array<int, array{filename: string, content: string, language: string, description?: string}>
     * } $data
     *
     * @throws ValidationException
     */
    public function execute(Post $post, array $data): void
    {
        Validator::make($data, [
            'files' => 'required|array|min:1',
            'files.*.content' => 'required|string',
            'files.*.filename' => 'required|string',
        ])->validate();

        DB::transaction(function () use ($post, $data) {
            $latestVersion = Snippet::where('post_id', $post->id)->max('version_number') ?? 0;
            $newVersion = (int) $latestVersion + 1;

            foreach ($data['files'] as $index => $file) {
                Snippet::create([
                    'post_id' => $post->id,
                    'filename' => $file['filename'] ?? $file['name'] ?? 'file_'.($index + 1),
                    'version_number' => $newVersion,
                    'code_content' => $file['content'],
                    'description' => $file['description'] ?? null,
                    'language' => $file['language'] ?? 'php',
                    'sort_order' => $index,
                ]);
            }
        });
    }
}
