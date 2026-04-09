<?php

declare(strict_types=1);

namespace App\Livewire\Groups;

use App\Models\Group;
use App\Models\GroupMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class GroupChat extends Component
{
    public Group $group;

    public string $message = '';

    protected $rules = [
        'message' => 'required|string|max:1000',
    ];

    public function sendMessage()
    {
        $this->validate();

        $this->group->messages()->create([
            'user_id' => Auth::id(),
            'content' => $this->message,
        ]);

        $this->reset('message');
    }

    public function render()
    {
        $messages = $this->group->messages()
            ->with('user')
            ->oldest() // Chat usually read top-to-bottom
            ->take(50)
            ->get();

        return view('livewire.groups.group-chat', [
            'messages' => $messages,
        ]);
    }
}
