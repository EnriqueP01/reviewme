<div class="max-w-4xl mx-auto px-6 py-12">
    <!-- Back Button -->
    <div class="mb-8 hover-trigger">
        <x-ui.back-button fallback="{{ route('dashboard') }}" />
    </div>

    <div class="mb-16 flex items-center justify-between animate-in fade-in slide-in-from-top-8 duration-1000">
        <div class="space-y-2">
            <h1 class="text-4xl lg:text-5xl font-black tracking-tighter uppercase">{{ __('System Status') }}</h1>
            <p class="text-on-surface-variant italic">{{ __('Real-time health monitoring of the ReviewMe ecosystem.') }}</p>
        </div>
        <div class="flex items-center gap-3 px-6 py-3 rounded-2xl bg-emerald-400/5 border border-emerald-400/10 text-emerald-400">
            <div class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_20px_#10b981]"></div>
            <span class="text-xs font-black uppercase tracking-widest text-emerald-400">{{ __('All Systems Nominal') }}</span>
        </div>
    </div>

    <!-- Services Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-in fade-in slide-in-from-bottom-8 duration-1000 delay-200">
        <!-- Dashboard API -->
        <div class="glass-panel p-8 rounded-round-4 border border-white/5 space-y-6">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/40">{{ __('Service') }}</span>
                <span class="text-[9px] font-black uppercase tracking-widest text-emerald-400">{{ __('Live') }}</span>
            </div>
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold tracking-tight">{{ __('Platform API') }}</h3>
                <span class="font-mono text-xs text-on-surface-variant/60">99.98% {{ __('UPTIME') }}</span>
            </div>
            <div class="flex gap-1">
                @for($i=0; $i<30; $i++)
                    <div class="h-8 flex-1 rounded-sm @if($i === 12) bg-amber-400/40 @else bg-emerald-400/20 @endif hover:scale-110 transition-transform cursor-pointer" title="{{ now()->subDays(30-$i)->format('Y-m-d') }}"></div>
                @endfor
            </div>
        </div>

        <!-- Real-time Pulse (Reverb) -->
        <div class="glass-panel p-8 rounded-round-4 border border-white/5 space-y-6">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/40">{{ __('Service') }}</span>
                <span class="text-[9px] font-black uppercase tracking-widest text-emerald-400">{{ __('Live') }}</span>
            </div>
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold tracking-tight">{{ __('Websocket (Reverb)') }}</h3>
                <span class="font-mono text-xs text-on-surface-variant/60">24ms {{ __('LATENCY') }}</span>
            </div>
            <div class="flex items-center gap-1 overflow-hidden h-8">
                @for($i=0; $i<50; $i++)
                    <div class="w-1 bg-emerald-400/20 rounded-full" style="height: {{ rand(20, 100) }}%"></div>
                @endfor
            </div>
        </div>

        <!-- DB Engine -->
        <div class="glass-panel p-8 rounded-round-4 border border-white/5 space-y-6">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/40">{{ __('Storage') }}</span>
                <span class="text-[9px] font-black uppercase tracking-widest text-emerald-400">{{ __('Healthy') }}</span>
            </div>
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold tracking-tight">{{ __('PostgreSQL Instance') }}</h3>
                <span class="font-mono text-xs text-on-surface-variant/60">7.2% {{ __('LOAD') }}</span>
            </div>
        </div>

        <!-- Github Gateway -->
        <div class="glass-panel p-8 rounded-round-4 border border-white/5 space-y-6">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/40">{{ __('Authentication') }}</span>
                <span class="text-[9px] font-black uppercase tracking-widest text-emerald-400">{{ __('Connected') }}</span>
            </div>
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold tracking-tight">{{ __('Github OAuth') }}</h3>
                <span class="font-mono text-xs text-on-surface-variant/60">88ms {{ __('RTT') }}</span>
            </div>
        </div>
    </div>
</div>
