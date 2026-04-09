<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ReviewMe') }} — Code Review Platform</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
        <meta name="description" content="{{ __('Professional code architecture insights and collaborative reviews.') }}">
        
        <!-- Open Graph / Social -->
        <meta property="og:type" content="website">
        <meta property="og:title" content="ReviewMe — Code Review Platform">
        <meta property="og:description" content="{{ __('Improve your code through collaborative reviews.') }}">
        <meta property="og:image" content="{{ asset('images/og-cover.png') }}">
        <meta name="theme-color" content="#bec2ff">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="ReviewMe — Code Review Platform">
        <meta name="twitter:description" content="{{ __('Improve your code through collaborative reviews.') }}">
        <meta name="twitter:image" content="{{ asset('images/og-cover.png') }}">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-surface text-on-surface font-sans antialiased selection:bg-primary selection:text-on-primary overflow-x-hidden">
        
        <x-ui.global-loader />

        <!-- Animated Background Elements -->
        <x-ui.interactive-grid />
        <x-ui.keyboard-shortcuts />

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
                Livewire.on('post-action', (event) => {
                    if (window.fx) {
                        window.fx.play(event.type);
                    }
                });
            });
        </script>

        <x-ui.toast />
    </body>
</html>
