<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class NotificationBell extends Component
{
    public $lastCount = 0;

    public function mount()
    {
        $this->lastCount = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
    }

    public function toggle()
    {
        $this->dispatch('toggle-notifications');
    }

    #[On('echo:notifications,NotificationSent')]
    public function refresh()
    {
        $currentCount = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
        
        if ($currentCount > $this->lastCount) {
            $this->dispatch('fx-play', type: 'notification');
            $this->dispatch('toast', message: __('You have a new notification!'), type: 'info');
        }
        
        $this->lastCount = $currentCount;
    }

    public function render()
    {
        $newCount = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
        
        // Unused for now, but good for future state tracking if needed
        // session(['last_unread_count' => $newCount]);

        return view('livewire.notification-bell', [
            'unreadCount' => $newCount,
        ]);
    }
}
