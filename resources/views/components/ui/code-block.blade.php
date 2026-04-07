@props(['language' => 'javascript', 'code' => '', 'title' => '', 'type' => 'elegant'])

@php
    $typeColors = [
        'performance' => 'text-secondary bg-secondary/10',
        'readability' => 'text-tertiary bg-tertiary/10',
        'elegant' => 'text-primary bg-primary/10',
    ];
    
    $glowClasses = [
        'performance' => 'shadow-[0_0_20px_rgba(78,222,163,0.1)]',
        'readability' => 'shadow-[0_0_20px_rgba(255,185,95,0.1)]',
        'elegant' => 'shadow-[0_0_20px_rgba(190,194,255,0.1)]',
    ];
@endphp

<div class="group relative overflow-hidden rounded-round-4 bg-surface-container transition-all duration-500 hover:scale-[1.01] {{ $glowClasses[$type] ?? '' }}">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-4 bg-surface-high">
        <div class="flex items-center gap-3">
            <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-widest rounded-sm {{ $typeColors[$type] ?? '' }}">
                {{ $type }}
            </span>
            <span class="font-display font-medium text-sm text-on-surface">
                {{ $title ?? 'Untitled Snippet' }}
            </span>
        </div>
        <div class="text-on-surface-variant text-xs font-mono uppercase">
            {{ $language }}
        </div>
    </div>
    
    <!-- Code Body -->
    <div class="p-6 font-mono text-sm leading-relaxed text-on-surface/90 overflow-x-auto selection:bg-primary/30">
        <pre><code>{{ $code }}</code></pre>
    </div>
</div>
