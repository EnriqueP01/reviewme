@props([
    'type' => 'line', // line, circle, block
    'class' => '',
])

@php
    $baseClasses = "bg-surface-highest/30 relative overflow-hidden before:absolute before:inset-0 before:-translate-x-full before:animate-[shimmer_2s_infinite] before:bg-gradient-to-r before:from-transparent before:via-white/5 before:to-transparent";
    
    $typeClasses = match($type) {
        'line' => 'h-3 rounded-full w-full',
        'circle' => 'rounded-full',
        'block' => 'rounded-2xl',
        default => 'h-3 rounded-full',
    };
@endphp

<div {{ $attributes->merge(['class' => "{$baseClasses} {$typeClasses} {$class}"]) }}></div>

<style>
@keyframes shimmer {
    100% {
        transform: translateX(100%);
    }
}
</style>
