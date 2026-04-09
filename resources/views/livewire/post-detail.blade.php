<div class="max-w-6xl mx-auto px-4 py-6" 
     x-data="{ 
        activeLine: @entangle('activeLine'),
        isReviewing: @entangle('isReviewing'),
        inlineViewMode: @entangle('inlineViewMode'),
        suggestingLine: @entangle('suggestingLine'),
        suggestingEndLine: @entangle('suggestingEndLine'),
        selectionPopup: { show: false, x: 0, y: 0, text: '', start: null, end: null },
        replyTo: @entangle('replyToId'),
        activeSnippetId: @entangle('activeSnippetId'),
        
         handleMouseUp(e) {
            if (e.target.closest('button') || e.target.closest('a')) return;
            const selection = window.getSelection();
            const selectedText = selection.toString().trim();
            
            if (selectedText.length > 0) {
                const range = selection.getRangeAt(0);
                const rect = range.getBoundingClientRect();
                
                let startNode = selection.anchorNode;
                let endNode = selection.focusNode;
                
                let startContainer = startNode.nodeType === 3 ? startNode.parentNode : startNode;
                let endContainer = endNode.nodeType === 3 ? endNode.parentNode : endNode;
                
                let startLineEl = startContainer.closest('.group\\/line');
                let endLineEl = endContainer.closest('.group\\/line');

                if (startLineEl && endLineEl) {
                    let snippetId = startLineEl.getAttribute('data-snippet');
                    let startLine = parseInt(startLineEl.getAttribute('data-line'));
                    let endLine = parseInt(endLineEl.getAttribute('data-line'));
                    
                    this.selectionPopup = {
                        show: true,
                        x: rect.left + (rect.width / 2),
                        y: rect.top - 10,
                        text: selectedText,
                        snippetId: snippetId,
                        start: Math.min(startLine, endLine),
                        end: Math.max(startLine, endLine),
                        original: selectedText
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
    @snippet-changed.window="$wire.set('activeSnippetId', $event.detail.id)"
    @click.away="selectionPopup.show = false"
>
    <!-- Inline Selection Popup -->
    <div x-show="selectionPopup.show" 
         x-transition
         class="fixed z-[9999] transform -translate-x-1/2 -translate-y-full bg-[#1a1b26] border border-primary/50 rounded-xl px-3 py-1.5 shadow-2xl flex items-center gap-2 pointer-events-auto backdrop-blur-md"
         :style="`left: ${selectionPopup.x}px; top: ${selectionPopup.y}px;`"
    >
        <button @click="startSuggestion()" class="text-[10px] font-bold text-primary hover:text-primary-light uppercase tracking-widest flex items-center gap-1.5 focus:outline-none">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
            {{ __('Suggest Refactoring') }}
        </button>
    </div>

    <!-- Restructured Full Width Layout -->
    <div class="w-full space-y-6">
        
        <!-- Main Content -->
        <div class="space-y-6">
            
            <!-- Compact Header -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @if($post->group)
                            <span class="px-2 py-0.5 rounded-lg text-[8px] font-black bg-primary/10 text-primary border border-primary/20 uppercase tracking-widest">
                                {{ $post->group->name }}
                            </span>
                        @endif
                        <span class="text-[8px] font-black text-on-surface-variant/40 uppercase tracking-widest">{{ $post->visibility }}</span>
                        
                        <div class="flex items-center bg-[#0f111a] rounded-lg p-0.5 border border-white/5 shadow-inner ml-2">
                            <button wire:click="toggleInlineViewMode" class="px-3 py-1 rounded-md text-[8px] font-black uppercase tracking-widest transition-all {{ $inlineViewMode === 'diff' ? 'bg-primary/20 text-primary' : 'text-on-surface-variant hover:text-on-surface' }}">Diff</button>
                            <button wire:click="toggleInlineViewMode" class="px-3 py-1 rounded-md text-[8px] font-black uppercase tracking-widest transition-all {{ $inlineViewMode === 'edit' ? 'bg-primary/20 text-primary' : 'text-on-surface-variant hover:text-on-surface' }}">Edit</button>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <select wire:model.live="selectedVersion" class="bg-[#1a1b26] border border-white/5 text-on-surface rounded-xl text-[9px] font-black uppercase tracking-widest px-4 py-2 hover:border-primary/50 transition-all cursor-pointer outline-none focus:ring-1 focus:ring-primary/40">
                            @php $maxVersion = $post->snippets->max('version_number') ?: 1; @endphp
                            @for($i = $maxVersion; $i >= 1; $i--)
                                <option value="{{ $i }}">{{ __('Version') }} {{ $i }}</option>
                            @endfor
                        </select>
                        <x-ui.button wire:click="toggleReviewMode" 
                                wire:loading.attr="disabled"
                                wire:target="toggleReviewMode"
                                variant="{{ $isReviewing ? 'primary' : 'ghost' }}"
                                size="sm" icon="pencil-square">
                            <span class="text-[9px]">{{ $isReviewing ? __('Cancel Review') : __('Full Code Review') }}</span>
                        </x-ui.button>
                    </div>
                </div>

                <div class="space-y-4">
                    <h1 class="text-3xl font-black font-display text-on-surface leading-tight tracking-tight drop-shadow-2xl">{{ $post->title }}</h1>
                    <div class="relative pl-4 border-l-2 border-primary/20">
                        <p class="text-on-surface-variant text-sm font-medium leading-relaxed opacity-70 italic">{{ $post->short_description }}</p>
                    </div>
                </div>
            </div>

            <!-- Header Banner (Author & Stats) -->
            <div class="bg-[#1a1b26] border border-white/5 rounded-3xl p-6 flex flex-wrap items-center justify-between gap-6 shadow-2xl overflow-hidden">
                <div class="flex items-center gap-4">
                    <img src="{{ $post->user->avatar }}" class="w-12 h-12 rounded-xl border border-white/10 object-cover">
                    <div class="space-y-0.5">
                        <h3 class="font-black text-sm text-on-surface leading-tight tracking-tight">{{ $post->user->name }}</h3>
                        <p class="text-[8px] font-black uppercase tracking-widest text-primary">Pro Contributor</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-6">
                    <div class="text-center hidden sm:block">
                        <p class="text-[8px] font-black uppercase tracking-widest text-on-surface-variant/40 mb-1">Reputation</p>
                        <p class="font-display font-black text-xl text-primary">{{ $post->user->reputation_score }}</p>
                    </div>
                    <div class="w-px h-8 bg-white/5 hidden sm:block"></div>
                    <div class="text-center">
                        <p class="text-[8px] font-black uppercase tracking-widest text-on-surface-variant/40 mb-1">Endorsements</p>
                        <p class="font-display font-black text-xl text-on-surface">{{ $post->up_count }}</p>
                    </div>
                    <div class="w-px h-8 bg-white/5"></div>
                    <div class="text-center">
                        <p class="text-[8px] font-black uppercase tracking-widest text-on-surface-variant/40 mb-1">Versions</p>
                        <p class="font-display font-black text-xl text-on-surface">{{ $post->snippets->max('version_number') }}</p>
                    </div>
                    
                    @if(auth()->user()?->can('update', $post) || auth()->user()?->can('delete', $post))
                    <div class="w-px h-8 bg-white/5"></div>
                    <div class="flex items-center gap-2">
                        @can('update', $post)
                            <x-ui.button variant="ghost" size="sm" icon="plus" href="{{ route('posts.update-code', $post->id) }}" static="true" class="shrink-0">{{ __('Update') }}</x-ui.button>
                        @endcan
                        @can('delete', $post)
                            <button wire:click="deletePost" wire:confirm="{{ __('Permanently delete this post?') }}" class="p-2 rounded-xl bg-error/10 text-error hover:bg-error hover:text-white transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                        @endcan
                    </div>
                    @endif
                </div>
            </div>

            @if($isReviewing)
                <!-- Premium Review Factory -->
                <div class="bg-[#1a1b26] border border-white/10 rounded-[2rem] overflow-hidden shadow-2xl animate-fade-in">
                    <div class="bg-[#0f111a] px-8 py-6 border-b border-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary border border-primary/20 shadow-inner">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-black font-display text-on-surface uppercase tracking-widest">{{ __('Review Factory') }}</h2>
                                <p class="text-[10px] uppercase font-black tracking-[0.2em] text-primary/80 mt-1">Full Codebase Modification</p>
                            </div>
                        </div>
                        <button wire:click="toggleReviewMode" class="text-on-surface-variant hover:text-white p-2.5 bg-[#1a1b26] rounded-xl border border-white/5 transition-all shadow-inner"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>

                    <div class="p-8 space-y-8">
                        <x-ui.textarea wire:model="reviewDescription" label="{{ __('Overall Evaluation') }}" placeholder="{{ __('Explain your architectural decisions and logic changes...') }}" rows="3" />
                        
                        <div class="space-y-4">
                            <h3 class="text-[10px] uppercase font-black tracking-[0.2em] text-on-surface-variant ml-2">{{ __('Project Files') }}</h3>
                            
                            @foreach($currentSnippets as $snippet)
                                @php $isModified = trim($reviewFilesData[$snippet->id]['content'] ?? '') !== trim($snippet->code_content); @endphp
                                <div class="bg-[#0f111a] rounded-[1.5rem] border border-white/5 overflow-hidden transition-all duration-300 {{ $isModified ? 'ring-1 ring-primary/30 shadow-[0_0_15px_rgba(var(--color-primary-rgb),0.1)]' : 'hover:border-white/10' }}" x-data="{ expanded: {{ $isModified ? 'true' : 'false' }} }">
                                    <div class="px-6 py-4 flex items-center justify-between cursor-pointer" @click="expanded = !expanded">
                                        <div class="flex items-center gap-4">
                                            <div class="w-8 h-8 rounded-lg bg-[#1a1b26] border border-white/5 flex items-center justify-center text-on-surface-variant group-hover:text-primary transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                            <div>
                                                <span class="text-xs font-black text-on-surface tracking-widest uppercase">{{ $snippet->filename ?: $snippet->name }}</span>
                                            </div>
                                            @if($isModified)
                                                <span class="bg-primary/10 text-primary text-[8px] font-black px-2 py-1 rounded-md uppercase tracking-widest border border-primary/20 ml-2 shadow-inner">Modified</span>
                                            @endif
                                        </div>
                                        <div class="text-on-surface-variant transition-transform duration-300" :class="expanded ? 'rotate-180 text-primary' : ''">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                    
                                    <div x-show="expanded" x-collapse x-cloak>
                                        <div class="p-6 pt-2 bg-[#1a1b26] border-t border-white/5 space-y-6">
                                            <div class="relative group/editor">
                                                <textarea wire:model="reviewFilesData.{{ $snippet->id }}.content" 
                                                          class="w-full font-mono text-[13px] bg-[#0d0e12] border-2 border-white/5 rounded-2xl p-6 min-h-[300px] text-on-surface focus:ring-0 focus:border-primary/40 shadow-inner leading-relaxed transition-all resize-y"></textarea>
                                                <div class="absolute top-4 right-4 px-3 py-1 rounded bg-[#0f111a] border border-white/5 text-[9px] font-black uppercase tracking-widest text-on-surface-variant opacity-50 select-none pointer-events-none">RAW</div>
                                            </div>
                                            <x-ui.input wire:model="reviewFilesData.{{ $snippet->id }}.description" label="{{ __('File Note (Optional)') }}" placeholder="{{ __('What did you specifically change in this file?') }}" />
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="bg-[#0f111a] px-8 py-6 border-t border-white/5 flex items-center justify-between">
                        <button wire:click="toggleReviewMode" class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant hover:text-error transition-all">{{ __('Discard Everything') }}</button>
                        <x-ui.button wire:click="saveFullReview" variant="primary" icon="rocket">{{ __('Publish Complete Review') }}</x-ui.button>
                    </div>
                </div>
            @endif

            <!-- Bloc de code : affichage multi-fichiers -->
            <div class="relative" @mouseup="handleMouseUp($event)">
                <x-ui.code-block 
                    :snippets="$currentSnippets"
                    :title="$post->title"
                    :goals="$post->review_goals"
                    :context="$post->short_description"
                    type="elegant"
                />
            </div>

            @if($isReviewing && !empty($previewDiffs))
                <!-- Real-time Modification Preview (Step 2 Implementation) -->
                <div class="space-y-6 pt-12 border-t border-white/5 animate-fade-in">
                    <div class="flex items-center justify-between px-2">
                        <h2 class="text-xl font-black font-display text-primary flex items-center gap-3 uppercase tracking-widest opacity-80">
                            <span class="p-1.5 rounded-lg bg-primary/10 text-primary border border-primary/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </span>
                            {{ __('Modification Preview') }} 
                        </h2>
                        <span class="text-[8px] font-black uppercase tracking-[0.2em] text-on-surface-variant/40 animate-pulse">Live Diff Active</span>
                    </div>

                    <div class="glass-panel rounded-3xl overflow-hidden border border-primary/20 bg-primary/[0.02] shadow-2xl">
                        <div class="bg-black/40 px-6 py-3 border-b border-white/5 flex items-center justify-between">
                             <div class="flex items-center gap-4">
                                <span class="text-[10px] font-black uppercase tracking-widest text-primary">{{ __('Review Preview') }}</span>
                                <div class="h-4 w-px bg-white/10"></div>
                                <span class="text-[9px] font-mono font-bold text-on-surface-variant">{{ $post->title }}</span>
                             </div>
                             <div class="flex gap-4">
                                 <div class="flex items-center gap-1.5">
                                     <span class="w-2 h-2 rounded-full bg-emerald-500/50 shadow-[0_0_8px_rgba(16,185,129,0.4)]"></span>
                                     <span class="text-[8px] font-black uppercase tracking-widest text-emerald-400/70">Additions</span>
                                 </div>
                                 <div class="flex items-center gap-1.5">
                                     <span class="w-2 h-2 rounded-full bg-red-500/50 shadow-[0_0_8px_rgba(239,68,68,0.4)]"></span>
                                     <span class="text-[8px] font-black uppercase tracking-widest text-red-400/70">Deletions</span>
                                 </div>
                             </div>
                        </div>

                        <div class="p-0 overflow-x-auto bg-[#0d0e12]">
                            <div class="divide-y divide-white/[0.02]">
                                @foreach($previewDiffs as $snippetId => $lines)
                                    @php 
                                        $snippet = $currentSnippets->firstWhere('id', $snippetId);
                                        $hasChanges = collect($lines)->contains(fn($l) => $l['type'] !== 'unchanged');
                                    @endphp
                                    @if($hasChanges)
                                        <div class="group/diff-file">
                                            <div class="bg-white/[0.02] px-6 py-2 flex items-center gap-3">
                                                <svg class="w-3.5 h-3.5 text-primary/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-width="2"/></svg>
                                                <span class="text-[10px] font-mono font-bold text-on-surface/40 uppercase tracking-widest italic">{{ $snippet->filename ?: $snippet->name }}</span>
                                            </div>
                                            <div class="font-mono text-[11px] leading-6 py-2">
                                                @foreach($lines as $index => $line)
                                                    <div class="flex gap-4 px-6 {{ $line['type'] === 'added' ? 'bg-emerald-500/10 text-emerald-300' : ($line['type'] === 'removed' ? 'bg-red-500/10 text-red-300 line-through opacity-70' : 'text-on-surface-variant/40 opacity-50 hover:opacity-100 hover:bg-white/[0.02]') }} transition-all duration-300">
                                                        <div class="w-10 shrink-0 text-right pr-4 select-none opacity-20 text-[9px]">
                                                            {{ $line['type'] === 'added' ? '+' : ($line['type'] === 'removed' ? '-' : $index + 1) }}
                                                        </div>
                                                        <pre class="whitespace-pre-wrap break-all inline">{{ $line['content'] ?: ' ' }}</pre>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Full Reviews Feed -->
            @if($post->fullReviews->count() > 0)
                <div class="space-y-8 pt-8 px-2">
                    <h2 class="text-xl font-black font-display text-on-surface flex items-center gap-3 uppercase tracking-widest opacity-80">
                        <span class="text-primary/40">#</span> {{ __('Implementation Reviews') }}
                    </h2>
                    
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($post->fullReviews as $fullReview)
                            <div class="bg-[#1a1b26] rounded-3xl p-6 border border-white/5 shadow-xl group/fr transition-all hover:border-primary/30 relative overflow-hidden">
                                <header class="flex items-center justify-between gap-4 mb-6 relative">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $fullReview->user->avatar }}" class="w-10 h-10 rounded-xl border border-primary/20 shadow-2xl object-cover">
                                        <div class="space-y-0.5">
                                            <h4 class="text-sm font-black text-on-surface tracking-tight">{{ $fullReview->user->name }}</h4>
                                            <p class="text-[8px] font-black uppercase tracking-widest text-primary">{{ count($fullReview->modifiedSnippets) }} revised</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center bg-[#1e1f2b] p-0.5 rounded-xl border border-white/5">
                                            @php 
                                                $votes = $fullReview->reactions;
                                                $upCount = $votes->where('type', 'up')->count();
                                                $downCount = $votes->where('type', 'down')->count();
                                                $myVote = $votes->where('user_id', auth()->id())->first()?->type;
                                            @endphp
                                            <button wire:click="voteReview({{ $fullReview->id }}, 'up')" class="p-1.5 rounded-lg {{ $myVote === 'up' ? 'bg-primary text-on-primary' : 'text-on-surface-variant hover:text-primary' }}"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"/></svg></button>
                                            <span class="px-2 font-display font-black text-xs text-on-surface tracking-tighter">{{ $upCount - $downCount }}</span>
                                            <button wire:click="voteReview({{ $fullReview->id }}, 'down')" class="p-1.5 rounded-lg {{ $myVote === 'down' ? 'bg-error text-white' : 'text-on-surface-variant hover:text-error' }}"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg></button>
                                        </div>
                                    </div>
                                </header>
                                <p class="text-sm text-on-surface-variant leading-relaxed font-medium italic opacity-90 mb-6 border-l-2 border-primary/20 pl-4">{{ $fullReview->description }}</p>

                                @if($fullReview->modifiedSnippets->count() > 0)
                                    <div class="space-y-4 mt-6">
                                        <h5 class="text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant/40 ml-2">{{ __('Modified Files') }}</h5>
                                        @foreach($fullReview->modifiedSnippets as $modSnippet)
                                            @php
                                                $diffLines = \App\Helpers\TextDiffHelper::diffLines($modSnippet->snippet->code_content, $modSnippet->modified_content);
                                            @endphp
                                            <div class="bg-[#0f111a] border border-white/5 rounded-2xl overflow-hidden shadow-inner" x-data="{ expanded: false }">
                                                <div class="px-4 py-3 bg-[#1e1f2b] border-b border-white/5 flex items-center justify-between cursor-pointer hover:bg-white/5 transition-all" @click="expanded = !expanded">
                                                    <div class="flex items-center gap-3">
                                                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                        <span class="text-xs font-mono font-bold tracking-tight">{{ $modSnippet->snippet->filename ?: $modSnippet->snippet->name }}</span>
                                                    </div>
                                                    <div class="text-[9px] uppercase font-black tracking-widest text-on-surface-variant group-hover:text-primary transition-colors">
                                                        <span x-show="!expanded">{{ __('View Changes') }}</span>
                                                        <span x-show="expanded">{{ __('Hide') }}</span>
                                                    </div>
                                                </div>
                                                <div x-show="expanded" class="overflow-x-auto bg-[#0d0e12] border-t border-black/40" x-collapse x-cloak>
                                                    <div class="py-4 font-mono text-[11px] leading-relaxed min-w-max">
                                                        @foreach($diffLines as $line)
                                                            <div class="flex gap-4 px-4 {{ $line['type'] === 'added' ? 'bg-emerald-500/10 text-emerald-300' : ($line['type'] === 'removed' ? 'bg-red-500/10 text-red-300 line-through opacity-50' : 'text-on-surface-variant hover:bg-white/5') }}">
                                                                <span class="select-none opacity-30 w-4 text-right inline-block">{{ $line['type'] === 'added' ? '+' : ($line['type'] === 'removed' ? '-' : ' ') }}</span>
                                                                <pre class="whitespace-pre-wrap break-all inline">{{ $line['content'] }}</pre>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Global Discussion Section -->
            <div class="space-y-8 pt-12 border-t border-white/5">
                <h2 class="text-2xl font-black font-display text-on-surface flex items-center gap-4 uppercase tracking-tighter">
                    <span class="p-2 rounded-xl bg-primary/10 text-primary shadow-xl shadow-primary/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </span>
                    {{ __('Discussion') }}
                </h2>

                <div class="bg-[#1a1b26] rounded-3xl p-8 border border-white/5 shadow-2xl space-y-12">
                    <div class="space-y-4 group/comment-input">
                        <x-ui.textarea wire:model="globalCommentContent" placeholder="{{ __('Start the conversation...') }}" rows="2" />
                        <div class="flex justify-end pt-2">
                            <x-ui.button wire:click="saveGlobalComment" variant="primary" size="sm" icon="paper-airplane">{{ __('Publish Critique') }}</x-ui.button>
                        </div>
                    </div>

                    <div class="space-y-10">
                        @forelse($post->comments as $comment)
                            <div class="relative group/maincmt {{ $comment->is_pinned ? 'bg-primary/[0.04] p-6 rounded-2xl border border-primary/10' : '' }}">
                                <div class="flex items-start gap-6">
                                    <img src="{{ $comment->user->avatar }}" class="w-10 h-10 rounded-xl border border-white/5 object-cover">
                                    <div class="flex-1 space-y-4">
                                        <div class="flex items-center justify-between gap-4">
                                            <div class="flex items-center gap-3">
                                                <h4 class="font-black text-sm text-on-surface tracking-tight">{{ $comment->user->name }}</h4>
                                                <span class="text-[8px] font-black text-on-surface-variant/30 uppercase tracking-widest">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                @php 
                                                    $cVotes = $comment->reactions;
                                                    $hasLiked = $cVotes->where('user_id', auth()->id())->where('type', 'like')->count() > 0; 
                                                @endphp
                                                <button wire:click="toggleCommentLike({{ $comment->id }})" 
                                                        class="flex items-center gap-2 px-3 py-1.5 rounded-xl transition-all border {{ $hasLiked ? 'bg-primary/20 border-primary/30 text-primary' : 'bg-[#1e1f2b] border-white/5 text-on-surface-variant hover:border-primary/40' }} group/lbtn">
                                                    <svg class="w-3.5 h-3.5 {{ $hasLiked ? 'text-primary fill-current' : 'text-on-surface-variant opacity-40' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                                    <span class="font-display font-black text-xs tracking-tighter">{{ $cVotes->count() }}</span>
                                                </button>
                                            </div>
                                        </div>
                                        <p class="text-on-surface-variant text-sm leading-relaxed max-w-2xl font-medium opacity-90">{{ $comment->content }}</p>
                                        
                                        <button @click="replyTo = (replyTo === {{ $comment->id }} ? null : {{ $comment->id }}); $wire.set('replyToId', replyTo)" 
                                                class="text-[8px] font-black uppercase tracking-widest text-primary hover:text-primary-light transition-all flex items-center gap-2">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                            {{ __('Reply') }}
                                        </button>

                                        {{-- Reply compose box (indenté visuellement comme YouTube) --}}
                                        <div x-show="replyTo === {{ $comment->id }}" x-transition x-cloak class="pt-3 ml-14 space-y-3">
                                            <div class="flex gap-3">
                                                <div class="w-px bg-primary/20 flex-shrink-0 ml-1"></div>
                                                <div class="flex-1 space-y-3">
                                                    <x-ui.textarea wire:model="replyContent" placeholder="{{ __('Reply to') }} {{ $comment->user->name }}..." rows="2" />
                                                    <div class="flex justify-end gap-3 items-center">
                                                        <button @click="replyTo = null; $wire.set('replyToId', null)" class="text-[8px] font-black uppercase tracking-widest text-on-surface-variant hover:text-error transition-all">{{ __('Discard') }}</button>
                                                        <x-ui.button 
                                                            wire:click="saveGlobalComment({{ $comment->id }})" 
                                                            @click="replyTo = null"
                                                            variant="primary" size="sm">{{ __('Reply') }}</x-ui.button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Replies (indented thread) --}}
                                        @if($comment->replies->count() > 0)
                                            <div class="ml-6 pl-4 border-l-2 border-white/5 space-y-4 pt-4">
                                                @foreach($comment->replies as $reply)
                                                    <div class="flex items-start gap-4 group/reply">
                                                        <img src="{{ $reply->user->avatar }}" class="w-7 h-7 rounded-lg border border-white/5 object-cover flex-shrink-0">
                                                        <div class="flex-1 space-y-1">
                                                            <div class="flex items-center justify-between">
                                                                <div class="flex items-center gap-2">
                                                                    <span class="font-black text-xs text-on-surface">{{ $reply->user->name }}</span>
                                                                    <span class="text-[8px] font-black text-on-surface-variant/30 uppercase tracking-widest">{{ $reply->created_at->diffForHumans() }}</span>
                                                                </div>
                                                                @php $rVotes = $reply->reactions; $replyLiked = $rVotes->where('user_id', auth()->id())->where('type','like')->count() > 0; @endphp
                                                                <button wire:click="toggleCommentLike({{ $reply->id }})" 
                                                                        class="flex items-center gap-1.5 px-2 py-1 rounded-lg transition-all border text-[10px] {{ $replyLiked ? 'bg-primary/10 border-primary/20 text-primary' : 'border-white/5 text-on-surface-variant hover:border-primary/20' }}">
                                                                    <svg class="w-3 h-3 {{ $replyLiked ? 'text-primary fill-current' : 'text-on-surface-variant opacity-40' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                                                    <span class="font-black">{{ $rVotes->count() }}</span>
                                                                </button>
                                                            </div>
                                                            <p class="text-xs text-on-surface-variant leading-relaxed opacity-90">{{ $reply->content }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center opacity-30">
                                <p class="text-xs font-black uppercase tracking-[0.3em]">{{ __('No discussion yet') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Premium Multi-line Suggestion Modal -->
    @if($suggestingLine)
        <div class="fixed inset-0 z-[99999] bg-[#0f111a]/90 backdrop-blur-md flex items-center justify-center p-4 animate-fade-in" @keydown.escape.window="$set('suggestingLine', null)">
            <div class="bg-[#1a1b26] border border-primary/30 rounded-[2rem] p-8 w-full max-w-4xl shadow-[0_0_50px_rgba(var(--color-primary-rgb),0.15)] relative transform transition-all group/modal" @click.away="$set('suggestingLine', null)">
                <div class="flex items-center justify-between mb-8 pb-6 border-b border-white/5">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary border border-primary/20 shadow-inner">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black font-display text-on-surface uppercase tracking-widest">{{ __('Suggest Refactoring') }}</h2>
                            <p class="text-[10px] text-on-surface-variant font-black uppercase tracking-widest pt-1 opacity-70">Target: Lines {{ $suggestingLine }} to {{ $suggestingEndLine }}</p>
                        </div>
                    </div>
                    <button wire:click="$set('suggestingLine', null)" class="text-on-surface-variant hover:text-error p-2.5 bg-[#0f111a] rounded-xl border border-white/5 transition-all shadow-inner"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-error/80 ml-2">
                                {{ __('Original Code') }}
                            </label>
                            <div class="bg-[#0f111a] border-2 border-error/20 p-6 rounded-2xl font-mono text-[13px] text-error/50 line-through leading-relaxed min-h-[200px] overflow-auto shadow-inner">
                                {{ $originalContent }}
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-primary/80 ml-2 flex justify-between">
                                <span>{{ __('Modified Code') }}</span>
                                <span class="text-on-surface-variant/50">Edit Directly</span>
                            </label>
                            <textarea wire:model="suggestedContent" 
                                      class="w-full bg-[#0d0e12] border-2 border-primary/20 rounded-2xl p-6 font-mono text-[13px] text-on-surface focus:ring-0 focus:border-primary/40 leading-relaxed min-h-[200px] shadow-inner transition-all resize-none"
                                      ></textarea>
                            @error('suggestedContent')
                                <p class="text-[10px] uppercase font-black tracking-widest text-error/90 mt-1 ml-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <x-ui.textarea wire:model="suggestionDescription" label="{{ __('Explanation') }}" placeholder="{{ __('Why are you suggesting this change? How does it improve the code?') }}" rows="2" />
                    
                    <div class="pt-6 border-t border-white/5 flex justify-end gap-4 items-center">
                        <button wire:click="$set('suggestingLine', null)" class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant hover:text-error transition-all">{{ __('Discard') }}</button>
                        <x-ui.button wire:click="saveInlineSuggestion" variant="primary" icon="check" size="lg">{{ __('Submit Suggestion') }}</x-ui.button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div> 
