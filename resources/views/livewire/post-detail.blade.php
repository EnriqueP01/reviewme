<div class="w-full max-w-none px-12 py-6" 
     x-data="{ 
        isReviewing: @entangle('isReviewing'),
        suggestingLine: @entangle('suggestingLine'),
        suggestingEndLine: @entangle('suggestingEndLine'),
        selectionPopup: { show: false, x: 0, y: 0, text: '', snippetId: null, start: null, end: null, original: '' },
        replyTo: @entangle('replyToId'),
        
        handleMouseUp(e) {
            if (@js($this->isAuthor())) return;
            if (e.target.closest('button') || e.target.closest('a') || e.target.closest('textarea')) return;
            const selection = window.getSelection();
            const text = selection.toString().trim();
            
            if (text.length > 0) {
                const range = selection.getRangeAt(0);
                const rect = range.getBoundingClientRect();
                const lineEl = selection.anchorNode.parentElement.closest('.group\\/line');
                const endLineEl = selection.focusNode.parentElement.closest('.group\\/line');
                
                if (lineEl && endLineEl) {
                    this.selectionPopup = {
                        show: true,
                        x: rect.left + (rect.width / 2),
                        y: rect.top - 10,
                        text: text,
                        snippetId: lineEl.getAttribute('data-snippet'),
                        start: Math.min(parseInt(lineEl.getAttribute('data-line')), parseInt(endLineEl.getAttribute('data-line'))),
                        end: Math.max(parseInt(lineEl.getAttribute('data-line')), parseInt(endLineEl.getAttribute('data-line'))),
                        original: text
                    };
                }
            } else {
                this.selectionPopup.show = false;
            }
        },

        startSuggestion() {
            $wire.setInlineSuggestion(this.selectionPopup.snippetId, this.selectionPopup.start, this.selectionPopup.end, this.selectionPopup.original);
            this.selectionPopup.show = false;
        }
     }"
     class="w-full space-y-8"
