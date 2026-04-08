<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Review;
use App\Models\Reaction;
use Livewire\Component;
use Livewire\Attributes\Layout;

class VibeDetail extends Component
{
    public Post $post;
    public $activeLine = null;
    public $commentContent = '';
    public $selectedVersion = null;

    public function mount($postId)
    {
        $this->post = Post::with(['user', 'snippets.reviews.user', 'reactions'])->findOrFail($postId);
        $this->selectedVersion = $this->post->snippets->first()->id;
    }

    public function selectLine($line)
    {
        $this->activeLine = $line;
    }

    public function saveComment()
    {
        $this->validate([
            'commentContent' => 'required|min:3',
        ]);

        Review::create([
            'snippet_id' => $this->selectedVersion,
            'user_id' => auth()->id(),
            'line_number' => $this->activeLine,
            'content' => $this->commentContent,
        ]);

        $this->commentContent = '';
        $this->activeLine = null;
        $this->post->load('snippets.reviews.user');
        
        session()->flash('message', 'Review ajoutée !');
    }

    public function react($type)
    {
        Reaction::updateOrCreate([
            'user_id' => auth()->id(),
            'reactable_id' => $this->post->id,
            'reactable_type' => Post::class,
        ], [
            'type' => $type
        ]);

        $this->post->load('reactions');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $snippet = $this->post->snippets()->where('id', $this->selectedVersion)->first();
        
        return view('livewire.vibe-detail', [
            'currentSnippet' => $snippet
        ]);
    }
}
