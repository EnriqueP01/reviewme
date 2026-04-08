<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

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

    public function submit()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'files' => 'required|array|min:1',
            'files.*.content' => 'required|string',
        ]);

        $post = \App\Models\Post::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'visibility' => $this->visibility,
            'goal' => $this->goal,
            'context' => $this->context,
            'lens' => $this->lens,
        ]);

        foreach ($this->files as $file) {
            \App\Models\Snippet::create([
                'post_id' => $post->id,
                'version_number' => 1,
                'code_content' => $file['content'],
                'language' => $file['language'] ?? 'php',
            ]);
        }

        session()->flash('success', 'Vibe publiée avec succès !');
        return redirect()->to(route('dashboard'));
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.publish-workflow');
    }
}
