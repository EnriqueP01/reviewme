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
    <body class="bg-surface text-on-surface font-sans antialiased overflow-x-hidden selection:bg-primary/30"
          x-data="{ mouseX: 0, mouseY: 0 }"
          @mousemove.window="mouseX = ($event.clientX / window.innerWidth) * 100; mouseY = ($event.clientY / window.innerHeight) * 100; document.body.style.setProperty('--mouse-x', mouseX + '%'); document.body.style.setProperty('--mouse-y', mouseY + '%')">
        
        <!-- Deep Desktop Texture -->
        <div class="fixed inset-0 pointer-events-none z-0">
            <!-- Macro Atmospheric Glows -->
            <div class="absolute inset-0 bg-gradient-to-tr from-primary/5 via-transparent to-secondary/5 opacity-50"></div>
            
            <!-- Mouse Follower Light -->
            <div class="absolute w-[1000px] h-[1000px] rounded-full opacity-20 blur-[120px] pointer-events-none transition-all duration-300"
                 :style="`background: radial-gradient(circle at 50% 50%, var(--primary), transparent 70%); left: ${mouseX}%; top: ${mouseY}%; transform: translate(-50%, -50%);` "></div>
            
            <!-- Structural Lines -->
            <div class="absolute inset-0 bg-grid opacity-20"></div>
        </div>

        <div class="relative min-h-screen flex flex-col z-10 w-full transition-opacity duration-1000" x-init="$el.style.opacity = 1" style="opacity: 0">
            <!-- Global Identity Bar -->
            <div class="w-full flex justify-between items-center px-12 py-8 animate-fade-in-up">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-primary/20 rounded-lg flex items-center justify-center border border-primary/30 group-hover:scale-110 transition-transform">
                        <span class="text-primary font-display font-bold text-xl">R</span>
                    </div>
                    <span class="font-display font-bold text-xl tracking-tighter">ReviewMe</span>
                </a>
                
                <div class="flex items-center gap-8">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-xs font-black uppercase tracking-[0.2em] text-on-surface hover:text-primary transition-colors">Access</a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-black uppercase tracking-[0.2em] text-on-surface-variant hover:text-white transition-colors">SignIn</a>
                        <x-ui.button variant="primary" size="sm" onclick="window.location.href='{{ route('register') }}'">Join</x-ui.button>
                    @endauth
                </div>
            </div>

            <!-- Main Content Area -->
            <main class="flex-grow flex flex-col items-center justify-center px-6">
                {{ $slot }}
            </main>
            
            <div class="py-10 text-on-surface-variant text-[10px] font-display uppercase tracking-[0.3em] flex justify-center opacity-40 animate-fade-in-up" style="animation-delay: 0.2s">
                Advanced Artifact Evaluation Platform
            </div>
        </div>
    </body>
</html>
