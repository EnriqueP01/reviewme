<?php

namespace App\Livewire;

use Livewire\Component;

class NotificationDrawer extends Component
{
    public $isOpen = false;

    protected $listeners = [
        'toggle-notifications' => 'toggle',
        'open-notifications' => 'open',
        'close-notifications' => 'close',
    ];

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
        if ($this->isOpen) {
            $this->dispatch('fx-play', type: 'open_drawer');
        }
    }

    public function open()
    {
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $notifications = auth()->check() 
            ? auth()->user()->notifications()->latest()->take(20)->get() 
            : collect();

        $unreadCount = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;

        return view('livewire.notification-drawer', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
}
