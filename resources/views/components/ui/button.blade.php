@props(['variant' => 'primary', 'size' => 'md', 'pill' => false, 'static' => false, 'loadingTarget' => null])

@php
    $baseClasses = 'inline-flex items-center justify-center font-display font-black transition-all duration-300 disabled:opacity-50 disabled:pointer-events-none relative overflow-hidden group active:scale-95 border uppercase tracking-[0.15em] select-none';
    
    // Border Radius Logic
    $radiusClass = $pill ? 'rounded-full' : 'rounded-[100px] hover:rounded-none';
    
    $variants = [
        'primary' => 'bg-primary btn-primary-fix border-primary/20 hover:border-primary shadow-[0_0_20px_rgba(190,194,255,0.2)] hover:shadow-[0_0_40px_rgba(190,194,255,0.4)]',
        'secondary' => 'bg-secondary btn-secondary-fix border-secondary/20 hover:border-secondary shadow-[0_0_20px_rgba(78,222,163,0.2)] hover:shadow-[0_0_40px_rgba(78,222,163,0.4)]',
        'danger' => 'bg-error text-white border-error/20 hover:border-error shadow-[0_0_20px_rgba(239,68,68,0.2)] hover:shadow-[0_0_40px_rgba(239,68,68,0.4)]',
        'ghost' => 'bg-transparent text-on-surface hover:bg-white/5 border-transparent hover:border-white/20',
        'outline' => 'bg-transparent text-primary border-primary/40 hover:border-primary hover:bg-primary/5',
    ];
    
    $sizes = [
        'sm' => 'px-6 py-2.5 text-[10px]',
        'md' => 'px-10 py-4 text-[12px]',
        'lg' => 'px-14 py-6 text-[14px]',
    ];
    
    $classes = $baseClasses . ' ' . $radiusClass . ' ' . $variants[$variant] . ' ' . $sizes[$size];
    $tag = $attributes->has('href') ? 'a' : 'button';

    // Determine the loading target for isolation:
    // Priority: explicit loadingTarget prop > wire:target attr > wire:click attr (simple method names only)
    $wireClick = $attributes->get('wire:click', '');
    $wireTarget = $attributes->get('wire:target', '');
    // Only use wire:click as target if it's a plain method name (no parens/dollar signs)
    $clickTarget = preg_match('/^[a-zA-Z_]+$/', $wireClick) ? $wireClick : '';
    $resolvedTarget = $loadingTarget ?: ($wireTarget ?: $clickTarget);
@endphp

<{{ $tag }} 
    x-data="{ 
        atX: 0, 
        atY: 0,
        txX: 0,
        txY: 0,
        attract(e) {
            const rect = $el.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            this.atX = (e.clientX - centerX) * 0.05;
            this.atY = (e.clientY - centerY) * 0.05;
            this.txX = (e.clientX - centerX) * -0.1;
            this.txY = (e.clientY - centerY) * -0.1;
        },
        reset() {
            this.atX = 0;
            this.atY = 0;
            this.txX = 0;
            this.txY = 0;
        }
    }"
    x-on:mousemove="!{{ $static ? 'true' : 'false' }} && attract($event)"
    x-on:mouseleave="reset()"
    :style="!{{ $static ? 'true' : 'false' }} ? `transform: translate(${atX}px, ${atY}px)` : ''"
    {{ $attributes->merge(['class' => $classes]) }}
>
    <!-- Complex Textured Background (Noise + Gradient) -->
    <div class="absolute inset-0 opacity-20 pointer-events-none" 
         style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\'0 0 256 256\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cfilter id=\'noiseFilter\'%3E%3CfeTurbulence type=\'fractalNoise\' baseFrequency=\'0.65\' numOctaves=\'3\' stitchTiles=\'stitch\'/%3E%3C/filter%3E%3Crect width=\'100%25\' height=\'100%25\' filter=\'url(%23noiseFilter)\'/%3E%3C/svg%3E'); background-blend-mode: soft-light;"></div>
    
    <!-- Hover Inner Grid Reaction -->
    <div class="absolute inset-0 opacity-0 group-hover:opacity-30 transition-all duration-500 pointer-events-none overflow-hidden rounded-[inherit]">
        <div class="absolute inset-[-100%] transition-transform duration-200 ease-out"
             :style="`transform: translate(${txX}px, ${txY}px) scale(1.2); background-image: radial-gradient(circle at 1px 1px, currentColor 1px, transparent 0); background-size: 16px 16px;`"></div>
    </div>
    
    <span class="relative z-10 flex items-center gap-2">
        @if($resolvedTarget)
            <span wire:loading wire:target="{{ $resolvedTarget }}" class="inline-flex shrink-0">
                <svg class="animate-spin h-3.5 w-3.5 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
        @endif
        {{ $slot }}
    </span>
</{{ $tag }}>

<style>
@keyframes shimmer {
    100% { transform: translateX(200%); }
}
</style>
