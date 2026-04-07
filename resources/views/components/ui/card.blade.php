@props(['padding' => 'p-6', 'tonal' => 'low'])

@php
    $layers = [
        'low' => 'bg-surface-low',
        'container' => 'bg-surface-container',
        'high' => 'bg-surface-high',
        'highest' => 'bg-surface-highest',
    ];
    
    $classes = 'rounded-round-4 transition-all duration-300 ' . $layers[$tonal] . ' ' . $padding;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
