<footer class="mt-20 border-t border-white/5 bg-[#08090f]/80 backdrop-blur-3xl pt-12 pb-8 overflow-hidden relative">
    <!-- Background Decor -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-primary/20 to-transparent"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-primary/5 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="max-w-[1400px] mx-auto px-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <!-- Brand -->
            <div class="col-span-1 md:col-span-1 space-y-8">
                <a href="/" class="flex items-center gap-4 group">
                    <x-ui.logo size="w-12 h-12" font="text-xl" />
                    <span class="font-display text-2xl font-black tracking-tighter text-on-surface">Review<span class="text-primary">Me</span></span>
                </a>
                <p class="text-xs text-on-surface-variant font-medium leading-relaxed opacity-40 max-w-xs">
                    {{ __('A professional platform for code review and architectural validation.') }}
                </p>
            </div>

            <!-- Navigation -->
            <div class="space-y-6">
                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary">{{ __('Navigation') }}</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('dashboard') }}" class="text-xs font-bold text-on-surface-variant hover:text-white transition-colors flex items-center gap-2 group"><span class="w-1 h-1 rounded-full bg-white/10 group-hover:bg-primary transition-colors"></span> {{ __('Global Feed') }}</a></li>
                    <li><a href="{{ route('groups') }}" class="text-xs font-bold text-on-surface-variant hover:text-white transition-colors flex items-center gap-2 group"><span class="w-1 h-1 rounded-full bg-white/10 group-hover:bg-primary transition-colors"></span> {{ __('Groups') }}</a></li>
                    <li><a href="{{ route('leaderboard') }}" class="text-xs font-bold text-on-surface-variant hover:text-white transition-colors flex items-center gap-2 group"><span class="w-1 h-1 rounded-full bg-white/10 group-hover:bg-primary transition-colors"></span> {{ __('Leaderboard') }}</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div class="space-y-6">
                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-secondary">{{ __('Resources') }}</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('docs') }}" class="text-xs font-bold text-on-surface-variant hover:text-white transition-colors flex items-center gap-2 group"><span class="w-1 h-1 rounded-full bg-white/10 group-hover:bg-secondary transition-colors"></span> {{ __('Documentation') }}</a></li>
                    <li><a href="{{ route('status') }}" class="text-xs font-bold text-on-surface-variant hover:text-white transition-colors flex items-center gap-2 group"><span class="w-1 h-1 rounded-full bg-white/10 group-hover:bg-secondary transition-colors"></span> {{ __('API Status') }}</a></li>
                    <li><a href="{{ route('changelog') }}" class="text-xs font-bold text-on-surface-variant hover:text-white transition-colors flex items-center gap-2 group"><span class="w-1 h-1 rounded-full bg-white/10 group-hover:bg-secondary transition-colors"></span> {{ __('Changelog') }}</a></li>
                </ul>
            </div>

            <!-- Stats & Meta -->
            <div class="space-y-6">
                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-on-surface/60">{{ __('Platform Statistics') }}</h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between bg-white/[0.02] border border-white/5 rounded-xl px-4 py-3">
                        <span class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant/40">{{ __('Uptime') }}</span>
                        <span class="text-[10px] font-mono font-bold text-emerald-400">99.98%</span>
                    </div>
                    <div class="flex items-center justify-between bg-white/[0.02] border border-white/5 rounded-xl px-4 py-3">
                        <span class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant/40">{{ __('Active Users') }}</span>
                        <span class="text-[10px] font-mono font-bold text-primary">1,248</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-8">
            <span class="text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/30">
                &copy; {{ date('Y') }} REVIEWME. ALL RIGHTS RESERVED.
            </span>
            <div class="flex items-center gap-8">
                <a href="{{ route('privacy') }}" class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant/40 hover:text-white transition-colors">{{ __('Privacy') }}</a>
                <a href="{{ route('terms') }}" class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant/40 hover:text-white transition-colors">{{ __('Terms') }}</a>
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_10px_#10b981]"></div>
            </div>
        </div>
    </div>
</footer>
