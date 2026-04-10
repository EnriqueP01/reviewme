@props([
    'fallback' => null,
])

<button 
    onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ $fallback ?? url('/') }}'"
    @mouseenter="window.fx && window.fx.play('hover')"
    @click="window.fx && window.fx.play('click')"
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-3 px-5 py-2.5 rounded-2xl bg-surface-container/50 border border-white/5 text-on-surface-variant hover:text-on-surface hover:bg-white/10 hover:border-white/10 transition-all duration-300 group backdrop-blur-md shadow-sm hover:shadow-lg']) }}
>
    <div class="relative flex items-center justify-center w-6 h-6 rounded-full bg-surface-highest/50 group-hover:bg-primary/20 transition-colors">
        <svg class="w-3.5 h-3.5 group-hover:-translate-x-0.5 transition-transform text-current group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/>
        </svg>
    </div>
    <span class="text-[10px] font-black uppercase tracking-[0.2em] pt-0.5">{{ __('Back') }}</span>
</button>
