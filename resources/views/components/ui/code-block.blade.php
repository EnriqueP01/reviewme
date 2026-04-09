@props([
    'snippets' => null, // Collection of snippets
    'language' => 'php', 
    'code' => '', 
    'title' => '', 
    'type' => 'elegant',
    'goals' => null,
    'context' => null
])

@php
    // If a collection of snippets is passed, initialize the data
    $snippetList = $snippets ? $snippets->map(fn($s, $index) => [
        'id' => $s->id,
        'name' => $s->filename ?: ($s->description ?: 'file_' . ($index + 1) . '.' . ($s->language === 'javascript' ? 'js' : $s->language)),
        'description' => $s->description,
        'code' => $s->code_content,
        'lang' => $s->language
    ]) : collect([['id' => 0, 'name' => 'Source.' . $language, 'description' => null, 'code' => $code, 'lang' => $language]]);

    // Minimalistic Syntax Highlighter Engine
    $highlight = function($text, $lang) {
        $text = htmlspecialchars($text);
        
        // Strings
        $text = preg_replace("/(&quot;.*?&quot;|&#039;.*?&#039;)/", '<span class="text-[#e0af68] italic">$1</span>', $text);
        
        // Comments
        $text = preg_replace("/(\/\/.*)/", '<span class="text-[#565f89] italic opacity-70">$1</span>', $text);
        $text = preg_replace("/(\/\*.*?\*\/)/s", '<span class="text-[#565f89] italic opacity-70">$1</span>', $text);

        // Keywords
        $keywords = [
            'php' => ['public', 'private', 'protected', 'function', 'class', 'const', 'return', 'if', 'else', 'elseif', 'foreach', 'as', 'use', 'namespace', 'new', 'static', 'extends', 'implements', 'try', 'catch', 'finally', 'throw', 'echo', 'print', 'die', 'exit'],
            'javascript' => ['export', 'import', 'const', 'let', 'var', 'function', 'async', 'await', 'return', 'if', 'else', 'for', 'while', 'switch', 'case', 'break', 'new', 'this', 'extends', 'class', 'from'],
            'css' => ['@media', '@import', '@extend', '@mixin', '@include', 'important', 'hover', 'focus', 'active'],
            'default' => ['if', 'else', 'return', 'class', 'new', 'var', 'let', 'const']
        ];
        
        $currentKeywords = $keywords[strtolower($lang)] ?? $keywords['default'];
        foreach($currentKeywords as $word) {
            $text = preg_replace("/\b($word)\b/", '<span class="text-[#bb9af7] font-bold">$1</span>', $text);
        }

        // Logic & Control
        $text = preg_replace("/\b(true|false|null)\b/i", '<span class="text-[#ff9e64] font-black">$1</span>', $text);
        
        // Numbers
        $text = preg_replace("/\b(\d+)\b/", '<span class="text-[#ff9e64]">$1</span>', $text);

        // Variables (PHP)
        if ($lang === 'php') {
            $text = preg_replace("/(\\$[a-zA-Z_]\w*)/", '<span class="text-[#f7768e]">$1</span>', $text);
        }

        // Functions
        $text = preg_replace("/(\w+)\s*\(/", '<span class="text-[#7dcfff] font-bold">$1</span>(', $text);

        // HTML Tags
        if (in_array(strtolower($lang), ['html', 'blade', 'xml'])) {
            $text = preg_replace("/(&lt;\/?[a-z0-9]+.*?&gt;)/i", '<span class="text-[#f7768e]">$1</span>', $text);
        }

        return $text;
    };

    // Pre-highlight everything and store in a collection for Alpine
    $snippetData = $snippetList->map(function($s) use ($highlight) {
        $hCode = $highlight($s['code'], $s['lang']);
        return [
            'id' => $s['id'],
            'name' => $s['name'],
            'description' => $s['description'],
            'lang' => $s['lang'],
            'raw' => $s['code'],
            'lines' => explode("\n", $hCode)
        ];
    });
    
    $lenses = explode(',', $type ?? 'elegant');
@endphp

