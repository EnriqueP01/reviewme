<?php

declare(strict_types=1);

namespace App\Actions\Posts;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class SearchPostsAction
{
    /**
     * Exécute la recherche et le filtrage des posts.
     */
    public function execute(string $search = '', string $sort = 'recent', ?int $groupId = null): Builder
    {
        $userId = Auth::id();
        $group_ids = $userId ? Auth::user()->groups()->pluck('groups.id') : collect();

        $query = Post::with(['user', 'latestSnippet', 'snippets' => function ($q) {
            $q->whereColumn('version_number', DB::raw('(select max(version_number) from snippets s2 where s2.post_id = snippets.post_id)'))
                ->orderBy('sort_order', 'asc');
        }])
            ->withCount([
                'reactions as up_count' => fn ($q) => $q->where('type', 'mindblown'),
                'reactions as down_count' => fn ($q) => $q->where('type', 'optimisable'),
                'fullReviews',
            ])
            ->with(['reactions' => function ($query) use ($userId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->whereRaw('1 = 0');
                }
            }])
            ->where(function ($query) use ($userId, $group_ids, $groupId) {
                if ($groupId) {
                    $query->where('group_id', $groupId);
                } else {
                    $query->where('visibility', 'public');
                    if ($userId) {
                        $query->orWhere('user_id', $userId)
                            ->orWhereIn('group_id', $group_ids);
                    }
                }
            });

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhereHas('user', fn ($qu) => $qu->where('name', 'like', '%'.$search.'%'));
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
