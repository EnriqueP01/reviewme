@php
    $levels = config('karma.levels');
    $rewards = config('karma.rewards');
@endphp

<div class="w-full max-w-7xl mx-auto px-12 py-20">
    <div class="relative overflow-hidden bg-surface-container-lowest rounded-[3rem] border border-white/5 shadow-2xl">
        <!-- Effects -->
        <div class="absolute -top-24 -right-24 w-[40rem] h-[40rem] bg-primary/10 blur-[150px] rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-[40rem] h-[40rem] bg-secondary/10 blur-[150px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 p-12 md:p-20">
            <!-- Header -->
            <div class="text-center mb-24 max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/10 border border-primary/20 text-primary text-[10px] font-black uppercase tracking-[0.2em] mb-8">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    {{ __('Reputation System') }}
                </span>
                <h1 class="font-display text-6xl font-black text-on-surface tracking-tight mb-8">
                    {{ __('Karma & Reputation') }}
                </h1>
                <p class="text-on-surface-variant text-xl font-medium opacity-60 leading-relaxed">
                    {{ __('Sur ReviewMe, votre réputation n\'est pas qu\'un chiffre. C\'est la mesure de votre expertise et de la confiance que la communauté vous accorde.') }}
                </p>
            </div>

            <!-- Levels Section -->
            <div class="mb-24">
                <h2 class="font-display text-2xl font-black text-on-surface mb-12 flex items-center gap-4">
                    <span class="w-12 h-px bg-primary/30"></span>
                    {{ __('Les Paliers de Progression') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($levels as $key => $level)
                        <div class="group relative p-10 rounded-[3rem] bg-white/[0.02] border border-white/5 hover:border-primary/30 transition-all duration-700 hover:bg-white/[0.04]">
                            <div class="flex items-center justify-between mb-8">
                                <div class="px-5 py-2 rounded-2xl bg-white/5 border border-white/5 group-hover:bg-primary/10 transition-colors">
                                    <span class="text-xs font-black uppercase tracking-widest {{ $level['color'] }}">
                                        {{ __($level['label']) }}
                                    </span>
                                </div>
                                <span class="font-mono text-sm font-bold text-on-surface-variant opacity-40">
                                    {{ $level['min_score'] }}+ XP
                                </span>
                            </div>

                            <ul class="space-y-4">
                                @foreach($level['permissions'] as $permission)
                                    <li class="flex items-start gap-3 text-sm text-on-surface-variant opacity-60 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-5 h-5 mt-0.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>{{ __('karma.permissions.'.$permission) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Rewards Section -->
            <div>
                <h2 class="font-display text-2xl font-black text-on-surface mb-12 flex items-center gap-4">
                    <span class="w-12 h-px bg-secondary/30"></span>
                    {{ __('Comment gagner des points ?') }}
                </h2>
                <div class="glass-panel p-12 rounded-[2.5rem] border-subtle">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                        @foreach($rewards as $action => $points)
                            <div class="p-8 rounded-3xl bg-[#0d0e12]/40 border border-white/5 text-center group/reward hover:bg-white/[0.05] transition-all">
                                <span class="block text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-4 opacity-40 group-hover/reward:opacity-100 group-hover/reward:text-primary transition-all">
                                    {{ __('karma.actions.'.$action) }}
                                </span>
                                <span class="block font-display font-black text-4xl @if($points > 0) text-primary @else text-rose-500 @endif mb-2">
                                    {{ $points > 0 ? '+' : '' }}{{ $points }}
                                </span>
                                <span class="text-[9px] font-mono text-on-surface-variant/30 uppercase tracking-tighter">{{ __('XP Points') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="mt-20 pt-12 border-t border-white/5 text-center">
                <p class="text-sm text-on-surface-variant font-medium opacity-40 max-w-2xl mx-auto italic">
                    {{ __('Le Karma est recalculé en temps réel. Les comportements toxiques ou le spam peuvent entraîner une réduction permanente de votre score et des restrictions d\'accès.') }}
                </p>
            </div>
        </div>
    </div>
</div>