<div x-data="{ 
    activeTab: 0,
    showInfo: false,
    snippets: @js($snippetData),
    copied: false,
    copy() {
        navigator.clipboard.writeText(this.snippets[this.activeTab].raw);
        this.copied = true;
        if (window.fx) window.fx.play('success');
        setTimeout(() => this.copied = false, 2000);
    }
}" wire:ignore class="relative group/lens w-full">
    
    <!-- Info Overlay (Slide Down & Reveal) -->
    <div 
        x-show="showInfo" 
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 -translate-y-8 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
        class="absolute top-20 right-10 left-10 z-30"
        x-cloak
        @mouseenter="showInfo = true"
        @mouseleave="showInfo = false"
    >
        <div class="glass-panel p-8 rounded-3xl border border-primary/20 bg-[#0f111a]/95 backdrop-blur-[50px] shadow-[0_0_100px_rgba(0,0,0,0.8)] flex flex-col md:flex-row gap-12 overflow-hidden relative">
            <!-- Decorative Glow -->
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/10 blur-[80px] rounded-full pointer-events-none"></div>

            <div class="flex-1 space-y-4 relative z-10">
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-primary shadow-[0_0_10px_#bec2ff]"></span>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-primary">{{ __('Artifact_Analysis') }}</h4>
                </div>
                <!-- File Specific Meta -->
                <div class="bg-white/5 p-4 rounded-xl border border-white/5">
                    <div class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant/40 mb-2">{{ __('Selected_Module') }}</div>
                    <div class="text-xs font-mono font-bold text-primary flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-width="2.5"/></svg>
                        <span x-text="snippets[activeTab].name"></span>
                    </div>
                </div>
                
                @if($goals)
                    <div class="space-y-2 mt-4">
                        <h5 class="text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/40">{{ __('Review_Objectives') }}</h5>
                        <p class="text-sm text-on-surface-variant font-medium leading-relaxed italic opacity-80 border-l-2 border-primary/10 pl-4">{{ $goals }}</p>
                    </div>
                @endif
            </div>

            @if($context)
                <div class="flex-1 space-y-4 relative z-10">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-secondary shadow-[0_0_10px_#4edea3]"></span>
                        <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-secondary">{{ __('Application_Context') }}</h4>
                    </div>
                    <p class="text-sm text-on-surface-variant font-medium leading-relaxed opacity-80 border-l-2 border-secondary/10 pl-4">{{ $context }}</p>
                    
                    <!-- Dynamic Fragment Description -->
                    <template x-if="snippets[activeTab].description">
                        <div class="mt-6 pt-6 border-t border-secondary/10">
                             <h4 class="text-[9px] font-black uppercase tracking-[0.2em] text-secondary/40 mb-2">{{ __('Curation Context') }}</h4>
                             <p class="text-xs text-on-surface-variant leading-relaxed italic opacity-70" x-text="snippets[activeTab].description"></p>
                        </div>
                    </template>
                </div>
            @endif
        </div>
    </div>

    <!-- The Monolith Container -->
    <div @class(['glass-panel rounded-3xl overflow-hidden border border-white/5 transition-all duration-700 group-hover/lens:border-primary/20 group-hover/lens:shadow-[0_45px_120px_-20px_rgba(0,0,0,0.9)]'])>
        
        <!-- Lens Header -->
        <div class="flex items-center justify-between px-6 py-4 bg-white/[0.04] border-b border-white/5 relative z-20">
            <!-- Inspection Module -->
            <div class="flex items-center gap-4 shrink-0 group/inspect cursor-pointer hover:bg-white/5 px-4 py-2 rounded-2xl transition-all" 
                 @mouseenter="showInfo = true" @mouseleave="showInfo = false">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center text-primary/60 group-hover/inspect:bg-primary/20 group-hover/inspect:scale-110 transition-all duration-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <div class="h-4 w-px bg-white/10 mx-1"></div>
                <div class="flex flex-col">
                    <span class="text-[8px] font-black uppercase tracking-[0.4em] text-on-surface-variant/40 leading-none mb-1 group-hover/inspect:text-primary transition-colors flex items-center gap-2">
                        @if($context)
                            <span class="inline-block max-w-[0px] group-hover/inspect:max-w-[150px] truncate align-bottom text-primary/70 transition-all duration-700 opacity-0 group-hover/inspect:opacity-100 -translate-x-2 group-hover/inspect:translate-x-0 font-mono italic">{{ $context }}</span>
                        @endif
                    </span>
                    <span class="text-[11px] font-mono font-bold text-on-surface tracking-wide flex items-center gap-2">
                        <span class="w-1 h-1 rounded-full bg-primary animate-pulse shadow-[0_0_10px_rgba(190,194,255,0.8)]"></span>
                        {{ $title ?: 'UNNAMED_MODULE' }}
                    </span>
                </div>
            </div>

            <!-- Tabs Navigation (File Explorer) -->
            <div class="flex-grow flex items-center justify-center px-8">
                <div class="flex items-center gap-1 bg-black/40 rounded-xl p-0.5 max-w-full overflow-x-auto no-scrollbar scroll-smooth">
                    <template x-for="(snippet, index) in snippets" :key="index">
                        <button 
                            @click="activeTab = index; if(window.fx) window.fx.play('click')"
                            :class="activeTab === index ? 'bg-primary/20 text-primary shadow-[0_0_15px_rgba(190,194,255,0.1)]' : 'text-on-surface-variant/30 hover:text-on-surface-variant hover:bg-white/5'"
                            class="px-5 py-2 rounded-lg text-[10px] font-black font-mono tracking-widest transition-all duration-500 whitespace-nowrap"
                            x-text="snippet.name"
                        ></button>
                    </template>
                </div>
            </div>
            
            <div class="flex items-center gap-4 shrink-0">
                <button 
                    @mouseenter="showInfo = true" @mouseleave="showInfo = false"
                    :class="showInfo ? 'text-primary bg-primary/10 border-primary/20 scale-125' : 'text-on-surface-variant hover:text-primary hover:bg-white/5'"
                    class="p-2.5 rounded-xl transition-all duration-500 border border-transparent"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </button>
                <div class="h-4 w-px bg-white/10 mx-1"></div>

                <div class="flex items-center gap-2">
                    @foreach($lenses as $l)
                        @php $lKey = strtolower(trim($l)); @endphp
                        <span 
                            class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-[0.2em] border transition-all duration-700 transform hover:scale-110"
                            style="color: var(--lens-{{ $lKey }}); background-color: rgba(var(--lens-{{ $lKey }}-rgb), 0.1); border-color: rgba(var(--lens-{{ $lKey }}-rgb), 0.3); box-shadow: 0 0 15px rgba(var(--lens-{{ $lKey }}-rgb), 0.1);"
                        >
                            {{ trim($l) }}
                        </span>
                    @endforeach
                </div>
                
                <button @click="copy()" class="relative text-on-surface-variant hover:text-primary transition-all p-2.5 rounded-xl hover:bg-white/5 group/copy">
                    <div class="flex items-center gap-2">
                        <span x-show="!copied" class="text-[8px] font-black uppercase tracking-widest opacity-0 group-hover/copy:opacity-100 transition-all -translate-x-2 group-hover/copy:translate-x-0">Copy</span>
                        <span x-show="copied" x-cloak class="text-[8px] font-black uppercase tracking-widest text-secondary scale-110">Done</span>
                        
                        <svg x-show="!copied" class="w-4 h-4 transition-transform group-hover/copy:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <svg x-show="copied" x-cloak class="w-4 h-4 text-secondary animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </button>
            </div>
        </div>

        <!-- Code Body with Magnetic Scroll & Transitions -->
        <div class="relative bg-[#0d0e14] selection:bg-primary/30 rounded-b-3xl min-h-[100px] max-h-[500px] overflow-y-auto overflow-x-hidden custom-scrollbar scroll-smooth snap-y snap-mandatory" 
             style="scrollbar-width: thin; scrollbar-color: rgba(190, 194, 255, 0.2) transparent;">
            
            <template x-for="(snippet, sIndex) in snippets" :key="sIndex">
                <div 
                    x-show="activeTab === sIndex"
                    x-transition:enter="transition ease-out duration-700"
                    x-transition:enter-start="opacity-0 translate-x-8"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-300 absolute inset-0"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-8"
                    class="p-6"
                >
                    <div class="font-mono text-[12px] leading-[2rem] relative z-10 space-y-0.5">
                        <template x-for="(line, lIndex) in snippet.lines" :key="lIndex">
                            <div class="flex items-start group/line hover:bg-white/[0.03] -mx-6 px-6 transition-all duration-300 snap-start snap-always scroll-mt-0">
                                <div class="w-12 shrink-0 text-right pr-6 select-none text-on-surface-variant/10 group-hover/line:text-primary transition-colors font-bold text-[10px]">
                                    <span x-text="lIndex + 1"></span>
                                </div>
                                <div class="flex-1 whitespace-pre-wrap break-all text-on-surface/90 font-medium tracking-wide group-hover/line:text-white transition-colors" x-html="line || '&nbsp;'">
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
