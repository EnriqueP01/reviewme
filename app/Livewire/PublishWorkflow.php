<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

use Illuminate\Support\Facades\Auth;

class PublishWorkflow extends Component
{
    public int $step = 1;
    
    // Step 1: Introspection
    public string $goal = '';
    public string $context = '';
    
    // Step 2: Files
    public array $files = [
        ['name' => 'index.js', 'content' => '', 'language' => 'javascript'],
    ];
    
    // Step 3: Lens
    public string $lens = 'elegant';

    public function addFile()
    {
        $this->files[] = ['name' => '', 'content' => '', 'language' => 'javascript'];
    }

    public function removeFile($index)
    {
        unset($this->files[$index]);
        $this->files = array_values($this->files);
    }

    public function nextStep()
    {
        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public string $title = '';
    public string $description = '';
    public string $visibility = 'public';

    public function submit(\App\Actions\Posts\CreatePostAction $createPost)
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'files' => 'required|array|min:1',
            'files.*.content' => 'required|string',
        ]);

        $createPost->execute(Auth::user(), [
            'title' => $this->title,
            'description' => $this->description,
            'visibility' => $this->visibility,
            'goal' => $this->goal,
            'context' => $this->context,
            'lens' => $this->lens,
            'files' => $this->files,
        ]);

        session()->flash('success', 'Vibe publiée avec succès !');
        return redirect()->to(route('dashboard'));
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.publish-workflow');
    }
}
