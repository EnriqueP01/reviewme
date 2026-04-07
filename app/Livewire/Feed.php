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
        return view('livewire.feed', [
            'posts' => Post::with(['user', 'snippets'])
                ->where('visibility', 'public')
                ->latest()
                ->paginate(10)
        ]);
    }
}
