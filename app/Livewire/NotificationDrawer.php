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
        $user = auth()->user();
        $notifications = collect();
        $unreadCount = 0;
        $todayKarma = 0;

        if (auth()->check()) {
            $notifications = $user->notifications()->latest()->take(20)->get();
            $unreadCount = $user->unreadNotifications()->count();
            
            // Calculer le Karma gagné aujourd'hui
            $todayKarma = $user->karmaTransactions()
                ->whereDate('created_at', now()->today())
                ->where('points', '>', 0) // On ne compte que les gains pour le total positif
                ->sum('points');
        }

        return view('livewire.notification-drawer', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'todayKarma' => $todayKarma,
        ]);
    }
}
