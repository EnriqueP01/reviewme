<?php

declare(strict_types=1);

namespace App\Livewire\Groups;

use App\Events\GroupMessageSent;
use App\Livewire\Traits\HasVibeNotifications;
use App\Models\Group;
use App\Models\GroupMessage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class GroupChat extends Component
{
    use HasVibeNotifications;

    public Group $group;

    public string $newMessage = '';

    public function getListeners()
    {
        return [
            "echo-private:groups.{$this->group->id},GroupMessageSent" => 'render',
        ];
    }

    protected $rules = [
        'newMessage' => 'required|string|max:1000',
    ];

    public function sendMessage()
    {
        $this->validate();

        $message = $this->group->messages()->create([
            'user_id' => Auth::id(),
            'content' => $this->newMessage,
        ]);

        broadcast(new GroupMessageSent($message))->toOthers();

        $this->reset('newMessage');

        // Dispatch event for local UI scroll behavior
        $this->dispatch('message-sent');
    }

    public function deleteMessage(int $messageId)
    {
        $msg = GroupMessage::findOrFail($messageId);

        // Auth check: Admin of group or author of message
        if ($this->group->owner_id === Auth::id() || $msg->user_id === Auth::id()) {
            $msg->delete();
            $this->notifySuccess(__('Message supprimé.'));
        }
    }

    public function render()
    {
        $messages = $this->group->messages()
            ->with('user:id,name,profile_photo_path') // Optimization: Select only needed columns
            ->latest()
            ->take(50)
            ->get()
            ->reverse();

        return view('livewire.groups.group-chat', [
            'messages' => $messages,
        ]);
    }
}