>
    <!-- Status Messages -->
    <div class="fixed top-24 left-1/2 transform -translate-x-1/2 z-[100] space-y-2 w-full max-w-sm pointer-events-none">
        @if (session()->has('success'))
            <div class="bg-emerald-500/90 backdrop-blur-md text-white px-6 py-4 rounded-2xl shadow-2xl border border-emerald-400/50 flex items-center justify-between pointer-events-auto animate-fade-in">
                <span class="text-xs font-black uppercase tracking-widest">{{ session('success') }}</span>
                <button @click="$el.closest('div').remove()" class="ml-4 opacity-50 hover:opacity-100 italic text-[10px]">Close</button>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-500/90 backdrop-blur-md text-white px-6 py-4 rounded-2xl shadow-2xl border border-red-400/50 flex items-center justify-between pointer-events-auto animate-fade-in">
                <span class="text-xs font-black uppercase tracking-widest">{{ session('error') }}</span>
                <button @click="$el.closest('div').remove()" class="ml-4 opacity-50 hover:opacity-100 italic text-[10px]">Close</button>
            </div>
        @endif
    </div>

    <!-- Inline Selection Popup -->
    <div x-show="selectionPopup.show" x-cloak x-transition
         @click.away="selectionPopup.show = false"
         class="fixed z-[9999] transform -translate-x-1/2 -translate-y-full bg-[#1a1b26] border border-primary/50 rounded-xl px-3 py-1.5 shadow-2xl flex items-center gap-2 pointer-events-auto backdrop-blur-md"
         :style="`left: ${selectionPopup.x}px; top: ${selectionPopup.y}px;`">
        <button @click.stop="startSuggestion()" class="text-[10px] font-bold text-primary hover:text-primary-light uppercase tracking-widest flex items-center gap-1.5 focus:outline-none">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
            {{ __('Suggest Refactoring') }}
        </button>
    </div>

    <div class="w-full space-y-8" @mouseup="handleMouseUp($event)">
        <!-- Header Monolith -->
        <div class="bg-surface-container-low/40 backdrop-blur-2xl border border-white/5 rounded-[2.5rem] p-8 flex items-center justify-between gap-6 shadow-2xl relative overflow-hidden group/header">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 to-transparent opacity-0 group-hover/header:opacity-100 transition-opacity pointer-events-none"></div>
            <div class="flex items-center gap-6 relative">
                <div class="relative group/avatar">
                    <div class="w-14 h-14 rounded-2xl bg-surface-container-highest border border-primary/10 flex items-center justify-center text-xl font-black font-display text-primary italic overflow-hidden transition-all duration-500 group-hover/avatar:scale-105 shadow-xl">
                        <img src="{{ $post->user->avatar }}" class="w-full h-full object-cover">
                    </div>
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center gap-3">
                        <span class="text-lg font-black text-on-surface tracking-tight">{{ $post->user->name }}</span>
                        <span class="text-xs font-mono font-bold text-primary tracking-wider font-black">@<span>{{ $post->user->name }}</span></span>
                    </div>
                    <div class="flex items-center gap-3 mt-1">
                        @php
                            $lensColors = [
                        <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest {{ $color['bg'] }} {{ $color['text'] }} border {{ $color['border'] }}">{{ $post->lens ?: 'Logic' }}</span>
                        <span class="text-[10px] uppercase tracking-[0.2em] text-on-surface-variant font-black opacity-40 flex items-center gap-2">
                            {{ $post->created_at->diffForHumans() }}
                        </span>
                        <span class="text-on-surface-variant/40 text-[10px] font-black uppercase tracking-[0.2em] font-mono">/ {{ $post->snippets->count() }} FILES</span>
                    </div>
                    <h1 class="text-2xl font-black text-on-surface tracking-tighter mt-2">{{ $post->title }}</h1>
                </div>
            </div>
            
            <div class="flex items-center gap-6 relative">
                <!-- Version Selector -->
                <div class="flex items-center gap-2 bg-black/20 rounded-2xl p-1.5 border border-white/5">
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/40 px-3">{{ __('Version') }}</span>
                    @php $maxVersion = $post->snippets->max('version_number'); @endphp
                    <select wire:model.live="selectedVersion" class="bg-surface-container-highest border-none rounded-xl text-xs font-black text-primary py-2 pl-4 pr-10 focus:ring-1 focus:ring-primary/20 outline-none cursor-pointer">
                        @for($v = 1; $v <= $maxVersion; $v++)
                            <option value="{{ $v }}">V{{ $v }} {{ $v == $maxVersion ? __('(Latest)') : '' }}</option>
                        @endfor
                    </select>
                </div>

                @if($this->isAuthor())
                    <!-- Author Actions -->
                    <a href="{{ route('posts.update', $post->id) }}" class="whitespace-nowrap rounded-2xl px-8 py-4 font-black uppercase text-xs tracking-widest shadow-2xl bg-primary text-white hover:scale-105 active:scale-95 transition-all flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        {{ __('Update Code') }}
                    </a>
                @else
                    <!-- Reviewer Actions -->
                    <x-ui.button wire:click="toggleReviewMode" variant="{{ $isReviewing ? 'primary' : 'ghost' }}" class="whitespace-nowrap rounded-2xl px-8 py-4 font-black uppercase text-xs tracking-widest shadow-2xl">
                        {{ $isReviewing ? __('Cancel') : __('Make Full Review') }}
                    </x-ui.button>
                @endif
            </div>
        </div>

        @if($isReviewing)
            <!-- Make Full Review (Antigravity HUD) -->
            <template x-teleport="body">
                <div class="fixed inset-0 z-[2000] flex items-center justify-center bg-black/80 backdrop-blur-2xl px-4 py-8 overflow-hidden" @click.self="toggleReviewMode">
                    <div class="bg-surface-container-highest border border-white/10 w-full max-w-5xl rounded-[2.5rem] shadow-[0_0_120px_rgba(0,0,0,1)] flex flex-col max-h-[90vh] overflow-hidden relative animate-in zoom-in-95 duration-300">
                        <!-- Header -->
                        <div class="px-8 py-5 border-b border-white/5 flex items-center justify-between shrink-0 bg-white/[0.02]">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary shadow-inner">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                                <h2 class="text-xs font-black uppercase tracking-widest text-on-surface">{{ __('Full Review Form') }}</h2>
                            </div>
                            <button wire:click="toggleReviewMode" class="p-2 rounded-xl hover:bg-white/5 text-on-surface-variant hover:text-white transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2.5" stroke-linecap="round"/></svg>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar space-y-8">
                            <!-- Global Eval -->
                            <div class="space-y-3">
                                <label class="px-1 text-[9px] font-black uppercase tracking-[0.4em] text-primary/60">{{ __('Summary') }}</label>
                                <textarea wire:model="reviewDescription" placeholder="{{ __('Overall thoughts on the code...') }}" 
                                          class="w-full bg-black/20 border border-white/5 rounded-2xl p-6 text-sm text-on-surface focus:border-primary/20 min-h-[120px] outline-none transition-all shadow-inner custom-scrollbar resize-none font-medium italic"></textarea>
                            </div>

                            <!-- Fragment Revisions -->
                            <div class="space-y-4">
                                <label class="px-1 text-[9px] font-black uppercase tracking-[0.4em] text-on-surface-variant/40">{{ __('Files:') }}</label>
                                <div class="grid grid-cols-1 gap-6">
                                    @foreach($post->snippets as $snippet)
                                        <div class="bg-black/20 rounded-[2rem] border border-white/5 overflow-hidden flex flex-col group/snippet shadow-xl transition-all hover:border-white/10">
                                            <div class="px-8 py-4 bg-white/[0.03] border-b border-white/5 flex items-center justify-between">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-2.5 h-2.5 rounded-full bg-primary/40 group-hover/snippet:bg-primary transition-colors"></div>
                                                    <span class="text-[10px] font-mono font-black text-on-surface/80 tracking-widest uppercase">{{ $snippet->filename ?: 'module' }}</span>
                                                </div>
                                            </div>
                                            <div class="flex box-border bg-[#0d0e12] overflow-hidden">
                                                <!-- Gutter -->
                                                <div class="w-14 bg-white/[0.02] border-r border-white/5 flex flex-col py-6 items-end pr-4 select-none text-[9px] font-mono text-on-surface-variant/20 tracking-tighter">
                                                    @for($i = 1; $i <= max(1, count(explode("\n", $reviewFilesData[$snippet->id]['content'] ?? ''))); $i++)
                                                        <span class="h-6 flex items-center">{{ $i }}</span>
                                                    @endfor
                                                </div>
                                                <!-- Editor -->
                                                <textarea 
                                                    wire:model.live="reviewFilesData.{{ $snippet->id }}.content"
                                                    class="flex-1 bg-transparent border-none p-6 font-mono text-[11px] text-on-surface focus:ring-0 leading-relaxed custom-scrollbar resize-none selection:bg-primary/20"
                                                    spellcheck="false"
                                                    rows="{{ max(5, min(20, count(explode("\n", $reviewFilesData[$snippet->id]['content'] ?? '')))) }}"
                                                ></textarea>
                                            </div>
                                            <div class="px-8 py-5 bg-white/[0.01] border-t border-white/5">
                                                <div class="flex items-center gap-4">
                                                    <svg class="w-4 h-4 text-primary/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                                    <input type="text" wire:model="reviewFilesData.{{ $snippet->id }}.description" placeholder="{{ __('File specific notes...') }}" class="flex-1 bg-transparent border-none p-0 text-sm text-on-surface focus:ring-0 placeholder:text-on-surface-variant/20 font-medium italic">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-8 py-5 border-t border-white/5 flex justify-end items-center gap-8 shrink-0 bg-white/[0.02]">
                            <button wire:click="toggleReviewMode" class="text-[10px] font-black uppercase text-on-surface-variant hover:text-white transition-colors tracking-widest">{{ __('Cancel') }}</button>
                            <x-ui.button wire:click="saveFullReview" variant="primary" class="rounded-2xl px-12 py-5 shadow-[0_0_40px_rgba(190,194,255,0.2)] text-[10px] font-black uppercase tracking-widest">
                                {{ __('Submit Review') }}
                            </x-ui.button>
                        </div>
                    </div>
                </div>
            </template>
        @endif

        <!-- Code Block : Monolith HUD -->
        <div class="rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl relative" wire:key="code-block-v{{ $selectedVersion }}" @mouseup="handleMouseUp($event)">
            <x-ui.code-block 
                :snippets="$currentSnippets"
                :title="$post->title"
                :suggestions="$post->inlineSuggestions"
                :selectedVersion="$selectedVersion"
            />
        </div>

        <!-- Activity Feed (Reviews + Discussion) -->
        <div class="grid grid-cols-1 gap-16 pt-16">
            
            <!-- FULL REVIEWS SECTION -->
            @if($post->fullReviews->count() > 0)
                <div class="space-y-12">
                    <div class="flex items-center justify-between">
                        <h2 class="text-[10px] font-black uppercase tracking-[0.5em] text-on-surface-variant/20 flex items-center gap-6 flex-1">
                            {{ __('Full Reviews') }}
                            <div class="h-px flex-1 bg-gradient-to-r from-white/5 via-white/[0.02] to-transparent"></div>
                        </h2>
                    </div>

                    <div class="relative group/carousel overflow-hidden pb-12" 
                         x-data="{ 
                            current: @entangle('activeReviewIndex'), 
                            total: {{ $post->fullReviews->count() }},
                            next() { this.current = (this.current + 1) % this.total },
                            prev() { this.current = (this.current - 1 + this.total) % this.total }
                         }">
                        
                        <!-- Discrete Top Navigation Tabs -->
                        @if($post->fullReviews->count() > 1)
                            <div class="flex items-center justify-center gap-3 mb-6 z-[100] py-2">
                                @foreach($post->fullReviews as $frIndex => $fr)
                                    <button type="button" 
                                            @click="current = {{ $frIndex }}" 
                                            class="px-4 py-1.5 rounded-xl transition-all duration-300 relative flex flex-col items-center gap-0.5 group/tab"
                                            :class="current === {{ $frIndex }} ? 'bg-primary/10 border border-primary/20' : 'border border-transparent hover:bg-white/5'">
                                        <span class="text-[9px] font-bold uppercase tracking-[0.15em]" :class="current === {{ $frIndex }} ? 'text-primary' : 'text-on-surface-variant/30 group-hover/tab:text-on-surface-variant'">{{ __('Ref') }} #{{ $frIndex + 1 }}</span>
                                        <div class="w-8 h-0.5 rounded-full transition-all duration-500" :class="current === {{ $frIndex }} ? 'bg-primary' : 'bg-white/5'"></div>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                        
                        <!-- Permanent Sticky Side Navigation Paddles -->
                        @if($post->fullReviews->count() > 1)
                            <div @click="prev()" class="absolute top-0 bottom-0 left-0 w-24 z-50 cursor-pointer group/nav-left flex items-start justify-center">
                                <div class="sticky top-[50%] -translate-y-1/2 w-14 h-14 rounded-full bg-surface-container-high/40 border border-white/10 flex items-center justify-center text-primary shadow-[0_0_40px_rgba(190,194,255,0.1)] backdrop-blur-2xl hover:scale-110 hover:bg-primary hover:text-white hover:border-primary/40 transition-all duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                            </div>
                            <div @click="next()" class="absolute top-0 bottom-0 right-0 w-24 z-50 cursor-pointer group/nav-right flex items-start justify-center">
                                <div class="sticky top-[50%] -translate-y-1/2 w-14 h-14 rounded-full bg-surface-container-high/40 border border-white/10 flex items-center justify-center text-primary shadow-[0_0_40px_rgba(190,194,255,0.1)] backdrop-blur-2xl hover:scale-110 hover:bg-primary hover:text-white hover:border-primary/40 transition-all duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                            </div>
                        @endif

                        <div class="relative w-full overflow-hidden px-8">
                            <!-- Translation Slider Container -->
                            <div class="flex transition-transform duration-700 ease-in-out will-change-transform" 
                                 :style="'transform: translateX(-' + (current * 100) + '%)'">
                                
                                @foreach($post->fullReviews as $frIndex => $fr)
                                    <div wire:key="fr-view-v7-{{ $fr->id }}" class="w-full shrink-0 px-4">
                                        <div class="max-w-6xl mx-auto bg-surface-container-low rounded-[2.5rem] p-10 border border-white/5 shadow-2xl relative overflow-hidden flex flex-col gap-8">
                                            
                                            <!-- Review Header -->
                                            <div class="flex items-start justify-between gap-8">
                                                <div class="flex items-start gap-6">
                                                    <img src="{{ $fr->user->avatar }}" class="w-16 h-16 rounded-2xl border-2 border-primary/20 shadow-2xl object-cover">
                                                    <div class="space-y-4">
                                                        <div class="flex items-center gap-4">
                                                            <h3 class="text-2xl font-black text-on-surface tracking-tighter">{{ $fr->user->name }}</h3>
                                                            <span class="text-xs font-mono font-bold text-primary/60 tracking-wider">@<span>{{ $fr->user->name }}</span></span>
                                                            @if($fr->score)
                                                                <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest border border-primary/20">SCORE: {{ $fr->score }}/10</span>
                                                            @endif
                                                        </div>
                                                        <span class="text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/40 flex items-center gap-2">
                                                            <div class="w-1 h-1 rounded-full bg-primary/40"></div>
                                                            {{ $fr->created_at->diffForHumans() }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Reactions (Vibrant Styling matching Feed) -->
                                                <div class="flex items-center gap-3">
                                                    <div class="flex items-center gap-2 p-1 bg-black/40 rounded-2xl border border-white/5 shadow-inner">
                                                        <button type="button" 
                                                                wire:click="voteReview({{ $fr->id }}, 'up')" 
                                                                class="p-3 rounded-xl transition-all duration-300 {{ $fr->reactions()->where('user_id', auth()->id())->where('type', 'up')->exists() ? 'bg-emerald-500 text-white shadow-[0_0_20px_rgba(16,185,129,0.4)]' : 'text-on-surface-variant hover:text-emerald-400 hover:bg-emerald-500/10' }}">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                        </button>
                                                        <span class="px-2 text-sm font-black {{ ($fr->reactions()->where('type', 'up')->count() - $fr->reactions()->where('type', 'down')->count()) > 0 ? 'text-emerald-400' : 'text-on-surface' }}">{{ $fr->reactions()->where('type', 'up')->count() - $fr->reactions()->where('type', 'down')->count() }}</span>
                                                        <button type="button" 
                                                                wire:click="voteReview({{ $fr->id }}, 'down')" 
                                                                class="p-3 rounded-xl transition-all duration-300 {{ $fr->reactions()->where('user_id', auth()->id())->where('type', 'down')->exists() ? 'bg-rose-500 text-white shadow-[0_0_20px_rgba(244,63,94,0.4)]' : 'text-on-surface-variant hover:text-rose-400 hover:bg-rose-500/10' }}">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Review Description (Clean Text Only) -->
                                            <div class="text-lg font-bold text-on-surface leading-normal py-0">
                                                {{ $fr->description }}
                                            </div>

                                            <!-- Modified Files -->
                                            @if($fr->modifiedSnippets->count() > 0)
                                                <div class="space-y-6">
                                                    @foreach($fr->modifiedSnippets as $ms)
                                                        <div wire:key="ms-v7-{{ $ms->id }}" class="bg-[#0f111a] rounded-[2rem] border border-white/5 overflow-hidden shadow-2xl relative">
                                                            <div class="px-8 py-5 flex items-center justify-between bg-white/[0.03] border-b border-white/10">
                                                                <div class="flex items-center gap-4">
                                                                    <svg class="w-4 h-4 text-primary/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                                    <span class="text-[11px] font-mono font-black text-primary tracking-widest uppercase">{{ $ms->snippet->filename ?: ($ms->snippet->description ?: 'SOURCE_FRAGMENT') }}</span>
                                                                    @if($ms->description)
                                                                        <div class="w-px h-4 bg-white/10 mx-2"></div>
                                                                        <span class="text-xs font-medium text-on-surface/60 tracking-tight">{{ $ms->description }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <x-ui.syntax-highlighter :code="$ms->modified_content" :lang="$ms->snippet->language" maxHeight="max-h-[400px]" />
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- Discussion -->
                                            <div x-data="{ collapsed: true }">
                                                <div @click="collapsed = !collapsed" class="flex items-center justify-between px-6 py-4 bg-white/[0.04] rounded-[1.5rem] border border-white/10 cursor-pointer hover:bg-white/[0.08] transition-all group/node">
                                                    <div class="flex items-center gap-4">
                                                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover/node:bg-primary group-hover/node:text-white transition-all">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                        </div>
                                                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-on-surface">{{ __('Discussion') }} ({{ $fr->comments->count() }})</span>
                                                    </div>
                                                    <svg class="w-4 h-4 transition-transform duration-500" :class="collapsed ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                </div>

                                                <div x-show="!collapsed" x-collapse class="mt-8 space-y-8 pl-6">
                                                    <!-- Reply Input -->
                                                    <div class="flex items-center gap-4 bg-black/40 rounded-[2rem] p-3 border border-primary/20 shadow-inner">
                                                        <img src="{{ auth()->user()->avatar }}" class="w-10 h-10 rounded-2xl border border-white/10 object-cover">
                                                        <input type="text" wire:model="reviewCommentContent" wire:keydown.enter="saveGlobalComment(null, {{ $fr->id }})" placeholder="{{ __('Add a commentary...') }}" class="bg-transparent border-none flex-1 py-3 text-sm text-on-surface focus:ring-0 placeholder:text-on-surface-variant/20 font-semibold italic">
                                                        <button type="button" wire:click="saveGlobalComment(null, {{ $fr->id }})" class="p-3 rounded-2xl bg-primary text-white hover:scale-105 active:scale-95 transition-all">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 5l7 7m0 0l-7 7m7-7H3" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                        </button>
                                                    </div>

                                                    @foreach($fr->comments as $comment)
                                                        <div wire:key="fr-comment-v8-{{ $comment->id }}" class="flex gap-6 relative group/frc">
                                                            <div class="absolute left-6 top-12 bottom-0 w-px bg-white/5 group-hover/frc:bg-primary/20 transition-all"></div>
                                                            <img src="{{ $comment->user->avatar }}" class="w-12 h-12 rounded-2xl border border-white/10 shrink-0 relative z-10 bg-surface-container">
                                                            <div class="space-y-2 flex-1">
                                                                <div class="flex items-center gap-4">
                                                                    <span class="text-sm font-black text-on-surface">{{ $comment->user->name }}</span>
                                                                    <span class="text-[10px] font-mono font-bold text-primary/40">@<span>{{ $comment->user->name }}</span></span>
                                                                    <span class="text-[9px] font-black text-on-surface-variant/30 uppercase tracking-widest">{{ $comment->created_at->diffForHumans() }}</span>
                                                                </div>
                                                                <div class="text-sm text-on-surface/70 leading-relaxed">{{ $comment->content }}</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Bottom Navigation Traits -->
                        <div class="flex items-center justify-center gap-6 mt-4">
                            @foreach($post->fullReviews as $frIndex => $fr)
                                <button type="button" @click="current = {{ $frIndex }}" 
                                        class="w-32 h-1 rounded-full relative overflow-hidden transition-all duration-500"
                                        :class="current === {{ $frIndex }} ? 'h-2 bg-primary/20 ring-1 ring-primary/30' : 'bg-white/5 hover:bg-white/10'">
                                    <div class="absolute inset-0 bg-primary transition-transform duration-500 origin-left"
                                         :style="current === {{ $frIndex }} ? 'transform: scaleX(1)' : 'transform: scaleX(0)'"></div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- GLOBAL DISCUSSION SECTION -->
            <div class="space-y-12">
                <h2 class="text-[10px] font-black uppercase tracking-[0.5em] text-on-surface-variant/20 flex items-center gap-6">
                    {{ __('Discussion') }}
                    <div class="h-px flex-1 bg-gradient-to-r from-white/5 via-white/[0.02] to-transparent"></div>
                </h2>

                <div class="bg-[#1a1b26] rounded-[2.5rem] p-8 border border-white/5 shadow-2xl space-y-10">
                    <!-- Minimalist Input (Global) -->
                    <div class="relative pt-4">
                        <div class="flex items-center gap-4 bg-black/40 rounded-[2rem] p-3 border border-primary/20 focus-within:border-primary/60 transition-all shadow-[0_0_40px_rgba(190,194,255,0.02)]">
                            <img src="{{ Auth::user()->avatar }}" class="w-10 h-10 rounded-2xl ml-2 shadow-2xl border border-white/10 shrink-0">
                            <input type="text" 
                                   wire:model="globalCommentContent"
                                   wire:keydown.enter="saveGlobalComment"
                                   placeholder="{{ __('Add a commentary...') }}" 
                                   class="bg-transparent border-none flex-1 py-3 text-sm text-on-surface focus:ring-0 placeholder:text-on-surface-variant/20 font-semibold tracking-tight">
                            <button type="button" wire:click="saveGlobalComment" class="mr-2 p-3 rounded-2xl bg-primary text-white hover:scale-105 active:scale-95 transition-all shadow-[0_0_20px_rgba(190,194,255,0.2)]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M13 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Comments List -->
                    <div class="space-y-8">
                        @foreach($post->comments->whereNull('full_review_id')->whereNull('parent_id') as $comment)
                            <div wire:key="global-comment-v7-{{ $comment->id }}" class="flex gap-6 group/comment relative" x-data="{ replying: false }">
                                <div class="absolute left-6 top-12 bottom-0 w-px bg-gradient-to-b from-primary/20 via-primary/5 to-transparent"></div>
                                <img src="{{ $comment->user->avatar }}" class="w-12 h-12 rounded-2xl border border-white/10 shadow-2xl object-cover shrink-0 relative z-10 bg-surface-container">
                                <div class="flex-1 space-y-3">
                                    <div class="flex items-center gap-4">
                                        <span class="text-sm font-black text-on-surface leading-none">{{ $comment->user->name }}</span>
                                        <span class="text-[10px] font-mono font-bold text-primary/40 tracking-wider">@<span>{{ $comment->user->name }}</span></span>
                                        <span class="text-[9px] font-black text-on-surface-variant/30 uppercase tracking-[0.2em] flex items-center gap-2">
                                            <div class="w-1 h-1 rounded-full bg-primary/40"></div>
                                            {{ $comment->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-on-surface-variant leading-relaxed opacity-80">{{ $comment->content }}</p>
                                    
                                    <div class="flex items-center gap-8 pt-2" 
                                         x-data="{ 
                                            likes: {{ $comment->reactions->count() }}, 
                                            isLiked: {{ $comment->reactions->where('user_id', Auth::id())->count() > 0 ? 'true' : 'false' }},
                                            toggle() {
                                                this.isLiked = !this.isLiked;
                                                this.likes = this.isLiked ? this.likes + 1 : this.likes - 1;
                                                $wire.toggleCommentLike({{ $comment->id }});
                                            }
                                         }">
                                        <button @click="toggle()" class="flex items-center gap-2.5 group/like transition-all active:scale-90">
                                            <div class="p-2 px-3 rounded-xl bg-white/5 flex items-center gap-2 group-hover/like:bg-emerald-500/10 transition-all border border-transparent group-hover/like:border-emerald-500/20">
                                                <svg class="w-4 h-4 transition-all duration-300" :class="isLiked ? 'text-emerald-400 fill-emerald-400 scale-110' : 'text-on-surface-variant group-hover/like:text-emerald-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-width="3"/></svg>
                                                <span class="text-[11px] font-black transition-colors" :class="likes > 0 ? 'text-on-surface' : 'text-on-surface-variant opacity-30'" x-text="likes"></span>
                                            </div>
                                        </button>
                                        <button @click="replying = !replying; if(replying) { $nextTick(() => $el.closest('.group\\/comment').querySelector('input')?.focus()); $wire.replyToId = {{ $comment->id }}; }" class="text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/40 hover:text-primary transition-all flex items-center gap-3 group/reply-btn">
                                            <svg class="w-3 h-3 group-hover/reply-btn:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            {{ __('Reply') }}
                                        </button>
                                    </div>

                                    <div x-show="replying" x-collapse x-cloak class="pt-6">
                                        <div class="flex items-center gap-4 bg-black/40 rounded-[2rem] p-3 border border-primary/10 transition-all shadow-inner">
                                            <img src="{{ Auth::user()->avatar }}" class="w-10 h-10 rounded-2xl ml-2 shadow-2xl border border-white/10 shrink-0">
                                            <input type="text" 
                                                   wire:model="replyContent" 
                                                   wire:keydown.enter="saveGlobalComment({{ $comment->id }})" 
                                                   placeholder="{{ __('Add a reply...') }}" 
                                                   class="bg-transparent border-none flex-1 py-3 text-sm text-on-surface focus:ring-0 placeholder:text-on-surface-variant/20 font-semibold">
                                            <button wire:click="saveGlobalComment({{ $comment->id }})" @click="if($wire.replyContent.length > 0) { setTimeout(() => { replying = false; }, 200) }" class="mr-2 p-3 rounded-2xl bg-primary text-white hover:scale-105 active:scale-95 transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 5l7 7m0 0l-7 7m7-7H3" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </button>
                                        </div>
                                    </div>

                                    @if($comment->replies->count() > 0)
                                        <div class="space-y-8 pt-8 pl-6 border-l-2 border-white/5">
                                            @foreach($comment->replies as $reply)
                                                <div wire:key="reply-v7-{{ $reply->id }}" class="flex gap-6 animate-in slide-in-from-top-2 duration-300">
                                                    <img src="{{ $reply->user->avatar }}" class="w-10 h-10 rounded-xl object-cover shrink-0 border border-white/10 shadow-xl">
                                                    <div class="flex-1 space-y-2">
                                                        <div class="flex items-center gap-3">
                                                            <span class="text-[11px] font-black text-on-surface">{{ $reply->user->name }}</span>
                                                            <span class="text-[10px] font-mono font-bold text-primary/40">@<span>{{ $reply->user->name }}</span></span>
                                                            <span class="text-[9px] font-black text-on-surface-variant/20 uppercase tracking-widest flex items-center gap-2">
                                                                <div class="w-1 h-1 rounded-full bg-primary/20"></div>
                                                                {{ $reply->created_at->diffForHumans() }}
                                                            </span>
                                                        </div>
                                                        <p class="text-sm text-on-surface-variant opacity-80 leading-relaxed">{{ $reply->content }}</p>
                                                        <div class="flex items-center gap-4 pt-1">
                                                            <button wire:click="toggleCommentLike({{ $reply->id }})" class="flex items-center gap-2 group/rlike transition-all active:scale-90">
                                                                <svg class="w-3.5 h-3.5 {{ $reply->reactions->where('user_id', Auth::id())->count() > 0 ? 'text-emerald-400 fill-emerald-400 scale-110' : 'text-on-surface-variant/20 group-hover/rlike:text-emerald-400' }} transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-width="3"/></svg>
                                                                <span class="text-[10px] font-black {{ $reply->reactions->count() > 0 ? 'text-on-surface/60' : 'text-on-surface-variant/10' }}">{{ $reply->reactions->count() }}</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Review Modal -->
        @if($suggestingLine)
            <template x-teleport="body">
                <div class="fixed inset-0 z-[2100] flex items-center justify-center bg-black/80 backdrop-blur-2xl px-4 py-8 overflow-hidden" @click.self="$set('suggestingLine', null)">
                    <div class="bg-surface-container-highest border border-white/10 w-full max-w-5xl rounded-[2.5rem] shadow-[0_0_120px_rgba(0,0,0,1)] flex flex-col max-h-[90vh] overflow-hidden relative animate-in zoom-in-95 duration-300">
                        <!-- Header -->
                        <div class="px-8 py-5 border-b border-white/5 flex items-center justify-between shrink-0 bg-white/[0.02]">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary shadow-inner">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-xs font-black uppercase tracking-widest text-on-surface">{{ __('Suggest Change') }}</h2>
                                    <p class="text-[10px] font-black uppercase text-primary/60 tracking-widest mt-0.5">LINES {{ $suggestingLine }} — {{ $suggestingEndLine ?: $suggestingLine }}</p>
                                </div>
                            </div>
                            <button wire:click="$set('suggestingLine', null)" class="p-2 rounded-xl hover:bg-white/5 text-on-surface-variant hover:text-white transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2.5" stroke-linecap="round"/></svg>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar space-y-8">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch min-h-[400px]">
                                <!-- Outdated Block -->
                                <div class="flex flex-col space-y-4">
                                    <div class="flex items-center gap-3 px-1">
                                        <div class="w-2 h-2 rounded-full bg-red-500 shadow-[0_0_15px_rgba(239,68,68,0.5)]"></div>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant/60">{{ __('Original Code') }}</span>
                                    </div>
                                    <div class="flex-1 rounded-2xl border border-white/5 overflow-hidden shadow-inner bg-black/20">
                                        <x-ui.syntax-highlighter :code="$originalContent" lang="php" class="text-[12px] h-full" />
                                    </div>
                                </div>
                                
                                <!-- Suggested change -->
                                <div class="flex flex-col space-y-4">
                                    <div class="flex items-center gap-3 px-1">
                                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_15px_rgba(16,185,129,0.5)]"></div>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-emerald-400">{{ __('Suggested Change') }}</span>
                                    </div>
                                    <div class="flex-1 flex flex-col bg-[#0d0e12] rounded-2xl border-2 border-emerald-500/20 focus-within:border-emerald-500/50 transition-all shadow-2xl relative overflow-hidden group/patch">
                                        <textarea wire:model.live="suggestedContent" 
                                                  class="flex-1 bg-transparent p-6 font-mono text-[12px] text-emerald-400 outline-none resize-none custom-scrollbar leading-relaxed selection:bg-emerald-500/30 font-black h-full"
                                                  spellcheck="false"
                                                  rows="{{ max(5, count(explode('\n', $suggestedContent))) }}"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Rationale -->
                            <div class="space-y-3">
                                <label class="px-1 text-[9px] font-black uppercase tracking-[0.4em] text-on-surface-variant/40">{{ __('Rationale & Commentary') }}</label>
                                <textarea wire:model="suggestionDescription" placeholder="{{ __('Explain the logic or performance gains...') }}" class="w-full bg-black/20 border border-white/5 rounded-2xl p-6 text-sm text-on-surface min-h-[140px] outline-none focus:border-primary/20 transition-all shadow-inner italic font-medium"></textarea>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-8 py-5 border-t border-white/5 flex justify-end items-center gap-8 shrink-0 bg-white/[0.02]">
                            <button wire:click="$set('suggestingLine', null)" class="text-[10px] font-black uppercase text-on-surface-variant hover:text-white tracking-widest transition-colors">{{ __('Cancel') }}</button>
                            <x-ui.button wire:click="saveInlineSuggestion" variant="primary" class="rounded-2xl px-12 py-5 shadow-[0_0_40px_rgba(16,185,129,0.2)] text-[10px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all">
                                {{ __('Submit Suggestion') }}
                            </x-ui.button>
                        </div>
                    </div>
                </div>
            </template>
        @endif
    </div>
</div>
