<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\GroupMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class GroupMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public GroupMessage $message
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('groups.'.$this->message->group_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'content' => $this->message->content,
            'user_id' => $this->message->user_id,
            'user_name' => $this->message->user->name,
            'user_avatar' => $this->message->user->profile_photo_url,
            'created_at' => $this->message->created_at->diffForHumans(),
        ];
    }
}
