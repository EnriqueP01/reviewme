<div class="max-w-6xl mx-auto px-6 py-12" 
     x-data="{ 
        activeLine: @entangle('activeLine'),
        isReviewing: @entangle('isReviewing'),
        inlineViewMode: @entangle('inlineViewMode'),
        suggestingLine: @entangle('suggestingLine'),
        selectionPopup: { show: false, x: 0, y: 0, text: '', line: null },
        replyTo: @entangle('replyToId'),
        
        handleMouseUp(e, lineNum, originalText) {
            const selection = window.getSelection();
            const selectedText = selection.toString().trim();
            
            if (selectedText.length > 0) {
                const range = selection.getRangeAt(0);
                const rect = range.getBoundingClientRect();
                
                this.selectionPopup = {
                    show: true,
                    x: rect.left + (rect.width / 2),
                    y: rect.top + window.scrollY - 10,
                    text: selectedText,
                    line: lineNum,
                    original: originalText
                };
            } else {
                this.selectionPopup.show = false;
            }
        },

        startSuggestion() {
            $wire.setInlineSuggestion(this.selectionPopup.line, this.selectionPopup.original);
            this.selectionPopup.show = false;
        }
     }"
     @click.away="selectionPopup.show = false"
>
    <!-- Inline Selection Popup -->
    <div x-show="selectionPopup.show" 
         x-transition
         class="fixed z-[100] transform -translate-x-1/2 -translate-y-full bg-[#1a1b26] border border-primary/50 rounded-xl px-3 py-1.5 shadow-2xl flex items-center gap-2 pointer-events-auto backdrop-blur-md"
         :style="`left: ${selectionPopup.x}px; top: ${selectionPopup.y}px;`"
    >
        <button @click="startSuggestion()" class="text-[10px] font-bold text-primary hover:text-primary-light uppercase tracking-widest flex items-center gap-1.5 focus:outline-none">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
            {{ __('Suggest Implementation') }}
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        
        <!-- Post Column (2/3) -->
        <div class="lg:col-span-2 space-y-8">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        @if($post->group)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black bg-primary/10 text-primary border border-primary/20 uppercase tracking-[0.1em]">
                                <svg class="w-2.5 h-2.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z"/></svg>
                                {{ $post->group->name }}
                            </span>
                        @endif
                        <span class="text-[9px] font-black text-on-surface-variant uppercase tracking-[0.2em] bg-[#1a1b26] px-3 py-1 rounded-full border border-white/5 shadow-sm">{{ $post->visibility }}</span>
                        
                        <div class="flex items-center bg-[#0f111a] rounded-xl p-0.5 border border-white/5 shadow-inner">
                            <button wire:click="toggleInlineViewMode" class="px-4 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all {{ $inlineViewMode === 'diff' ? 'bg-primary/20 text-primary' : 'text-on-surface-variant hover:text-on-surface' }}">Diff</button>
                            <button wire:click="toggleInlineViewMode" class="px-4 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all {{ $inlineViewMode === 'edit' ? 'bg-primary/20 text-primary' : 'text-on-surface-variant hover:text-on-surface' }}">Edit</button>
                        </div>
                    </div>
                    <h1 class="text-5xl font-black font-display text-on-surface leading-tight tracking-tight mb-4 drop-shadow-2xl">{{ $post->title }}</h1>
                    <div class="relative pl-6 group">
                        <div class="absolute left-0 top-0 bottom-0 w-1 rounded-full bg-gradient-to-b from-primary to-transparent opacity-20 group-hover:opacity-100 transition-opacity duration-700"></div>
                        <p class="text-on-surface-variant text-xl font-medium leading-relaxed italic opacity-70">{{ $post->short_description }}</p>
                    </div>
                </div>
                
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-3">
                        <button wire:click="toggleReviewMode" 
                                class="px-6 py-3 rounded-2xl border transition-all text-[10px] font-black uppercase tracking-widest flex items-center gap-2.5 shadow-lg {{ $isReviewing ? 'border-primary bg-primary text-on-primary' : 'border-white/5 bg-[#1a1b26] text-on-surface-variant hover:border-primary/50 hover:text-on-surface' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            {{ $isReviewing ? __('Cancel Review') : __('Full Code Review') }}
                        </button>

                        <div class="relative">
                            <select wire:model.live="selectedVersion" class="appearance-none bg-[#1a1b26] border border-white/5 text-on-surface rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] pl-6 pr-10 py-3.5 hover:border-primary/50 transition-all cursor-pointer shadow-lg outline-none focus:ring-1 focus:ring-primary/40">
                                @php $maxVersion = $post->snippets->max('version_number') ?: 1; @endphp
                                @for($i = $maxVersion; $i >= 1; $i--)
                                    <option value="{{ $i }}">{{ __('Version') }} {{ $i }}</option>
                                @endfor
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant/40">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 justify-end">
                        @can('update', $post)
                            <a href="{{ route('posts.update-code', $post->id) }}"
                               class="px-5 py-2.5 rounded-xl border border-primary/20 bg-primary/5 text-primary hover:bg-primary hover:text-on-primary transition-all text-[9px] font-black uppercase tracking-widest shadow-lg flex items-center gap-2"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                {{ __('Update') }}
                            </a>
                        @endcan

                        @can('delete', $post)
                            <button wire:click="deletePost" 
                                    wire:confirm="{{ __('Permanently delete this post?') }}"
                                    class="p-2.5 rounded-xl border border-error/20 bg-error/5 text-error hover:bg-error hover:text-on-error transition-all shadow-lg active:scale-90 group/del"
                            >
                                <svg class="w-4 h-4 group-hover/del:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        @endcan
                    </div>
                </div>
            </div>

            @if($isReviewing)
                <div class="bg-[#1a1b26] border border-primary/20 rounded-[2.5rem] p-10 space-y-8 animate-fade-in shadow-2xl relative">
                    <div class="flex items-center gap-4 border-b border-white/5 pb-8">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black font-display text-on-surface uppercase tracking-tighter">{{ __('Complete Review Factory') }}</h2>
                            <p class="text-[9px] font-bold text-on-surface-variant/40 uppercase tracking-[0.3em]">{{ __('Collaborative refinement process') }}</p>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <x-ui.textarea wire:model="reviewDescription" label="{{ __('Overall Evaluation') }}" placeholder="{{ __('Detailed feedback about the whole post architecture...') }}" />
                        
                        <div class="grid grid-cols-1 gap-6">
                            @foreach($currentSnippets as $snippet)
                                <div class="bg-[#1e1f2b] rounded-3xl p-6 border border-white/5 shadow-xl transition-all hover:border-primary/20" x-data="{ expanded: false }">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-[#0f111a] flex items-center justify-center border border-white/5 text-primary text-[10px] font-black italic">
                                                {{ $loop->iteration }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-xs font-bold text-on-surface tracking-tight">{{ $snippet->filename ?: $snippet->name }}</span>
                                                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-primary/40">{{ $snippet->language }}</span>
                                            </div>
                                            @if($reviewFilesData[$snippet->id]['modified'] ?? false)
                                                <span class="bg-emerald-500/10 text-emerald-500 text-[8px] font-black px-2 py-0.5 rounded-lg uppercase tracking-widest border border-emerald-500/20 shadow-sm ml-2">Modified</span>
                                            @endif
                                        </div>
                                        <button @click="expanded = !expanded" class="px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] border border-white/10 hover:border-primary/50 hover:text-primary transition-all shadow-lg active:scale-95">
                                            <span x-show="!expanded">{{ __('Edit this file') }}</span>
                                            <span x-show="expanded">{{ __('Done editing') }}</span>
                                        </button>
                                    </div>
                                    
                                    <div x-show="expanded" class="mt-8 space-y-6 animate-slide-down" x-cloak>
                                        <div class="relative group/edit">
                                            <textarea wire:model.lazy="reviewFilesData.{{ $snippet->id }}.content" 
                                                      @input="$wire.set('reviewFilesData.{{ $snippet->id }}.modified', true)"
                                                      class="w-full font-mono text-[13px] bg-[#0d0e12] border border-white/5 rounded-2xl p-8 min-h-[400px] text-on-surface focus:ring-1 focus:ring-primary/40 shadow-inner leading-relaxed transition-all"></textarea>
                                            <div class="absolute top-4 right-6 text-[8px] font-black uppercase tracking-widest text-primary/30 group-hover/edit:text-primary transition-colors italic">Source Edit</div>
                                        </div>
                                        <x-ui.input wire:model="reviewFilesData.{{ $snippet->id }}.description" label="{{ __('Change Summary') }}" placeholder="{{ __('Short notes on changes for this file...') }}" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-10 border-t border-white/5">
                        <button wire:click="toggleReviewMode" class="text-[9px] font-black uppercase tracking-[0.4em] text-on-surface-variant hover:text-error transition-all">{{ __('Discard') }}</button>
                        <x-ui.button wire:click="saveFullReview" variant="primary" size="lg" icon="rocket">{{ __('Publish Complete Review') }}</x-ui.button>
                    </div>
                </div>
            @endif

            <!-- Multi-file Code Viewer Loop -->
            <div class="space-y-12">
                @forelse($currentSnippets as $snippet)
                    <div class="bg-[#1a1b26] rounded-[3rem] overflow-hidden border border-white/5 shadow-[0_40px_120px_rgba(0,0,0,0.7)] relative group/viewer">
                        <x-ui.loader-overlay target="selectedVersion, saveComment, deleteReview, selectLine, saveInlineSuggestion" />
                        
                        <div class="flex items-center justify-between px-10 py-6 bg-[#1e1f2b] border-b border-white/5">
                            <div class="flex items-center gap-8">
                                <div class="flex gap-2">
                                    <div class="w-3.5 h-3.5 rounded-full bg-red-500/30 border border-red-500/20 shadow-inner"></div>
                                    <div class="w-3.5 h-3.5 rounded-full bg-amber-500/30 border border-amber-500/20 shadow-inner"></div>
                                    <div class="w-3.5 h-3.5 rounded-full bg-emerald-500/30 border border-emerald-500/20 shadow-inner"></div>
                                </div>
                                <div class="h-5 w-px bg-white/5 mx-2"></div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-on-surface font-mono font-bold tracking-tight">{{ $snippet->filename ?: $snippet->name }}</span>
                                    <span class="text-[8px] font-black uppercase tracking-[0.3em] text-on-surface-variant/40 line-clamp-1">{{ $snippet->description ?: 'Source implementation' }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-[10px] uppercase font-black tracking-[0.3em] text-primary/70 px-5 py-2 bg-primary/5 rounded-2xl border border-primary/20 shadow-sm backdrop-blur-sm">{{ $snippet->language }}</span>
                            </div>
                        </div>

                        <div class="p-0 font-mono text-[14px] leading-relaxed overflow-x-auto bg-[#0d0e12] py-8 custom-scrollbar">
                            <table class="w-full border-separate border-spacing-0">
                                @foreach(explode("\n", $snippet->code_content) as $index => $line)
                                    @php 
                                        $num = $index + 1; 
                                        $suggestion = $snippet->inlineSuggestions->where('line_number', $num)->first();
                                        $activeId = $snippet->id . '-' . $num;
                                        
                                        // Simple Blade Highlighting
                                        $hLine = htmlspecialchars($line ?: ' ');
                                        $hLine = preg_replace("/(&quot;.*?&quot;|&#039;.*?&#039;)/", '<span class="text-[#e0af68] italic">$1</span>', $hLine);
                                        $hLine = preg_replace("/(\/\/.*)/", '<span class="text-[#565f89] italic opacity-60">$1</span>', $hLine);
                                        $hLine = preg_replace("/\b(public|private|protected|function|class|return|if|else|foreach|as|use|namespace|new|static|var|let|const|import|export|from|await|async)\b/", '<span class="text-[#bb9af7] font-bold">$1</span>', $hLine);
                                        $hLine = preg_replace("/\b(true|false|null)\b/i", '<span class="text-[#ff9e64] font-black">$1</span>', $hLine);
                                        $hLine = preg_replace("/\b(\d+)\b/", '<span class="text-[#ff9e64]/80">$1</span>', $hLine);
                                    @endphp
                                    <tr class="group hover:bg-white/[0.03] transition-colors relative" 
                                        x-data="{ isHovered: false }"
                                        @mouseenter="isHovered = true"
                                        @mouseleave="isHovered = false"
                                        :class="{ 'bg-primary/5': activeLine == '{{ $activeId }}' }">
                                        
                                        <td class="w-24 text-on-surface-variant/20 text-right pr-10 select-none font-mono text-[11px] align-top py-1 border-r border-white/5 group-hover:text-primary transition-colors italic">
                                            {{ $num }}
                                            @if($suggestion)
                                                <div class="absolute right-0 top-1/2 -translate-y-1/2">
                                                    <img src="{{ $suggestion->user->avatar }}" class="w-5 h-5 rounded-lg border border-primary/50 shadow-2xl -mr-3 relative z-10 hover:scale-125 transition-transform cursor-help" title="{{ $suggestion->user->name }}">
                                                </div>
                                            @endif
                                        </td>
                                        
                                        <td class="whitespace-pre px-8 group/line relative align-top py-1" @mouseup="handleMouseUp($event, {{ $num }}, @js($line))">
                                            @if($suggestion)
                                                <div x-show="isHovered" 
                                                     x-transition
                                                     class="absolute inset-x-0 inset-y-0 z-10 p-0 pointer-events-none" x-cloak>
                                                    @if($inlineViewMode === 'diff')
                                                        <div class="flex flex-col h-full bg-[#1e1f2b]/95 backdrop-blur-xl shadow-2xl rounded-2xl border border-primary/30 mx-6 my-1 overflow-hidden font-mono text-[13px]">
                                                            <div class="bg-red-500/10 text-red-500/60 line-through px-6 py-2 border-b border-white/5 leading-relaxed"><code>{{ $suggestion->original_content }}</code></div>
                                                            <div class="bg-emerald-500/10 text-emerald-500 px-6 py-2 font-black leading-relaxed"><code>{{ $suggestion->suggested_content }}</code></div>
                                                            <div class="bg-[#0f111a]/50 text-on-surface-variant/50 px-6 py-2 text-[10px] italic flex items-center gap-3 border-t border-white/5 leading-none">
                                                                <span class="w-1 h-1 rounded-full bg-primary/40"></span>
                                                                {{ $suggestion->description }}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="h-full bg-primary/10 text-primary px-8 border-l-4 border-primary shadow-[inset_0_0_20px_rgba(var(--primary-rgb),0.1)] font-black flex items-center justify-between mx-6 my-1 rounded-r-2xl">
                                                            <code>{{ $suggestion->suggested_content }}</code>
                                                            <div class="px-3 py-1 text-[9px] bg-primary text-on-primary rounded-lg font-black uppercase tracking-[0.2em] shadow-xl">{{ __('Improved') }}</div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                            
                                            <code class="relative z-0 block text-[#abb2bf] font-medium tracking-tight">@php echo $hLine @endphp</code>
                                            
                                            <button wire:click="selectLine({{ $snippet->id }}, {{ $num }})" class="absolute right-6 top-1/2 -translate-y-1/2 opacity-0 group-hover/line:opacity-100 p-2 text-primary/40 hover:text-primary transition-all hover:scale-125 focus:outline-none">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                    
                                    @if($activeLine == $activeId)
                                        <tr x-cloak>
                                            <td class="bg-primary/[0.03]"></td>
                                            <td class="py-12 pr-12 pl-8 bg-primary/[0.03]">
                                                <div class="bg-[#1e1f2b] p-10 rounded-[2.5rem] border border-primary/40 shadow-[0_30px_70px_rgba(var(--primary-rgb),0.2)] space-y-8 animate-scale-up relative overflow-hidden group/replybox">
                                                    <div class="absolute -top-12 -right-12 w-48 h-48 bg-primary/5 blur-[60px] rounded-full pointer-events-none"></div>
                                                    
                                                    <header class="flex items-center justify-between relative">
                                                        <div class="flex items-center gap-4">
                                                            <div class="w-2.5 h-2.5 rounded-full bg-primary animate-pulse shadow-[0_0_10px_rgba(var(--primary-rgb),0.8)]"></div>
                                                            <h3 class="text-[11px] font-black uppercase tracking-[0.3em] text-primary">{{ __('Architecture Insight') }} — {{ __('Line') }} {{ $num }}</h3>
                                                        </div>
                                                        <button @click="activeLine = null" class="p-2.5 rounded-2xl bg-white/5 text-on-surface-variant/40 hover:text-error hover:bg-error/10 transition-all focus:outline-none active:scale-95"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                                    </header>
                                                    
                                                    <x-ui.textarea wire:model="commentContent" placeholder="{{ __('What could be improved here?') }}" />
                                                    
                                                    <div class="flex justify-end gap-6 items-center relative">
                                                        <button @click="activeLine = null" class="text-[9px] font-black uppercase tracking-[0.4em] text-on-surface-variant/60 hover:text-on-surface transition-colors">{{ __('Discard') }}</button>
                                                        <x-ui.button wire:click="saveComment" variant="primary" size="lg" icon="chat-bubble-left-ellipsis">{{ __('Submit Review') }}</x-ui.button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif

                                    @foreach($snippet->reviews->where('line_number', $num) as $review)
                                        <tr class="bg-white/[0.01] border-l-4 border-primary/20 hover:bg-white/[0.02] transition-colors" x-cloak>
                                            <td class="border-r border-white/5 opacity-10 text-center italic text-[9px] pt-6 font-black uppercase tracking-widest text-primary">Rev</td>
                                            <td class="py-8 px-12">
                                                <div class="flex items-start justify-between group/review">
                                                    <div class="flex gap-8">
                                                        <div class="relative flex-shrink-0">
                                                            <img src="{{ $review->user->avatar }}" class="w-12 h-12 rounded-[1.25rem] border-2 border-white/5 shadow-2xl cover scale-110 group-hover/review:scale-100 transition-transform duration-500">
                                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-primary rounded-[0.5rem] border-2 border-[#0d0e12] flex items-center justify-center shadow-lg">
                                                                <svg class="w-2.5 h-2.5 text-on-primary" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z"/></svg>
                                                            </div>
                                                        </div>
                                                        <div class="space-y-3">
                                                            <div class="flex items-center gap-4">
                                                                <span class="font-black text-base text-on-surface tracking-tighter">{{ $review->user->name }}</span>
                                                                <div class="w-1 h-1 rounded-full bg-white/10"></div>
                                                                <span class="text-[10px] font-black text-on-surface-variant/30 uppercase tracking-[0.2em]">{{ $review->created_at->diffForHumans() }}</span>
                                                            </div>
                                                            <div class="relative pl-6">
                                                                <div class="absolute left-0 top-0 bottom-0 w-0.5 bg-gradient-to-b from-primary/30 via-primary/10 to-transparent rounded-full"></div>
                                                                <p class="text-[15px] text-on-surface-variant font-medium leading-relaxed max-w-3xl opacity-90">{{ $review->content }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @can('delete', $review)
                                                        <button wire:click="deleteReview({{ $review->id }})" class="opacity-0 group-hover/review:opacity-100 p-3.5 rounded-2xl bg-error/5 border border-error/20 text-error hover:bg-error hover:text-on-error transition-all shadow-2xl scale-90 hover:scale-100 hover:rotate-6 active:scale-95"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="bg-[#1a1b26] rounded-[3rem] p-24 text-center border border-white/5 opacity-40">
                        <div class="text-6xl mb-8">👻</div>
                        <h3 class="text-2xl font-black text-on-surface uppercase tracking-tighter">{{ __('No files found for this version.') }}</h3>
                    </div>
                @endforelse
            </div>

            <!-- Full Reviews Feed -->
            @if($post->fullReviews->count() > 0)
                <div class="space-y-12 pt-24">
                    <div class="flex items-center gap-8">
                        <div class="h-px flex-1 bg-gradient-to-r from-transparent via-white/5 to-transparent"></div>
                        <h2 class="text-3xl font-black font-display text-on-surface flex items-center gap-4 uppercase tracking-[0.1em] opacity-80">
                            <span class="text-primary opacity-40">#</span> {{ __('Full Implementation Reviews') }}
                        </h2>
                        <div class="h-px flex-1 bg-gradient-to-r from-transparent via-white/5 to-transparent"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-12">
                        @foreach($post->fullReviews as $fullReview)
                            <div class="bg-[#1a1b26] rounded-[3rem] p-12 border border-white/5 shadow-[0_30px_80px_rgba(0,0,0,0.4)] group/fr transition-all hover:border-primary/30 relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-80 h-80 bg-primary/5 blur-[100px] pointer-events-none group-hover/fr:bg-primary/10 transition-all duration-1000"></div>
                                
                                <header class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 relative">
                                    <div class="flex items-center gap-6">
                                        <div class="relative">
                                            <img src="{{ $fullReview->user->avatar }}" class="w-16 h-16 rounded-[1.5rem] border-2 border-primary/20 group-hover/fr:border-primary transition-all duration-700 shadow-2xl object-cover">
                                            <div class="absolute -top-3 -left-3 bg-primary w-7 h-7 rounded-xl border-4 border-[#1a1b26] flex items-center justify-center shadow-xl">
                                                <svg class="w-3.5 h-3.5 text-on-primary" fill="currentColor" viewBox="0 0 20 20"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"/></svg>
                                            </div>
                                        </div>
                                        <div class="space-y-1">
                                            <h4 class="text-2xl font-black text-on-surface tracking-tighter">{{ $fullReview->user->name }}</h4>
                                            <div class="flex items-center gap-4">
                                                <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-on-surface-variant/40">{{ $fullReview->created_at->format('M d, Y') }}</span>
                                                <div class="w-1.5 h-1.5 rounded-full bg-primary/30 shadow-[0_0_8px_rgba(var(--primary-rgb),0.4)]"></div>
                                                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-primary">{{ count($fullReview->modifiedSnippets) }} modules revised</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button class="px-8 py-4 rounded-2xl bg-[#1e1f2b] border border-white/5 text-xs font-black text-on-surface-variant hover:text-primary hover:border-primary/50 hover:bg-primary/5 transition-all shadow-2xl flex items-center gap-4 active:scale-95 group/vote border-b-4 border-b-primary/10">
                                            <span class="text-xl group-hover/vote:-translate-y-1.5 transition-transform duration-300">🔼</span>
                                            <span class="font-display text-2xl tracking-tighter text-on-surface">{{ $fullReview->score }}</span>
                                        </button>
                                        @can('delete', $fullReview)
                                            <button wire:click="deleteReview({{ $fullReview->id }})" class="p-4 rounded-2xl border border-error/20 bg-error/5 text-error hover:bg-error hover:text-on-error transition-all shadow-2xl active:scale-90"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                        @endcan
                                    </div>
                                </header>
                                
                                <div class="relative mb-12 pl-10 border-l-2 border-primary/20">
                                    <p class="text-xl text-on-surface-variant leading-relaxed font-medium italic opacity-90">{{ $fullReview->description }}</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($fullReview->modifiedSnippets as $modSnippet)
                                        <div class="bg-[#1e1f2b] rounded-3xl p-6 border border-white/5 group/mod cursor-pointer hover:border-primary transition-all shadow-xl hover:-translate-y-2 relative overflow-hidden">
                                            <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-primary/5 blur-2xl rounded-full opacity-0 group-hover/mod:opacity-100 transition-opacity"></div>
                                            <div class="flex items-center justify-between mb-4 relative">
                                                <div class="flex items-center gap-4 overflow-hidden">
                                                    <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center text-[10px] font-black text-primary border border-primary/20 shadow-lg">
                                                        {{ $loop->iteration }}
                                                    </div>
                                                    <span class="text-xs font-black font-mono text-on-surface truncate tracking-tight">{{ $modSnippet->snippet->filename ?: $modSnippet->snippet->name }}</span>
                                                </div>
                                                <svg class="w-5 h-5 text-white/5 group-hover/mod:text-primary transition-all group-hover/mod:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                            </div>
                                            <p class="text-[11px] text-on-surface-variant line-clamp-2 px-1 opacity-60 leading-relaxed font-medium">{{ $modSnippet->description ?: __('No specific notes for this file.') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Global Discussion Section -->
            <div class="space-y-16 pt-32 border-t border-white/5">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div class="space-y-2">
                        <h2 class="text-4xl font-black font-display text-on-surface flex items-center gap-6 uppercase tracking-tighter">
                            <span class="p-3 rounded-2xl bg-primary/10 text-primary shadow-xl shadow-primary/20">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6c0 .403.119.778.324 1.091s.496.547.822.685z"/></svg>
                            </span>
                            {{ __('Community Discussion') }}
                        </h2>
                        <p class="pl-20 text-[10px] font-black uppercase tracking-[0.4em] text-on-surface-variant/40 italic">{{ $post->comments->count() }} specialized insights shared</p>
                    </div>
                </div>

                <div class="bg-[#1a1b26] rounded-[3.5rem] p-12 md:p-16 border border-white/5 shadow-[0_50px_120px_rgba(0,0,0,0.6)] space-y-16 relative overflow-hidden group/chat">
                    <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-primary/50 to-transparent scale-x-75 group-hover/chat:scale-x-100 transition-transform duration-1000"></div>
                    
                    <!-- Comment Input -->
                    <div class="space-y-8 relative group/comment-input">
                        <div class="flex items-center gap-6 mb-2">
                             @if(auth()->check())
                                <img src="{{ auth()->user()->avatar }}" class="w-12 h-12 rounded-[1.25rem] border-2 border-white/5 shadow-2xl scale-110 group-focus-within/comment-input:scale-100 transition-transform">
                             @endif
                            <div class="flex flex-col">
                                <span class="text-[11px] font-black uppercase tracking-[0.3em] text-primary">{{ __('Advanced Feedback') }}</span>
                                <span class="text-[9px] font-bold text-on-surface-variant/40 uppercase tracking-widest">{{ __('Pro architectural critique') }}</span>
                            </div>
                        </div>
                        <div class="relative">
                            <x-ui.textarea wire:model="globalCommentContent" placeholder="{{ __('Start the conversation on this implementation...') }}" />
                            <div class="absolute -bottom-2 -right-2 w-32 h-32 bg-primary/5 blur-[50px] pointer-events-none rounded-full"></div>
                        </div>
                        <div class="flex justify-end pt-2 relative">
                            <x-ui.button wire:click="saveGlobalComment" variant="primary" size="lg" icon="paper-airplane">{{ __('Publish Critique') }}</x-ui.button>
                        </div>
                    </div>

                    <!-- Comments List -->
                    <div class="space-y-16 pt-16 border-t border-white/5">
                        @forelse($post->comments as $comment)
                            <div class="relative group/maincmt {{ $comment->is_pinned ? 'bg-primary/[0.04] border border-primary/20 rounded-[2.5rem] p-10 transition-all shadow-[0_20px_50px_rgba(var(--primary-rgb),0.1)]' : '' }}">
                                @if($comment->is_pinned)
                                    <div class="absolute -top-5 left-12 px-5 py-2 bg-primary text-[10px] font-black text-on-primary rounded-2xl uppercase tracking-[0.3em] shadow-[0_15px_40px_rgba(var(--primary-rgb),0.5)] flex items-center gap-3 z-10 animate-bounce-twice">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                                        {{ __('Master Insight') }}
                                    </div>
                                @endif

                                <div class="flex items-start gap-10">
                                    <div class="flex-shrink-0 relative group/avatar">
                                        <div class="absolute inset-0 bg-primary/20 blur-[20px] rounded-full scale-0 group-hover/avatar:scale-110 transition-transform duration-700"></div>
                                        <img src="{{ $comment->user->avatar }}" class="w-16 h-16 rounded-[1.75rem] border-2 border-white/5 shadow-2xl object-cover relative z-10">
                                    </div>
                                    <div class="flex-1 space-y-6">
                                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                            <div class="flex items-center gap-5">
                                                <h4 class="font-black text-xl text-on-surface tracking-tighter">{{ $comment->user->name }}</h4>
                                                <div class="h-1.5 w-1.5 rounded-full bg-white/10"></div>
                                                <span class="text-[10px] font-black text-on-surface-variant/30 uppercase tracking-[0.3em]">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                @php $hasLiked = $comment->reactions->where('user_id', auth()->id())->where('type', 'like')->count() > 0; @endphp
                                                <button wire:click="toggleCommentLike({{ $comment->id }})" 
                                                        class="flex items-center gap-3 px-5 py-2.5 rounded-2xl transition-all shadow-2xl active:scale-90 border-2 {{ $hasLiked ? 'bg-primary/20 border-primary/50 text-primary shadow-primary/20' : 'bg-[#1e1f2b] border-white/5 text-on-surface-variant hover:border-primary/40' }} group/lbtn">
                                                    <span class="text-xl transition-transform group-hover/lbtn:scale-125 {{ $hasLiked ? 'animate-bounce-twice' : 'opacity-40 grayscale group-hover/lbtn:grayscale-0 group-hover/lbtn:opacity-100' }}">{{ $hasLiked ? '❤' : '🤍' }}</span>
                                                    <span class="font-display font-black text-lg tracking-tighter">{{ $comment->reactions->count() }}</span>
                                                </button>
                                                @if($post->group && auth()->id() === $post->group->owner_id)
                                                    <button wire:click="pinComment({{ $comment->id }})" class="p-4 rounded-2xl bg-[#1e1f2b] border border-white/5 text-on-surface-variant hover:text-primary transition-all shadow-2xl opacity-0 group-hover/maincmt:opacity-100 flex items-center justify-center focus:outline-none">
                                                        <svg class="w-5 h-5 {{ $comment->is_pinned ? 'fill-primary text-primary' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="relative p-6 bg-[#000000]/10 rounded-[2rem] border border-white/[0.02]">
                                            <p class="text-on-surface-variant text-[16px] leading-relaxed max-w-2xl font-medium opacity-90">{{ $comment->content }}</p>
                                        </div>
                                        
                                        <div class="flex items-center gap-8 pl-4">
                                            <button @click="replyTo = (replyTo === {{ $comment->id }} ? null : {{ $comment->id }}); if(replyTo) { $nextTick(() => $el.closest('.group\\/maincmt').querySelector('textarea')?.focus()) }" 
                                                    class="text-[10px] font-black uppercase tracking-[0.4em] text-primary hover:text-primary-light transition-all flex items-center gap-3 focus:outline-none group/rply-lnk">
                                                <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center group-hover/rply-lnk:scale-110 transition-transform">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                                </div>
                                                {{ __('Reply') }}
                                            </button>
                                        </div>

                                        <!-- Nested Reply Input -->
                                        <div x-show="replyTo === {{ $comment->id }}" x-transition x-cloak class="pt-8 animate-slide-up relative">
                                            <div class="bg-[#1e1f2b] p-10 rounded-[2.5rem] border border-primary/30 shadow-[0_30px_60px_rgba(0,0,0,0.5)] space-y-6 relative overflow-hidden group/nest">
                                                <x-ui.textarea wire:model="globalCommentContent" placeholder="{{ __('What is your take on this critique?') }}" />
                                                <div class="flex justify-end gap-6 items-center pt-2 relative">
                                                    <button @click="replyTo = null" class="text-[9px] font-black uppercase tracking-[0.4em] text-on-surface-variant hover:text-error transition-all">{{ __('Discard') }}</button>
                                                    <x-ui.button wire:click="saveGlobalComment" @click="replyTo = null" variant="primary" size="lg" icon="chat-bubble-bottom-center-text">{{ __('Reply') }}</x-ui.button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Replies -->
                                @if($comment->replies->count() > 0)
                                    <div class="ml-24 mt-12 pl-12 border-l-2 border-white/5 space-y-12 relative">
                                        @foreach($comment->replies as $reply)
                                            <div class="flex items-start gap-8 group/reply relative">
                                                <div class="flex-shrink-0 relative group/rpl-av">
                                                    <img src="{{ $reply->user->avatar }}" class="w-12 h-12 rounded-[1.25rem] border-2 border-white/5 shadow-2xl object-cover relative z-10 group-hover/rpl-av:scale-95 transition-transform">
                                                </div>
                                                <div class="flex-1 space-y-3">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center gap-4">
                                                            <h5 class="font-black text-base text-on-surface tracking-tight">{{ $reply->user->name }}</h5>
                                                            <div class="w-1 h-1 rounded-full bg-white/5"></div>
                                                            <span class="text-[9px] font-black text-on-surface-variant/30 uppercase tracking-[0.3em]">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <div class="flex items-center gap-3">
                                                             @php $replyLiked = $reply->reactions->where('user_id', auth()->id())->where('type', 'like')->count() > 0; @endphp
                                                            <button wire:click="toggleCommentLike({{ $reply->id }})" 
                                                                    class="flex items-center gap-2.5 px-4 py-2 rounded-2xl transition-all border-2 scale-90 active:scale-75 {{ $replyLiked ? 'bg-primary/10 border-primary/40 text-primary shadow-lg shadow-primary/10' : 'bg-[#1e1f2b] border-white/5 text-on-surface-variant hover:border-primary/30' }}">
                                                                <span class="text-base {{ $replyLiked ? 'animate-bounce-twice' : 'opacity-40 grayscale' }}">{{ $replyLiked ? '❤' : '🤍' }}</span>
                                                                <span class="font-display font-black text-base tracking-tighter">{{ $reply->reactions->count() }}</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <p class="text-[14.5px] text-on-surface-variant leading-relaxed opacity-90 font-medium">{{ $reply->content }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-32 text-center space-y-10 animate-fade-in opacity-40 group-hover/chat:opacity-60 transition-opacity duration-1000">
                                <div class="relative">
                                    <div class="w-32 h-32 rounded-[3.5rem] bg-[#1e1f2b] flex items-center justify-center border-2 border-white/5 text-6xl shadow-2xl relative z-10">
                                        💬
                                    </div>
                                    <div class="absolute inset-0 bg-primary/20 blur-[60px] rounded-full scale-75 animate-pulse"></div>
                                </div>
                                <div class="space-y-4 max-w-sm">
                                    <h4 class="text-3xl font-black text-on-surface uppercase tracking-tighter leading-none">{{ __('Be the first to comment') }}</h4>
                                    <p class="text-xs font-black uppercase tracking-[0.3em] text-primary/60 leading-relaxed">{{ __('Launch the first architectural critique of this implementation.') }}</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-12 lg:sticky lg:top-16 self-start">
            <div class="bg-[#1a1b26] rounded-[3.5rem] p-12 border border-white/5 shadow-[0_40px_100px_rgba(0,0,0,0.5)] relative overflow-hidden group/sidebar">
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-primary/5 blur-[80px] rounded-full pointer-events-none group-hover/sidebar:bg-primary/10 transition-all duration-1000"></div>
                
                <div class="flex items-center justify-between mb-12">
                     <h3 class="font-black text-2xl text-on-surface uppercase tracking-[0.1em]">{{ __('Core Impact') }}</h3>
                     <div class="relative flex items-center justify-center">
                         <div class="absolute w-6 h-6 bg-primary/20 rounded-full animate-ping"></div>
                         <div class="w-2.5 h-2.5 rounded-full bg-primary shadow-[0_0_10px_#bec2ff] relative z-10"></div>
                     </div>
                </div>
                
                <div class="grid grid-cols-2 gap-6 relative">
                    @foreach(['clean' => '✨', 'optimisable' => '🚀', 'mindblown' => '🤯', 'security' => '🛡️'] as $type => $emoji)
                        @php $reacted = $post->reactions->where('user_id', auth()->id())->where('type', $type)->count() > 0; @endphp
                        <button wire:click="react('{{ $type }}')" class="group/react flex flex-col items-center p-8 rounded-[2.5rem] border-2 transition-all duration-500 shadow-xl {{ $reacted ? 'bg-primary/20 border-primary shadow-[0_25px_50px_rgba(var(--primary-rgb),0.3)] scale-105' : 'bg-[#1e1f2b] border-white/5 hover:border-primary/50' }} hover:-translate-y-2 active:scale-95">
                            <span class="text-5xl mb-6 group-hover/react:scale-125 transition-transform duration-500">{{ $emoji }}</span>
                            <span class="text-[10px] font-black uppercase tracking-[0.25em] text-on-surface-variant group-hover/react:text-primary transition-colors text-center leading-tight">{{ __($type) }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Author Card -->
            <div class="bg-[#1a1b26] rounded-[3.5rem] p-12 border border-white/5 shadow-[0_40px_100px_rgba(0,0,0,0.5)] group/author relative overflow-hidden transition-all hover:border-primary/30 active:scale-[0.98]">
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-primary/5 blur-[80px] rounded-full group-hover/author:bg-primary/10 transition-all duration-700"></div>
                
                <div class="flex items-center gap-8 relative">
                    <img src="{{ $post->user->avatar }}" class="w-20 h-20 rounded-[1.75rem] border-2 border-white/10 group-hover/author:border-primary transition-all duration-500 shadow-2xl object-cover relative z-10">
                    <div class="space-y-1.5">
                        <h3 class="font-black text-2xl text-on-surface leading-tight tracking-tighter">{{ $post->user->name }}</h3>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-primary bg-primary/10 px-4 py-1.5 rounded-xl border border-primary/20 inline-block shadow-sm">Pro Contributor</p>
                    </div>
                </div>
                <div class="mt-12 space-y-8 relative">
                    <p class="text-base text-on-surface-variant leading-relaxed font-medium opacity-80 italic">
                        {{ $post->user->bio ?: 'Enterprise architect & performance specialist pushing for zero-latency code.' }}
                    </p>
                    <div class="flex items-center justify-between pt-10 border-t border-white/5 relative">
                        <div class="text-center">
                            <p class="text-[10px] font-black uppercase tracking-[0.4em] text-on-surface-variant/40 mb-3">Impact</p>
                            <p class="font-display font-black text-4xl text-primary">{{ $post->user->reputation_score }}</p>
                        </div>
                        <div class="h-12 w-px bg-white/5 opacity-40"></div>
                        <div class="text-center">
                            <p class="text-[10px] font-black uppercase tracking-[0.4em] text-on-surface-variant/40 mb-3">Manifests</p>
                            <p class="font-display font-black text-4xl text-on-surface">{{ $post->user->posts->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inline Suggestion Modal -->
    @if($suggestingLine)
        <div class="fixed inset-0 z-[150] flex items-center justify-center p-6 bg-[#000000]/90 backdrop-blur-[60px] animate-fade-in" @keydown.escape.window="$set('suggestingLine', null)">
            <div class="bg-[#1a1b26] rounded-[4rem] border border-primary/40 shadow-[0_60px_150px_rgba(0,0,0,0.9)] w-full max-w-6xl overflow-hidden relative group/modal scroll-smooth" @click.away="$set('suggestingLine', null)">
                <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/10 blur-[150px] pointer-events-none group-hover/modal:bg-primary/20 transition-all duration-1000"></div>
                
                <header class="px-16 py-12 bg-[#1e1f2b] border-b border-white/5 flex items-center justify-between relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-2 bg-primary"></div>
                    <div>
                        <div class="flex items-center gap-8 mb-3">
                             <div class="w-4 h-4 rounded-full bg-primary animate-pulse shadow-[0_0_15px_rgba(var(--primary-rgb),0.8)]"></div>
                             <h2 class="text-4xl font-black font-display text-on-surface uppercase tracking-tighter">{{ __('Suggest Refactoring') }}</h2>
                        </div>
                        <p class="text-[11px] text-primary/60 font-black uppercase tracking-[0.5em] font-mono pl-12 flex items-center gap-4">
                            <span>Target: Line {{ $suggestingLine }}</span>
                        </p>
                    </div>
                    <button wire:click="$set('suggestingLine', null)" class="p-5 rounded-[2rem] bg-white/[0.03] border border-white/5 text-on-surface-variant/20 hover:text-error hover:bg-error/10 hover:border-error/20 transition-all active:scale-90 focus:outline-none"><svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </header>

                <div class="p-16 space-y-16 relative">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                        <div class="space-y-6">
                            <div class="bg-[#0f111a] border border-red-500/20 p-10 rounded-[3rem] font-mono text-[14px] text-red-500/50 line-through leading-[2.2rem] shadow-[inset_0_4px_20px_rgba(0,0,0,0.4)]">
                                {{ $originalContent }}
                            </div>
                        </div>
                        <div class="space-y-6">
                            <textarea wire:model="suggestedContent" 
                                      class="w-full bg-[#0d0e12] border-2 border-primary/20 rounded-[3rem] p-10 font-mono text-[14px] text-on-surface focus:ring-1 focus:ring-primary/40 shadow-[0_30px_60px_rgba(0,0,0,0.4)] group-hover/edit:border-primary/50 transition-all duration-500 leading-[2.2rem]"
                                      rows="4"></textarea>
                        </div>
                    </div>

                    <div class="space-y-6 relative">
                        <x-ui.textarea wire:model="suggestionDescription" 
                                  placeholder="{{ __('Quantify the architectural or performance gains...') }}" />
                    </div>

                    <div class="flex items-center justify-between pt-12 border-t border-white/5 relative">
                        <button wire:click="$set('suggestingLine', null)" class="text-[11px] font-black uppercase tracking-[0.5em] text-on-surface-variant/40 hover:text-error transition-all duration-500 focus:outline-none">{{ __('Discard Task') }}</button>
                        <x-ui.button wire:click="saveInlineSuggestion" variant="primary" size="lg" icon="check-all">{{ __('Enforce Refactoring') }}</x-ui.button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
