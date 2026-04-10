<?php

namespace App\Livewire\Traits;

trait HasVibeNotifications
{
    /**
     * Envoie une notification "Vibe" au frontend.
     */
    protected function notify(string $message, string $type = 'info', ?string $title = null): void
    {
        $this->dispatch('vibe-notif', [
            'type' => $type,
            'message' => $message,
            'title' => $title,
        ]);
    }

    protected function notifySuccess(string $message, ?string $title = null): void
    {
        $this->notify($message, 'success', $title);
    }

    protected function notifyError(string $message, ?string $title = null): void
    {
        $this->notify($message, 'error', $title);
    }

    protected function notifyInfo(string $message, ?string $title = null): void
    {
        $this->notify($message, 'info', $title);
    }
}
