@props(['variant' => 'primary', 'size' => 'md'])

@php
    $baseClasses = 'inline-flex items-center justify-center font-display font-bold transition-all duration-300 transform active:scale-95 disabled:opacity-50 disabled:pointer-events-none hover:scale-[1.02]';
    
    $variants = [
        'primary' => 'bg-primary text-on-primary hover:bg-primary/90',
        'secondary' => 'bg-secondary text-on-secondary hover:bg-secondary/90',
        'ghost' => 'bg-transparent text-on-surface hover:bg-surface-high',
        'outline' => 'bg-transparent text-primary border border-primary/20 hover:border-primary/50 hover:bg-primary/5',
    ];
    
    $sizes = [
        'sm' => 'px-4 py-2 text-sm rounded-round-4',
        'md' => 'px-6 py-3 text-base rounded-round-4',
        'lg' => 'px-8 py-4 text-lg rounded-round-4',
    ];
    
    $classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

<button {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
