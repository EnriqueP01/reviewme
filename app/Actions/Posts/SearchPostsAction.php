<?php

declare(strict_types=1);

namespace App\Actions\Posts;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

final class SearchPostsAction
{
    /**
     * Exécute la recherche et le filtrage des posts.
     *
     * @param string $search
     * @param string $sort
     * @return Builder
     */
    public function execute(string $search = '', string $sort = 'recent'): Builder
    {
        $userId = Auth::id();
        
        $query = Post::with(['user', 'snippets'])
            ->withCount([
                'reactions as up_count' => fn($q) => $q->where('type', 'mindblown'),
                'reactions as down_count' => fn($q) => $q->where('type', 'optimisable')
            ])
            ->with(['reactions' => function($query) use ($userId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->whereRaw('1 = 0');
                }
            }])
            ->where(function ($query) use ($userId) {
                $query->where('visibility', 'public');
                if ($userId) {
                    $query->orWhere('user_id', $userId);
                }
            });

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('user', fn($qu) => $qu->where('name', 'like', '%' . $search . '%'));
            });
        }

        if ($sort === 'recent') {
            $query->latest();
        } else {
            $query->orderByRaw('(up_count - down_count) DESC');
        }

        return $query;
    }
}
