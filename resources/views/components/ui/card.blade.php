@props(['padding' => 'p-6', 'tonal' => 'low'])

@php
    $layers = [
        'low' => 'bg-surface-low',
        'container' => 'bg-surface-container',
        'high' => 'bg-surface-high',
        'highest' => 'bg-surface-highest',
        'primary' => 'bg-primary/10 border border-primary/20',
        'secondary' => 'bg-secondary/10 border border-secondary/20',
        'tertiary' => 'bg-tertiary/10 border border-tertiary/20',
        'error' => 'bg-error/10 border border-error/20',
    ];
    
    $layerClass = $layers[$tonal] ?? $layers['low'];
    $classes = 'rounded-round-4 transition-all duration-300 ' . $layerClass . ' ' . $padding;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
