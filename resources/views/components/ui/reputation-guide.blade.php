@props(['model' => null])

@php
    $levels = config('karma.levels');
    $rewards = config('karma.rewards');
@endphp

<div x-data="{ open: @entangle($attributes->wire('model')) }"
     x-init="$watch('open', value => {
        if (value) { $dispatch('open-modal', 'karma-guide'); }
        else { $dispatch('close-modal', 'karma-guide'); }
     })"
     x-on:close.stop="open = false">
    
    <x-modal name="karma-guide" :show="false" maxWidth="5xl">
        <div class="relative p-8 md:p-12 overflow-hidden bg-[#0d0e12]">
            <!-- Effects -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary/10 blur-[120px] rounded-full pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-secondary/10 blur-[120px] rounded-full pointer-events-none"></div>

            <!-- Header -->
            <div class="relative z-10 flex items-center justify-between mb-12">
                <div>
                    <h2 class="font-display text-4xl font-black text-on-surface tracking-tight truncate flex items-center gap-4">
                        <span class="p-3 bg-primary/20 rounded-2xl text-primary shadow-lg shadow-primary/20">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </span>
                        {{ __('Karma & Reputation') }}
                    </h2>
                    <p class="mt-3 text-on-surface-variant text-lg font-medium opacity-60">
                        {{ __('Votre influence sur la plateforme est définie par vos contributions.') }}
                    </p>
                </div>
                <button @click="open = false" class="p-4 rounded-2xl bg-white/5 hover:bg-white/10 transition-colors text-on-surface-variant">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Levels Grid -->
            <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
                @foreach($levels as $key => $level)
                    <div class="group relative p-8 rounded-[2.5rem] bg-white/[0.03] border border-white/5 hover:border-primary/30 transition-all duration-500 hover:bg-white/[0.05] hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-6">
                            <div class="px-4 py-1.5 rounded-full bg-white/5 border border-white/5">
                                <span class="text-[10px] font-black uppercase tracking-widest {{ $level['color'] }}">
                                    {{ __($level['label']) }}
                                </span>
                            </div>
                            <span class="font-mono text-xs font-bold text-on-surface-variant opacity-40">
                                {{ $level['min_score'] }}+ XP
                            </span>
                        </div>

                        <ul class="space-y-3">
                            @foreach($level['permissions'] as $permission)
                                <li class="flex items-start gap-3 text-sm text-on-surface-variant opacity-80 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-4 h-4 mt-0.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>{{ __('karma.permissions.'.$permission) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <!-- Rewards Table -->
            <div class="relative z-10 glass-panel p-8 rounded-[2rem] border-subtle">
                <h3 class="font-display font-bold text-2xl text-on-surface mb-8 flex items-center gap-3">
                    <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Points de Réputation') }}
                </h3>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach($rewards as $action => $points)
                        <div class="p-5 rounded-2xl bg-white/5 border border-white/5 text-center group/reward hover:bg-white/[0.08] transition-all">
                            <span class="block text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-2 opacity-50 group-hover/reward:opacity-100 group-hover/reward:text-primary transition-all">
                                {{ __('karma.actions.'.$action) }}
                            </span>
                            <span class="block font-display font-black text-2xl @if($points > 0) text-primary @else text-rose-500 @endif">
                                {{ $points > 0 ? '+' : '' }}{{ $points }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Bottom Note -->
            <div class="relative z-10 mt-12 text-center">
                <p class="text-xs text-on-surface-variant italic opacity-40">
                    {{ __('Les scores sont recalculés périodiquement pour refléter votre activité réelle.') }}
                </p>
            </div>
        </div>
    </x-modal>
</div>
