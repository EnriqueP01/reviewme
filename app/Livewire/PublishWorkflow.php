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

    public function submit()
    {
        // Production logic would save to DB via an Action
        session()->flash('success', 'Your code has been submitted to the curators.');
        return redirect()->to(route('dashboard'));
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.publish-workflow');
    }
}
