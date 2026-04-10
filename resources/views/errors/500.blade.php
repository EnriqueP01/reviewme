<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'ReviewMe') }} — {{ __('Server Error') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-surface text-on-surface font-sans antialiased overflow-x-hidden">

        <x-ui.interactive-grid />

        <div class="relative min-h-screen flex flex-col items-center justify-center z-10 px-6">

            <div class="absolute w-[600px] h-[600px] rounded-full bg-rose-500/5 blur-[120px] pointer-events-none"></div>

            <div class="relative flex flex-col items-center text-center max-w-lg gap-8">

                <div class="flex items-center gap-3 px-4 py-2 rounded-full bg-rose-500/10 border border-rose-500/20">
                    <div class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-pulse"></div>
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-rose-400">HTTP 500</span>
                </div>

                <div class="relative select-none">
                    <span class="text-[200px] font-black leading-none tracking-tighter text-white/[0.03]">500</span>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-7xl font-black tracking-tighter text-on-surface">{{ __('Server Error') }}</span>
                    </div>
                </div>

                <p class="text-sm font-medium text-on-surface-variant/60 leading-relaxed max-w-sm">
                    {{ __('An unexpected error occurred. The issue has been logged. Please try again or contact support if the problem persists.') }}
                </p>

                <div class="flex items-center gap-4 flex-wrap justify-center">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="px-8 py-4 rounded-2xl bg-primary text-on-primary text-[10px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all">
                            {{ __('Back to Dashboard') }}
                        </a>
                    @else
                        <a href="{{ url('/') }}"
                           class="px-8 py-4 rounded-2xl bg-primary text-on-primary text-[10px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all">
                            {{ __('Go to Home') }}
                        </a>
                    @endauth
                    <a href="javascript:history.back()"
                       class="px-8 py-4 rounded-2xl bg-white/5 border border-white/10 text-on-surface-variant text-[10px] font-black uppercase tracking-widest hover:bg-white/10 hover:text-on-surface transition-all">
                        {{ __('Go Back') }}
                    </a>
                </div>

            </div>
        </div>

    </body>
</html>
