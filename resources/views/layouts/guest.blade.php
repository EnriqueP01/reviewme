<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ReviewMe') }} — Code Review Platform</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-surface text-on-surface font-sans antialiased overflow-x-hidden selection:bg-primary/30">
        
        <!-- Deep Desktop Texture: Unified Interactive Grid -->
        <x-ui.interactive-grid />

        <div class="relative min-h-screen flex flex-col z-10 w-full">
            <!-- Header -->
            <div class="w-full flex justify-between items-center px-12 py-6 animate-fade-in-up">
                <a href="/" class="flex items-center gap-4 group">
                    <x-ui.logo size="w-10 h-10" font="text-xl" />
                    <span class="font-display font-bold text-xl tracking-tighter">ReviewMe</span>
                </a>
                
                <div class="flex items-center gap-10">
                    <!-- Language Switcher: High Contrast Precision -->
                    <div class="relative flex items-center p-1 bg-surface-container-low/50 rounded-xl border border-white/5 backdrop-blur-md"
                         x-data="{ locale: '{{ app()->getLocale() }}' }">
                        <div class="absolute inset-y-1 transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] bg-primary rounded-lg shadow-[0_0_20px_rgba(190,194,255,0.2)]"
                             :class="locale === 'fr' ? 'translate-x-0 w-8' : 'translate-x-[36px] w-8'">
                        </div>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('lang', 'fr') }}" @click="locale = 'fr'" class="relative z-10 w-8 h-7 flex items-center justify-center text-[10px] font-black transition-colors {{ app()->getLocale() == 'fr' ? 'text-surface' : 'text-on-surface-variant hover:text-on-surface' }}">FR</a>
                            <a href="{{ route('lang', 'en') }}" @click="locale = 'en'" class="relative z-10 w-8 h-7 flex items-center justify-center text-[10px] font-black transition-colors {{ app()->getLocale() == 'en' ? 'text-surface' : 'text-on-surface-variant hover:text-on-surface' }}">EN</a>
                        </div>
                    </div>

                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-xs font-black uppercase tracking-[0.2em] text-on-surface hover:text-primary transition-colors">{{ __('Access') }}</a>
                    @else
                        <div class="flex items-center gap-6">
                            <a href="{{ route('login', ['mode' => 'login']) }}" class="text-xs font-black uppercase tracking-[0.2em] text-on-surface-variant hover:text-white transition-colors">{{ __('Sign In') }}</a>
                            <x-ui.button variant="primary" size="sm" href="{{ route('register', ['mode' => 'register']) }}">
                                {{ __('Join') }}
                            </x-ui.button>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Main Content Area: Compact & Scalable -->
            <main class="flex-grow flex flex-col items-center pt-8 px-6">
                {{ $slot }}
            </main>
            
            <!-- Shared Footer Component -->
            <x-ui.footer />
            
            @livewireScripts
        </div>
    </body>
</html>
