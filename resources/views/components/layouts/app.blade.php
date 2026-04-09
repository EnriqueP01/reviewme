<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ReviewMe') }} — The Digital Curator</title>

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
                <header class="py-16 px-6 max-w-7xl mx-auto w-full animate-fade-in-up">
                    <div class="font-display text-5xl font-bold tracking-tight text-on-surface drop-shadow-sm">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-grow animate-fade-in-up" style="animation-delay: 0.1s">
                {{ $slot }}
            </main>

            <x-ui.footer />

        </div>

        @livewireScripts
    </body>
</html>
