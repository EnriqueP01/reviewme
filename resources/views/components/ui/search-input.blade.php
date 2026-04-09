@props([
    'placeholder' => __('Search...'),
    'model' => null,
    'debounce' => '300ms',
    'class' => '',
    'containerClass' => '',
])

<div class="relative group/search-ui {{ $containerClass }} bg-black/20 backdrop-blur-3xl rounded-[1.25rem] border border-white/5 shadow-2xl focus-within:border-primary/40 focus-within:shadow-[0_0_30px_rgba(190,194,255,0.05)] transition-all duration-500 flex items-center px-6">
    <!-- Texture Layer (Monolith Style) -->
    <div class="absolute inset-0 opacity-10 pointer-events-none rounded-[inherit]" 
         style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\'0 0 256 256\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cfilter id=\'noiseFilter\'%3E%3CfeTurbulence type=\'fractalNoise\' baseFrequency=\'0.65\' numOctaves=\'3\' stitchTiles=\'stitch\'/%3E%3C/filter%3E%3Crect width=\'100%25\' height=\'100%25\' filter=\'url(%23noiseFilter)\'/%3E%3C/svg%3E'); background-blend-mode: soft-light;"></div>

    <div class="relative flex items-center w-full">
        <!-- Icon -->
        <svg class="h-4 w-4 text-on-surface-variant/40 group-focus-within/search-ui:text-primary group-hover/search-ui:scale-110 transition-all duration-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>

        <input 
            {{ $attributes->merge([
                'type' => 'text',
                'wire:model.live.debounce.' . $debounce => $model,
                'placeholder' => $placeholder,
                'class' => 'w-full bg-transparent border-none py-4 px-4 text-xs text-on-surface placeholder:text-on-surface-variant/20 focus:ring-0 transition-all duration-500 font-bold tracking-tight outline-none ' . $class
            ]) }}
        >
    </div>
</div>
