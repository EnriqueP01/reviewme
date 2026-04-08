@props(['language' => 'javascript', 'code' => '', 'title' => '', 'type' => 'elegant'])

@php
    $typeColors = [
        'performance' => 'text-secondary bg-secondary/10 border-secondary/30 shadow-[0_0_20px_rgba(78,222,163,0.1)]',
        'readability' => 'text-tertiary bg-tertiary/10 border-tertiary/30 shadow-[0_0_20px_rgba(255,185,95,0.1)]',
        'elegant' => 'text-primary bg-primary/10 border-primary/30 shadow-[0_0_20px_rgba(190,194,255,0.1)]',
    ];
    
    // Improved highlighting
    $highlighted = $code;
    
    // Protection: Mask Livewire attributes to prevent DOM corruption
    $highlighted = str_replace(['wire:', 'x-'], ['wi&#8203;re:', 'x&#8203;-'], $highlighted);

    $keywords = ['public', 'private', 'protected', 'function', 'class', 'const', 'let', 'var', 'if', 'else', 'return', 'import', 'export', 'final', 'namespace', 'use', 'static', 'throws', 'async', 'await', 'new', 'this'];
    foreach($keywords as $word) {
        $highlighted = preg_replace("/\b($word)\b/", '<span class="text-primary font-bold">$1</span>', $highlighted);
    }
    $highlighted = preg_replace("/(\\$[a-zA-Z_]\w*)/", '<span class="text-secondary">$1</span>', $highlighted);
    $highlighted = preg_replace("/(\w+)\s*\(/", '<span class="text-on-surface font-bold">$1</span>(', $highlighted);
    $highlighted = preg_replace("/(['\"].*?['\"])/", '<span class="text-tertiary italic">$1</span>', $highlighted);
    $highlighted = preg_replace("/(\/\/.*)/", '<span class="text-on-surface-variant font-medium opacity-40">$1</span>', $highlighted);
    
    $lines = explode("\n", $highlighted);
@endphp

<div x-data="{ 
    copied: false,
    copy() {
        navigator.clipboard.writeText(`{{ $code }}`);
        this.copied = true;
        setTimeout(() => this.copied = false, 2000);
    }
}" wire:ignore class="relative group/lens">
    <!-- The Monolith Container -->
    <div @class(['glass-panel rounded-3xl overflow-hidden border border-white/5 transition-all duration-500 group-hover/lens:border-primary/20 group-hover/lens:shadow-2xl translate-y-0 group-hover/lens:-translate-y-1'])>
        
        <!-- Lens Header -->
        <div class="flex items-center justify-between px-8 py-5 bg-white/[0.04] border-b border-white/5">
            <div class="flex items-center gap-6">
                <div class="flex gap-2">
                    <div class="w-3.5 h-3.5 rounded-full bg-[#ff5f56] shadow-[0_0_10px_rgba(255,95,86,0.3)]"></div>
                    <div class="w-3.5 h-3.5 rounded-full bg-[#ffbd2e] shadow-[0_0_10px_rgba(255,189,46,0.2)]"></div>
                    <div class="w-3.5 h-3.5 rounded-full bg-[#27c93f] shadow-[0_0_10px_rgba(39,201,63,0.3)]"></div>
                </div>
                <div class="h-5 w-px bg-white/10 mx-1"></div>
                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    Artifact: <span class="text-on-surface">{{ $title ?: 'UNNAMED_PATTERN' }}</span>
                </span>
            </div>
            
            <div class="flex items-center gap-8">
                <span @class(['px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border transition-all duration-300', $typeColors[$type]])>
                    {{ $type }}
                </span>
                
                <button @click="copy()" class="relative text-on-surface-variant hover:text-primary transition-all p-2 rounded-xl hover:bg-white/5 group/copy">
                    <div class="flex items-center gap-2">
                        <span x-show="!copied" class="text-[9px] font-black uppercase tracking-widest opacity-0 group-hover/copy:opacity-100 transition-all -translate-x-2 group-hover/copy:translate-x-0">Capture</span>
                        <span x-show="copied" x-cloak class="text-[9px] font-black uppercase tracking-widest text-secondary">Secured</span>
                        
                        <svg x-show="!copied" class="w-5 h-5 transition-transform group-hover/copy:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <svg x-show="copied" x-cloak class="w-5 h-5 text-secondary animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </button>
            </div>
        </div>

        <!-- Code Body -->
        <div wire:ignore class="relative p-8 font-mono text-[14px] leading-relaxed overflow-x-auto bg-[#08080c] selection:bg-primary/20">
            <!-- Noise Overlay -->
            <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\'0 0 256 256\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cfilter id=\'noiseFilter\'%3E%3CfeTurbulence type=\'fractalNoise\' baseFrequency=\'0.65\' numOctaves=\'3\' stitchTiles=\'stitch\'/%3E%3C/filter%3E%3Crect width=\'100%25\' height=\'100%25\' filter=\'url(%23noiseFilter)\'/%3E%3C/svg%3E');"></div>
            
            <pre class="relative z-10"><code class="block space-y-1.5">@foreach($lines as $index => $line)
<div class="flex gap-10 group/line">
    <span class="w-8 text-right text-on-surface-variant/20 select-none group-hover/line:text-primary transition-colors font-bold">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
    <span class="flex-1 text-on-surface/90 font-medium">{!! $line !!}</span>
</div>
@endforeach</code></pre>
        </div>
    </div>
</div>
