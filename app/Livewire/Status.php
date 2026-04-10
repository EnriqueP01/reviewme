<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Status extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        // 1. DB HEALTH & LATENCY
        $dbStatus = 'Healthy';
        $startTime = microtime(true);
        try {
            \DB::connection()->getPdo();
            $dbLatency = round((microtime(true) - $startTime) * 1000, 2);
        } catch (\Exception $e) {
            $dbStatus = 'Offline';
            $dbLatency = 0;
        }

        // 2. SYSTEM RESOURCES
        $memUsage = round(memory_get_usage(true) / 1024 / 1024, 2);
        
        // 3. AUTH STATUS
        $hasGithub = !empty(config('services.github.client_id'));

        // 4. STATS GLOBALES
        $activeUsers = \App\Models\User::count();
        $totalPosts = \App\Models\Post::count();

        return view('livewire.status', [
            'dbStatus' => $dbStatus,
            'dbLatency' => $dbLatency,
            'memUsage' => $memUsage,
            'hasGithub' => $hasGithub,
            'activeUsers' => $activeUsers,
            'totalPosts' => $totalPosts,
        ]);
    }
}
