<div class="max-w-6xl mx-auto px-4 py-6" 
     x-data="{ 
        isReviewing: @entangle('isReviewing'),
        suggestingLine: @entangle('suggestingLine'),
        suggestingEndLine: @entangle('suggestingEndLine'),
        selectionPopup: { show: false, x: 0, y: 0, text: '', snippetId: null, start: null, end: null, original: '' },
        replyTo: @entangle('replyToId'),
        
        handleMouseUp(e) {
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
     @click.away="selectionPopup.show = false"
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
         class="fixed z-[9999] transform -translate-x-1/2 -translate-y-full bg-[#1a1b26] border border-primary/50 rounded-xl px-3 py-1.5 shadow-2xl flex items-center gap-2 pointer-events-auto backdrop-blur-md"
         :style="`left: ${selectionPopup.x}px; top: ${selectionPopup.y}px;`">
        <button @click="startSuggestion()" class="text-[10px] font-bold text-primary hover:text-primary-light uppercase tracking-widest flex items-center gap-1.5 focus:outline-none">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
            {{ __('Suggest Refactoring') }}
        </button>
    </div>

    <div class="w-full space-y-8">
        <!-- Header Monolith -->
        <div class="bg-[#1a1b26] border border-white/5 rounded-[2.5rem] p-8 flex items-center justify-between gap-6 shadow-2xl relative overflow-hidden group/header">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 to-transparent opacity-0 group-hover/header:opacity-100 transition-opacity pointer-events-none"></div>
            <div class="flex items-center gap-6 relative">
                <div class="relative">
                    <img src="{{ $post->user->avatar }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-white/5 shadow-2xl">
                    <div class="absolute -bottom-2 -right-2 bg-emerald-500 w-5 h-5 rounded-full border-4 border-[#1a1b26]"></div>
                </div>
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <span class="px-2 py-0.5 rounded bg-primary/10 text-primary text-[8px] font-black uppercase tracking-widest">{{ $post->lens ?: 'Logic' }}</span>
                        <span class="text-on-surface-variant/40 text-[10px]">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                    <h1 class="text-2xl font-black text-on-surface tracking-tighter">{{ $post->title }}</h1>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant/60">{{ $post->user->name }} • {{ $post->snippets->count() }} files</p>
                </div>
            </div>
            
            <div class="flex items-center gap-4 relative">
                <select wire:model.live="selectedVersion" class="bg-[#0f111a] border border-white/10 text-on-surface rounded-xl text-[10px] font-bold px-4 py-2 outline-none focus:border-primary/50 transition-all appearance-none cursor-pointer">
                    @for($i = ($post->snippets->max('version_number') ?: 1); $i >= 1; $i--)
                        <option value="{{ $i }}">DEPLOYMENT V{{ $i }}</option>
                    @endfor
                </select>
                <div class="w-px h-8 bg-white/5"></div>
                <x-ui.button wire:click="toggleReviewMode" variant="{{ $isReviewing ? 'primary' : 'ghost' }}" size="sm" class="rounded-xl px-6">
                    {{ $isReviewing ? __('Abort') : __('Review Factory') }}
                </x-ui.button>
            </div>
        </div>

        @if($isReviewing)
            <!-- Full Review Factory (Compact & Premium) -->
            <div class="bg-[#1a1b26] border-2 border-primary/20 rounded-[3rem] p-10 space-y-8 shadow-[0_0_50px_-12px_rgba(16,185,129,0.2)] animate-in slide-in-from-top-4 duration-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black uppercase tracking-tighter text-on-surface">{{ __('Submit Implementation Review') }}</h2>
                        <p class="text-xs text-on-surface-variant/60 font-medium">Your feedback will be attached to V{{ $selectedVersion }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-primary">{{ __('Recording') }}</span>
                    </div>
                </div>

                <div class="space-y-6">
                    <textarea wire:model="reviewDescription" placeholder="{{ __('State the overall architectural impact...') }}" class="w-full bg-[#0f111a] border border-white/5 rounded-3xl p-8 text-sm text-on-surface focus:border-primary/40 min-h-[140px] outline-none transition-all shadow-inner"></textarea>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($currentSnippets as $snippet)
                            <div class="bg-[#0f111a] rounded-[2rem] border border-white/5 overflow-hidden flex flex-col group/snippet">
                                <div class="px-6 py-4 bg-[#161720] border-b border-white/5 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-primary/40 group-hover/snippet:bg-primary transition-colors"></div>
                                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant">{{ $snippet->filename ?: 'file' }}</span>
                                    </div>
                                    <span class="text-[8px] font-mono text-on-surface-variant/30">{{ strtoupper($snippet->language) }}</span>
                                </div>
                                <div class="p-2 flex-1">
                                    <textarea wire:model="reviewFilesData.{{ $snippet->id }}.content" class="w-full bg-[#0d0e12] border-none font-mono text-[11px] text-on-surface min-h-[250px] focus:ring-0 rounded-2xl p-4 custom-scrollbar"></textarea>
                                </div>
                                <div class="px-6 py-4 bg-white/[0.02] border-t border-white/5">
                                    <input type="text" wire:model="reviewFilesData.{{ $snippet->id }}.description" placeholder="{{ __('What changed in this file?') }}" class="w-full bg-transparent border-none p-0 text-[10px] text-on-surface focus:ring-0 placeholder:text-on-surface-variant/30 font-medium">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="flex justify-end items-center gap-6 pt-6 border-t border-white/5">
                    <button wire:click="toggleReviewMode" class="text-[10px] font-black uppercase text-on-surface-variant hover:text-white transition-colors tracking-widest">{{ __('Abandon Changes') }}</button>
                    <x-ui.button wire:click="saveFullReview" variant="primary" class="rounded-2xl px-10 py-5 shadow-2xl">{{ __('Dispatch Review') }}</x-ui.button>
                </div>
            </div>
        @endif

        <!-- Code Block : Monolith HUD -->
        <div class="rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl relative" @mouseup="handleMouseUp($event)">
            <x-ui.code-block 
                :snippets="$currentSnippets"
                :title="$post->title"
                :suggestions="$post->inlineSuggestions"
            />
        </div>

        <!-- Activity Feed (Reviews + Discussion) -->
        <div class="grid grid-cols-1 gap-12 pt-12">
            
            <!-- REVIEWS SECTION -->
            @if($post->fullReviews->count() > 0)
                <div class="space-y-10">
                    <h2 class="text-[10px] font-black uppercase tracking-[0.4em] text-on-surface-variant/40 flex items-center gap-4">
                        {{ __('Implementation Feed') }}
                        <div class="h-px flex-1 bg-white/5"></div>
                    </h2>

                    <div class="space-y-8">
                        @foreach($post->fullReviews as $fr)
                            <div class="bg-[#1a1b26] rounded-[2.5rem] p-8 border border-white/5 shadow-xl group/fr transition-all hover:bg-[#1d1e2b] relative">
                                <div class="flex items-start justify-between gap-6 mb-8">
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <img src="{{ $fr->user->avatar }}" class="w-12 h-12 rounded-2xl object-cover border border-white/10">
                                            <div class="absolute -top-1 -right-1 bg-emerald-500 w-3 h-3 rounded-full border-2 border-[#1a1b26]"></div>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black text-on-surface">{{ $fr->user->name }}</h4>
                                            <p class="text-[8px] font-black uppercase tracking-widest text-primary">{{ $fr->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 bg-[#0f111a] p-1 rounded-[1.2rem] border border-white/5">
                                        <button wire:click="voteReview({{ $fr->id }}, 'up')" class="p-2 rounded-xl hover:bg-emerald-500/10 text-on-surface-variant hover:text-emerald-400 transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                                        <span class="text-xs font-black px-2 {{ $fr->score > 0 ? 'text-emerald-400' : ($fr->score < 0 ? 'text-red-400' : 'text-on-surface-variant') }}">{{ $fr->score }}</span>
                                        <button wire:click="voteReview({{ $fr->id }}, 'down')" class="p-2 rounded-xl hover:bg-red-500/10 text-on-surface-variant hover:text-red-400 transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                                    </div>
                                </div>

                                <div class="pl-4 border-l-2 border-primary/20 mb-8">
                                    <p class="text-[13px] text-on-surface-variant leading-relaxed font-medium italic opacity-90">{{ $fr->description }}</p>
                                </div>
                                
                                @if($fr->modifiedSnippets->count() > 0)
                                    <div class="space-y-4 mb-8">
                                        @foreach($fr->modifiedSnippets as $ms)
                                            <div class="bg-[#0f111a] rounded-[1.5rem] border border-white/5 overflow-hidden group/mod" x-data="{ open: false }">
                                                <div @click="open = !open" class="px-6 py-3 flex items-center justify-between cursor-pointer hover:bg-white/[0.02] transition-all">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-2 h-2 rounded-full bg-primary/20 group-hover/mod:bg-primary transition-colors"></div>
                                                        <span class="text-[10px] font-black uppercase text-on-surface/70">{{ $ms->snippet->filename ?: 'file' }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-3">
                                                        <span class="text-[8px] font-black text-on-surface-variant/30 uppercase tracking-widest">{{ $ms->description ?: __('Revised') }}</span>
                                                        <svg class="w-3.5 h-3.5 text-on-surface-variant/40 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round"/></svg>
                                                    </div>
                                                </div>
                                                <div x-show="open" x-collapse x-cloak>
                                                    <div class="p-6 bg-[#0d0e12] border-t border-white/5 font-mono text-[10px] leading-relaxed custom-scrollbar overflow-x-auto">
                                                        @foreach(\App\Helpers\TextDiffHelper::diffLines($ms->snippet->code_content, $ms->modified_content) as $line)
                                                            <div class="flex gap-4 {{ $line['type'] === 'added' ? 'bg-emerald-500/5 text-emerald-400' : ($line['type'] === 'removed' ? 'bg-red-500/5 text-red-400 line-through opacity-40 text-[9px]' : 'text-on-surface-variant/30 opacity-60') }} px-2 rounded">
                                                                <span class="w-4 opacity-30 select-none">{{ $line['type'] === 'added' ? '+' : ($line['type'] === 'removed' ? '-' : ' ') }}</span>
                                                                <pre class="whitespace-pre-wrap break-all">{{ $line['content'] }}</pre>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- DISCUSSION FOR THIS REVIEW (COMPACT) -->
                                <div class="pt-6 border-t border-white/5" x-data="{ open: false }">
                                    <button @click="open = !open" class="flex items-center gap-2 mb-4 group/intel">
                                        <div class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center group-hover/intel:bg-primary/20 transition-all">
                                            <svg class="w-3.5 h-3.5 text-on-surface-variant/40 group-hover/intel:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-width="2.5" stroke-linecap="round"/></svg>
                                        </div>
                                        <span class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant group-hover/intel:text-white transition-colors">{{ $fr->comments->count() }} {{ __('Intel Nodes') }}</span>
                                    </button>

                                    <div x-show="open" x-collapse x-cloak class="space-y-4 pl-4 border-l border-white/5 ml-4">
                                        @foreach($fr->comments as $revComp)
                                            <div class="flex gap-3 group/revc">
                                                <img src="{{ $revComp->user->avatar }}" class="w-8 h-8 rounded-xl border border-white/5 object-cover">
                                                <div class="flex-1 space-y-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-[10px] font-black text-on-surface">{{ $revComp->user->name }}</span>
                                                        <span class="text-[8px] font-black text-on-surface-variant/30 uppercase">{{ $revComp->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-xs text-on-surface-variant/90 leading-snug">{{ $revComp->content }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        <div class="relative pt-2">
                                            <input type="text" wire:model="reviewCommentContent" wire:keydown.enter="saveGlobalComment(null, {{ $fr->id }})" placeholder="{{ __('Attach note...') }}" class="w-full bg-[#0f111a] border border-white/10 rounded-xl px-4 py-2.5 text-xs text-on-surface outline-none focus:border-primary/40 transition-all shadow-inner">
                                            <button wire:click="saveGlobalComment(null, {{ $fr->id }})" class="absolute right-2 top-3.5 p-1 text-primary hover:scale-110 transition-transform"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" stroke-width="2.5"/></svg></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- GLOBAL DISCUSSION SECTION (YOUTUBE STYLE COMPACT) -->
            <div class="space-y-10">
                <h2 class="text-[10px] font-black uppercase tracking-[0.4em] text-on-surface-variant/40 flex items-center gap-4">
                    {{ __('Community Discussion') }}
                    <div class="h-px flex-1 bg-white/5"></div>
                </h2>

                <div class="bg-[#1a1b26] rounded-[3rem] p-10 border border-white/5 shadow-2xl space-y-12">
                    
                    <!-- Main Input -->
                    <div class="flex gap-6 items-start">
                        <img src="{{ Auth::user()?->avatar ?: 'https://ui-avatars.com/api/?name=Guest' }}" class="w-12 h-12 rounded-[1.2rem] border-2 border-white/5 object-cover shrink-0">
                        <div class="flex-1 space-y-4">
                            <textarea wire:model="globalCommentContent" placeholder="{{ __('Add a comment...') }}" class="w-full bg-[#0f111a] border-b border-white/10 p-0 text-sm text-on-surface focus:border-primary placeholder:text-on-surface-variant/30 min-h-[40px] outline-none transition-all resize-none"></textarea>
                            <div class="flex justify-end gap-3" x-show="$wire.globalCommentContent.length > 0" x-transition>
                                <button @click="$wire.globalCommentContent = ''" class="px-4 py-2 text-[10px] font-black uppercase tracking-widest text-on-surface-variant hover:text-white transition-colors">{{ __('Cancel') }}</button>
                                <x-ui.button wire:click="saveGlobalComment" variant="primary" class="rounded-xl px-6 py-2.5">{{ __('Comment') }}</x-ui.button>
                            </div>
                        </div>
                    </div>

                    <!-- Comments List -->
                    <div class="space-y-10">
                        @foreach($post->comments->whereNull('full_review_id')->whereNull('parent_id') as $comment)
                            <div class="flex gap-5 group/comment" x-data="{ replying: false }">
                                <img src="{{ $comment->user->avatar }}" class="w-11 h-11 rounded-[1.2rem] border border-white/10 shadow-lg object-cover shrink-0">
                                <div class="flex-1 space-y-3">
                                    <div class="flex items-center gap-3">
                                        <span class="text-[11px] font-black text-on-surface">{{ $comment->user->name }}</span>
                                        <span class="text-[8px] font-black text-on-surface-variant/30 uppercase tracking-widest">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-[13px] text-on-surface-variant leading-relaxed opacity-90">{{ $comment->content }}</p>
                                    
                                    <!-- Actions -->
                                    <div class="flex items-center gap-6 pt-1">
                                        <button wire:click="toggleCommentLike({{ $comment->id }})" class="flex items-center gap-2 group/like transition-all active:scale-90">
                                            <div class="p-1 px-2 rounded-lg bg-white/5 flex items-center gap-1.5 group-hover/like:bg-emerald-500/10 transition-all">
                                                <svg class="w-3.5 h-3.5 {{ $comment->reactions->where('user_id', Auth::id())->count() > 0 ? 'text-emerald-400 fill-emerald-400 scale-110' : 'text-on-surface-variant group-hover/like:text-emerald-400' }} transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-width="2.5"/></svg>
                                                <span class="text-[10px] font-black {{ $comment->reactions->count() > 0 ? 'text-on-surface' : 'text-on-surface-variant opacity-40' }}">{{ $comment->reactions->count() }}</span>
                                            </div>
                                        </button>
                                        <button @click="replying = !replying; if(replying) { $nextTick(() => $el.closest('.group\\/comment').querySelector('input')?.focus()); $wire.replyToId = {{ $comment->id }}; }" class="text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/40 hover:text-primary transition-all">{{ __('Reply') }}</button>
                                    </div>

                                    <!-- Reply Input -->
                                    <div x-show="replying" x-collapse x-cloak class="pt-4 flex gap-4 animate-in slide-in-from-left-2 duration-300">
                                        <img src="{{ Auth::user()?->avatar ?: 'https://ui-avatars.com/api/?name=Guest' }}" class="w-8 h-8 rounded-lg object-cover shrink-0">
                                        <div class="flex-1 space-y-3">
                                            <input type="text" wire:model="replyContent" placeholder="{{ __('Add a reply...') }}" class="w-full bg-transparent border-b-2 border-white/5 py-2 text-xs text-on-surface outline-none focus:border-primary transition-all placeholder:text-on-surface-variant/20">
                                            <div class="flex justify-end gap-3">
                                                <button @click="replying = false; $wire.replyToId = null; $wire.replyContent = ''" class="text-[9px] font-black uppercase text-on-surface-variant hover:text-white transition-colors">{{ __('Cancel') }}</button>
                                                <button wire:click="saveGlobalComment({{ $comment->id }})" @click="if($wire.replyContent.length > 0) { setTimeout(() => { replying = false; }, 200) }" class="bg-primary/10 hover:bg-primary text-primary hover:text-white px-5 py-2 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] transition-all shadow-lg active:scale-95">{{ __('Reply') }}</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Nested Replies List -->
                                    @if($comment->replies->count() > 0)
                                        <div class="space-y-6 pt-6 pl-4 border-l border-white/5">
                                            @foreach($comment->replies as $reply)
                                                <div class="flex gap-4">
                                                    <img src="{{ $reply->user->avatar }}" class="w-8 h-8 rounded-lg object-cover shrink-0 border border-white/5">
                                                    <div class="flex-1 space-y-1">
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-[10px] font-black text-on-surface">{{ $reply->user->name }}</span>
                                                            <span class="text-[8px] font-black text-on-surface-variant/30 uppercase tracking-widest">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-[12px] text-on-surface-variant opacity-80">{{ $reply->content }}</p>
                                                        <div class="flex items-center gap-4 pt-1">
                                                            <button wire:click="toggleCommentLike({{ $reply->id }})" class="flex items-center gap-1 group/rlike">
                                                                <svg class="w-3 h-3 {{ $reply->reactions->where('user_id', Auth::id())->count() > 0 ? 'text-emerald-400 fill-emerald-400' : 'text-on-surface-variant/40 group-hover/rlike:text-emerald-400' }} transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-width="2.5"/></svg>
                                                                <span class="text-[9px] font-black {{ $reply->reactions->count() > 0 ? 'text-on-surface' : 'text-on-surface-variant opacity-20' }}">{{ $reply->reactions->count() }}</span>
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

        <!-- Inline Suggestion Modal (Monolith HUD) -->
        @if($suggestingLine)
            <div class="fixed inset-0 z-[1000] flex items-center justify-center p-6 bg-black/90 backdrop-blur-xl animate-in fade-in duration-300">
                <div class="bg-[#1a1b26] border border-primary/30 rounded-[3rem] p-12 w-full max-w-5xl shadow-[0_0_100px_-20px_rgba(16,185,129,0.3)] relative overflow-hidden" @click.away="$set('suggestingLine', null)">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-emerald-400"></div>
                    
                    <div class="flex items-center justify-between mb-10">
                        <div>
                            <h2 class="text-2xl font-black uppercase tracking-tighter mb-1">{{ __('Refactoring Logic') }}</h2>
                            <p class="text-xs text-on-surface-variant font-medium">Lines {{ $suggestingLine }} - {{ $suggestingEndLine ?: $suggestingLine }}</p>
                        </div>
                        <button @click="$set('suggestingLine', null)" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-red-500/20 hover:text-red-400 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round"/></svg></button>
                    </div>

                    <div class="grid grid-cols-2 gap-10 mb-10">
                        <div class="space-y-4">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-red-500/60">{{ __('Outdated') }}</span>
                            </div>
                            <div class="bg-[#0f111a] p-8 rounded-[2.5rem] font-mono text-[11px] text-red-400/50 line-through overflow-auto max-h-[400px] border border-red-500/10 shadow-inner custom-scrollbar whitespace-pre">{{ $originalContent }}</div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-emerald-500">{{ __('Suggested') }}</span>
                            </div>
                            <div class="relative group/edit">
                                <textarea wire:model="suggestedContent" class="w-full bg-[#0d0e12] border-2 border-primary/20 rounded-[2.5rem] p-8 font-mono text-[11px] text-on-surface min-h-[400px] focus:border-primary/60 outline-none shadow-2xl transition-all custom-scrollbar"></textarea>
                                <div class="absolute bottom-4 right-8 text-[9px] font-black uppercase tracking-widest text-primary opacity-20 group-hover/edit:opacity-100 transition-opacity">Ready to Deploy</div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 mb-10">
                        <label class="text-[9px] font-black uppercase tracking-[0.4em] text-on-surface-variant/40 ml-4">{{ __('Architectural Rationale') }}</label>
                        <textarea wire:model="suggestionDescription" placeholder="{{ __('Explain why this refactoring improves the system complexity...') }}" class="w-full bg-[#0f111a] border border-white/5 rounded-[2rem] p-6 text-xs text-on-surface min-h-[100px] outline-none focus:border-primary/20 shadow-inner"></textarea>
                    </div>

                    <div class="flex justify-end gap-6">
                        <button @click="$set('suggestingLine', null)" class="text-[10px] font-black uppercase text-on-surface-variant hover:text-white tracking-widest">{{ __('Discard') }}</button>
                        <x-ui.button wire:click="saveInlineSuggestion" variant="primary" class="rounded-2xl px-12 py-5 shadow-[0_20px_40px_-12px_rgba(16,185,129,0.3)]">{{ __('Commit Suggestion') }}</x-ui.button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
