<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Feed extends Component
{
    use WithPagination;

    public function render()
    {
        $posts = Post::with(['user', 'snippets'])
            ->where('visibility', 'public')
            ->latest()
            ->paginate(10);

        $perspectives = collect($posts->items())->map(function ($post) {
            $latestSnippet = $post->snippets->first();
            
            return [
                'id' => $post->id,
                'author' => $post->user->name ?? 'Curator anonyme',
                'points' => $post->user->reputation_score ?? 0,
                'time_ago' => $post->created_at->diffForHumans(),
                'title' => $post->title,
                'snippet' => $latestSnippet->code_content ?? '// No code available',
                'language' => $latestSnippet->language ?? 'javascript', // Prop attendue par le composant
                'type' => 'elegant', // Valeur par défaut pour le style (elegant, performance, readability)
            ];
        });

        return view('livewire.feed', [
            'perspectives' => $perspectives,
            'posts' => $posts
        ]);
    }
}
