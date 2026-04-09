@props([
    'snippets' => null, // Collection of snippets
    'language' => 'php', 
    'code' => '', 
    'title' => '', 
    'type' => 'elegant',
    'goals' => null,
    'context' => null,
    'suggestions' => collect([]),
    'selectedVersion' => 1
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

    $suggestList = $suggestions->map(fn($s) => [
        'id' => $s->id,
        'snippet_id' => $s->snippet_id,
        'line' => $s->line_number,
        'end_line' => $s->end_line_number,
        'description' => $s->description,
        'user' => [
            'name' => $s->user->name,
            'avatar' => $s->user->avatar
        ],
        'original' => $s->original_content,
        'suggested' => $highlight($s->suggested_content, ($snippetList->firstWhere('id', $s->snippet_id)['lang'] ?? 'php')),
        'suggested_raw' => $s->suggested_content
    ]);
@endphp

<div x-data="{ 
    activeTab: 0,
    showInfo: false,
    snippets: @js($snippetData),
    suggestions: @js($suggestList),
    activeSuggestion: null,
    suggestionMode: 'diff',
    copied: false,
    
    toggleSuggestion(sId) {
        if (this.activeSuggestion && this.activeSuggestion.id === sId) {
            // Cycle modes: DIFF -> EDIT -> OFF
            if (this.suggestionMode === 'diff') {
                this.suggestionMode = 'edit';
            } else {
                this.activeSuggestion = null;
                this.suggestionMode = 'diff';
            }
        } else {
            this.activeSuggestion = this.suggestions.find(s => s.id === sId);
            this.suggestionMode = 'diff';
        }
    },
    
    copy() {
        navigator.clipboard.writeText(this.snippets[this.activeTab].raw);
        this.copied = true;
        if (window.fx) window.fx.play('success');
        setTimeout(() => this.copied = false, 2000);
    }
}" wire:ignore.self x-init="console.log('CodeBlock initialized with', snippets.length, 'snippets')" class="relative group/lens w-full">
    
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
                    <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-primary">{{ __('Analysis') }}</h4>
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
                    <span class="text-[11px] font-mono font-bold text-on-surface tracking-wide flex items-center gap-3">
                        <span class="w-1 h-1 rounded-full bg-primary animate-pulse shadow-[0_0_10px_rgba(190,194,255,0.8)]"></span>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-lg bg-primary/10 text-primary text-[9px] font-black border border-primary/20">V{{ $selectedVersion }}</span>
                            {{ $title ?: 'UNNAMED_MODULE' }}
                        </div>
                    </span>
                </div>
            </div>

            <!-- Tabs Navigation (File Explorer) -->
            <div class="flex-grow flex items-center justify-center px-8">
                <div class="flex items-center gap-1 bg-black/40 rounded-xl p-0.5 max-w-full overflow-x-auto no-scrollbar scroll-smooth">
                    <template x-for="(snippet, index) in snippets" :key="index">
                        <button 
                            @click="activeTab = index; $dispatch('snippet-changed', { id: snippet.id }); if(window.fx) window.fx.play('click')"
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
        <div class="relative bg-[#0d0e14] selection:bg-primary/30 rounded-b-3xl max-h-[1200px] overflow-y-auto overflow-x-hidden custom-scrollbar scroll-smooth" 
             style="scrollbar-width: thin; scrollbar-color: rgba(190, 194, 255, 0.2) transparent;">
            
            <template x-for="(snippet, sIndex) in snippets" :key="sIndex">
                <div 
                    x-show="activeTab === sIndex"
                    x-transition:enter="transition ease-out duration-700"
                    x-transition:enter-start="opacity-0 translate-x-8"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="p-6"
                >
                    <div class="font-mono text-[12px] leading-6 relative z-10 space-y-0">
                        <template x-for="(line, lIndex) in snippet.lines" :key="lIndex">
                            <div class="group/linerow relative flex items-start">
                                <!-- LINE CONTENT -->
                                <div class="flex-1 flex items-start group/line hover:bg-white/[0.03] -mx-6 px-6 transition-all duration-300 selection:bg-primary/40 selection:text-white relative"
                                     :data-snippet="snippet.id"
                                     :data-line="lIndex + 1"
                                     :class="{
                                         'bg-primary/5': activeSuggestion && (lIndex + 1) >= activeSuggestion.line && (lIndex + 1) <= activeSuggestion.end_line && suggestionMode === 'edit',
                                         'opacity-30': activeSuggestion && (lIndex + 1) >= activeSuggestion.line && (lIndex + 1) <= activeSuggestion.end_line && suggestionMode === 'diff'
                                     }">
                                    
                                    <!-- Quick Review Anchor (Photo) Integrated -->
                                    <template x-for="sug in suggestions.filter(s => s.snippet_id === snippet.id && s.line === (lIndex + 1))" :key="sug.id">
                                        <div class="absolute left-0 top-1/2 -translate-y-1/2 z-50 flex items-center group/picaction h-full">
                                            <div class="relative">
                                                <button @click="if(activeSuggestion && activeSuggestion.id === sug.id) { suggestionMode = (suggestionMode === 'diff' ? 'edit' : (suggestionMode === 'edit' ? 'original' : 'diff')) } else { activeSuggestion = sug; suggestionMode = 'diff' }"
                                                        class="w-8 h-8 rounded-lg overflow-hidden border-2 border-[#1a1b26] shadow-xl hover:scale-110 active:scale-95 transition-all outline-none"
                                                        :class="activeSuggestion && activeSuggestion.id === sug.id ? 'ring-2 ring-primary ring-offset-2 ring-offset-[#0d0e14] border-white' : 'border-white/10'">
                                                    <img :src="sug.user.avatar" class="w-full h-full object-cover">
                                                </button>
                                                <!-- Status Indicator -->
                                                <div class="absolute -bottom-1 -right-1 w-3 h-3 rounded-full border-2 border-[#1a1b26] shadow-md"
                                                     :class="activeSuggestion && activeSuggestion.id === sug.id ? 'bg-primary' : 'bg-on-surface-variant/40'"></div>
                                            </div>

                                            <!-- Description Tooltip -->
                                            <div class="absolute left-full ml-4 top-1/2 -translate-y-1/2 w-72 p-4 bg-[#1a1b26] border border-white/10 rounded-[1.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)] opacity-0 scale-95 pointer-events-none group-hover/picaction:opacity-100 group-hover/picaction:scale-100 transition-all z-[100] backdrop-blur-xl">
                                                <div class="flex items-center gap-3 mb-3">
                                                    <img :src="sug.user.avatar" class="w-8 h-8 rounded-lg border border-white/10">
                                                    <div>
                                                        <p class="text-[10px] font-black uppercase tracking-widest text-primary" x-text="sug.user.name"></p>
                                                        <p class="text-[8px] font-black text-on-surface-variant uppercase tracking-tighter">{{ __('Quick Reviewer') }}</p>
                                                    </div>
                                                </div>
                                                <p class="text-[12px] text-on-surface/80 font-medium leading-relaxed" x-text="sug.description"></p>
                                                <div class="mt-3 pt-3 border-t border-white/5 flex items-center justify-between">
                                                    <span class="text-[9px] font-black uppercase text-on-surface-variant/40">{{ __('Click to toggle Mode') }}</span>
                                                    <div class="flex gap-1.5">
                                                        <div class="w-1.5 h-1.5 rounded-full" :class="suggestionMode === 'original' ? 'bg-primary' : 'bg-white/10'"></div>
                                                        <div class="w-1.5 h-1.5 rounded-full" :class="suggestionMode === 'diff' ? 'bg-primary' : 'bg-white/10'"></div>
                                                        <div class="w-1.5 h-1.5 rounded-full" :class="suggestionMode === 'edit' ? 'bg-primary' : 'bg-white/10'"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <!-- Gutter -->
                                    <div class="w-14 shrink-0 text-right pr-6 select-none border-r border-white/5 font-mono font-bold text-[10px] flex items-center justify-end h-7"
                                         :class="{
                                            'text-red-400/50 bg-red-400/5': activeSuggestion && suggestionMode === 'diff' && (lIndex + 1) >= activeSuggestion.line && (lIndex + 1) <= activeSuggestion.end_line,
                                            'text-emerald-400/50 bg-emerald-400/5': activeSuggestion && suggestionMode === 'edit' && (lIndex + 1) >= activeSuggestion.line && (lIndex + 1) <= activeSuggestion.end_line,
                                            'text-on-surface-variant/10 group-hover/line:text-primary transition-colors': !activeSuggestion || (lIndex + 1) < activeSuggestion.line || (lIndex + 1) > activeSuggestion.end_line
                                         }">
                                        <span x-text="lIndex + 1" x-show="!(activeSuggestion && suggestionMode === 'diff' && (lIndex + 1) >= activeSuggestion.line && (lIndex + 1) <= activeSuggestion.end_line)"></span>
                                        <span x-show="activeSuggestion && suggestionMode === 'diff' && (lIndex + 1) >= activeSuggestion.line && (lIndex + 1) <= activeSuggestion.end_line" class="font-black text-xs">-</span>
                                        <span x-show="activeSuggestion && suggestionMode === 'edit' && (lIndex + 1) >= activeSuggestion.line && (lIndex + 1) <= activeSuggestion.end_line" class="font-black text-xs">+</span>
                                    </div>
                                    
                                    <!-- Actual Code Display -->
                                    <div class="flex-1 whitespace-pre-wrap break-all text-on-surface/90 font-mono text-[11px] leading-relaxed py-1 px-6 transition-all"
                                         :class="{
                                            'text-red-200/70 bg-red-500/[0.05]': activeSuggestion && suggestionMode === 'diff' && (lIndex + 1) >= activeSuggestion.line && (lIndex + 1) <= activeSuggestion.end_line,
                                            'text-emerald-200/90 font-bold bg-emerald-500/[0.05]': activeSuggestion && suggestionMode === 'edit' && (lIndex + 1) >= activeSuggestion.line && (lIndex + 1) <= activeSuggestion.end_line
                                         }"
                                         x-html="activeSuggestion && (lIndex + 1) >= activeSuggestion.line && (lIndex + 1) <= activeSuggestion.end_line && suggestionMode === 'edit'
                                                 ? (lIndex + 1 === activeSuggestion.line ? activeSuggestion.suggested : '') 
                                                 : (line.trim() === '' ? '&nbsp;' : line)">
                                    </div>
                                </div>

                                <!-- Inline Suggestion Block (The '+' part for DIFF mode) -->
                                <template x-if="activeSuggestion && suggestionMode === 'diff' && lIndex + 1 === activeSuggestion.end_line && snippet.id === activeSuggestion.snippet_id">
                                    <div class="bg-emerald-500/[0.08] flex items-start animate-in slide-in-from-top-2 duration-300">
                                        <div class="w-14 shrink-0 text-right pr-6 select-none border-r border-white/5 font-mono font-bold text-[10px] flex items-center justify-end h-auto min-h-[1.75rem] text-emerald-400/50 bg-emerald-400/5">
                                            <span class="font-black text-xs">+</span>
                                        </div>
                                        <div class="flex-1 whitespace-pre-wrap break-all text-emerald-400 font-mono text-[11px] leading-relaxed py-1 px-6 font-black bg-emerald-500/[0.12]">
                                            <div x-html="activeSuggestion.suggested"></div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
