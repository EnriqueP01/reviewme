<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ReviewMe') }} — The Digital Curator</title>
        <meta name="description" content="{{ __('Hand-picked code architecture insights and collaborative curation for elite developers.') }}">
        
        <!-- Open Graph / Social -->
        <meta property="og:type" content="website">
        <meta property="og:title" content="ReviewMe — The Digital Curator">
        <meta property="og:description" content="{{ __('Forge better code through collaborative curation.') }}">
        <meta name="theme-color" content="#bec2ff">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-surface text-on-surface font-sans antialiased selection:bg-primary selection:text-on-primary overflow-x-hidden">
        
        <!-- Animated Background Elements -->
        <x-ui.interactive-grid />

        <div class="relative min-h-screen flex flex-col z-10">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="py-16 px-6 max-w-7xl mx-auto w-full">
                    <div class="font-display text-5xl font-bold tracking-tight text-on-surface drop-shadow-sm">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-grow">
                {{ $slot }}
            </main>

            <x-ui.footer />
        </div>

        @livewireScripts
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('vibe-action', (event) => {
                    if (window.fx) {
                        window.fx.play(event.type);
                    }
                });
            });
        </script>

        <x-ui.toast />
    </body>
</html>
