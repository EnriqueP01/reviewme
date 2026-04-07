<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ReviewMe') }} — The Digital Curator</title>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-surface text-on-surface font-sans antialiased">
        <!-- Grid Texture Overlay -->
        <div class="fixed inset-0 pointer-events-none bg-grid opacity-[0.03] z-0"></div>

        <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 z-10">
            <div class="mb-12">
                <a href="/" class="flex flex-col items-center gap-4 group">
                    <div class="w-16 h-16 bg-primary/20 rounded-round-4 flex items-center justify-center transition-all duration-500 group-hover:scale-110 group-hover:bg-primary/30">
                        <span class="text-primary font-display font-bold text-3xl leading-none">R</span>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6">
                <x-ui.card tonal="container" class="border border-outline-variant/10">
                    {{ $slot }}
                </x-ui.card>
            </div>
            
            <div class="mt-8 text-on-surface-variant text-sm font-display">
                Built for the elite developers.
            </div>
        </div>
    </body>
</html>
