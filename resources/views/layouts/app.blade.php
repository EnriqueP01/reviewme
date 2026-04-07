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
    <body class="bg-surface text-on-surface font-sans antialiased selection:bg-primary selection:text-on-primary">
        <!-- Grid Texture Overlay -->
        <div class="fixed inset-0 pointer-events-none bg-grid opacity-[0.03] z-0"></div>

        <div class="relative min-h-screen flex flex-col z-10">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="py-12 px-6 max-w-7xl mx-auto w-full">
                    <div class="font-display text-4xl font-bold tracking-tight text-on-surface">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-grow">
                {{ $slot }}
            </main>

            <footer class="py-12 px-6 border-t border-outline-variant/10 text-on-surface-variant text-sm text-center">
                &copy; {{ date('Y') }} ReviewMe. Built for the elite.
            </footer>
        </div>

        @livewireScripts
    </body>
</html>
