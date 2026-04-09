<div class="w-full max-w-none px-12 py-10">
    <!-- Feed Header -->
    <div class="flex items-end justify-between mb-16 border-b border-outline-variant/10 pb-10">
        <div class="space-y-3">
            <h2 class="font-display text-5xl font-black text-on-surface tracking-tighter">{{ __('Feed') }}</h2>
            <p class="text-on-surface-variant text-sm tracking-wide font-medium opacity-60">{{ __('Find the best code reviews.') }}</p>
        </div>

        <div class="flex flex-col items-end gap-6">
            <!-- Search bar -->
            <div class="relative group/search">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none z-10">
                    <svg class="h-4 w-4 text-on-surface-variant/30 group-focus-within/search:text-primary transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="{{ __('Search posts...') }}" 
                    class="bg-[#0f111a]/60 backdrop-blur-3xl border border-white/5 rounded-xl py-3.5 pl-12 pr-6 text-xs text-on-surface placeholder:text-on-surface-variant/20 focus:outline-none focus:border-primary/40 transition-all w-64 group-hover/search:w-80 group-focus-within/search:!w-[32rem] shadow-xl shadow-black/60 font-medium tracking-wide"
                >
                <div class="absolute inset-0 rounded-xl bg-primary/5 opacity-0 group-focus-within/search:opacity-100 blur-xl -z-10 transition-opacity duration-700"></div>
            </div>

            <div class="flex items-center gap-4 bg-surface-container-low/40 backdrop-blur-2xl rounded-xl p-1 border border-white/5">
                <x-ui.button 
                    variant="{{ $sort === 'trending' ? 'primary' : 'ghost' }}" 
                    size="sm" class="!px-8 !rounded-lg"
                    wire:click="sortBy('trending')"
                    wire:loading.attr="disabled"
                >
                    {{ __('Trending') }}
                </x-ui.button>
                <x-ui.button 
                    variant="{{ $sort === 'recent' ? 'primary' : 'ghost' }}" 
                    size="sm" class="!px-8 !rounded-lg"
                    wire:click="sortBy('recent')"
                    wire:loading.attr="disabled"
                >
                    {{ __('Recent') }}
                </x-ui.button>
            </div>
        </div>
    </div>

    <!-- Feed Content -->
    <div class="relative min-h-[400px]">
        <x-ui.loader-overlay target="search, sortBy, gotoPage, nextPage, previousPage" />
        
        <div class="space-y-24">
        @foreach($posts as $post)
            @php
                $myReaction = $post->reactions->first()?->type;
                $votedState = $myReaction === 'mindblown' ? 'up' : ($myReaction === 'optimisable' ? 'down' : '');
            @endphp
            <article wire:key="post-{{ $post->id }}" @class(['group relative', 'opacity-50 blur-sm grayscale' => false]) x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false" wire:loading.class="opacity-50 blur-sm grayscale" wire:loading.attr="disabled">
                <div class="flex items-start gap-12">
                    <!-- Karma Lens (Vertical Sidebar) -->
                    <div class="flex flex-col items-center gap-3 sticky top-32" 
                         x-data="{ 
                             voted: '{{ $votedState }}',
                             score: {{ $post->up_count - $post->down_count }},
                             isVoting: false,
                             handleVote(dir) {
                                if (this.isVoting) return;
                                this.isVoting = true;
                                window.haptic.play(dir);

                                if (dir === 'up') {
                                    if (this.voted === 'up') { this.score--; this.voted = ''; }
                                    else {
                                        this.score += (this.voted === 'down' ? 2 : 1);
                                        this.voted = 'up';
                                    }
                                } else {
                                    if (this.voted === 'down') { this.score++; this.voted = ''; }
                                    else {
                                        this.score -= (this.voted === 'up' ? 2 : 1);
                                        this.voted = 'down';
                                    }
                                }
                                setTimeout(() => { this.isVoting = false; }, 300);
                             }
                         }">
                        <button 
                            wire:click="vote({{ $post->id }}, 'up')"
                            wire:loading.attr="disabled"
                            @click="handleVote('up')"
                            :class="voted === 'up' ? 'bg-emerald-500 border-emerald-400 text-white shadow-[0_0_30px_rgba(52,211,153,0.4)] scale-110' : 'bg-surface-container-low text-on-surface-variant hover:text-emerald-400 hover:border-emerald-500/40 active:scale-95 border-white/5'"
                            class="w-14 h-14 rounded-2xl border flex items-center justify-center transition-all duration-300 group/vote"
                        >
                            <svg class="w-6 h-6 transition-transform group-hover/vote:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                        </button>
                        
                        <div class="py-2 flex flex-col items-center select-none">
                            <span class="font-display font-black text-3xl tracking-tighter transition-all duration-300"
                                  :class="voted === 'up' ? 'text-emerald-400' : (voted === 'down' ? 'text-rose-400' : 'text-on-surface/40')"
                                  x-text="score"></span>
                            <span class="text-[8px] font-black uppercase tracking-[0.3em] text-on-surface-variant/20 mt-1">{{ __('Score') }}</span>
                        </div>
                        
                        <button 
                            wire:click="vote({{ $post->id }}, 'down')"
                            wire:loading.attr="disabled"
                            @click="handleVote('down')"
                            :class="voted === 'down' ? 'bg-rose-500 text-white border-rose-400 shadow-[0_0_20px_rgba(244,63,94,0.3)] scale-110' : 'bg-surface-container-low text-on-surface-variant hover:text-rose-400 hover:border-rose-500/40 active:scale-95 border-white/5'"
                            class="w-14 h-14 rounded-2xl border flex items-center justify-center transition-all duration-300 group/vote"
                        >
                            <svg class="w-6 h-6 transition-transform group-hover/vote:translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>

                    <!-- Main Article Content -->
                    <div class="flex-grow space-y-8">
                        <!-- Metadata Row -->
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-6">
                                <div class="relative group/avatar">
                                    <div class="w-14 h-14 rounded-2xl bg-surface-container-highest border border-primary/10 flex items-center justify-center text-xl font-black font-display text-primary italic overflow-hidden transition-all duration-500 group-hover/avatar:scale-105 shadow-xl">
                                        @if($post->user->profile_photo_path || $post->user->avatar)
                                            <img src="{{ $post->user->profile_photo_url }}" class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-secondary rounded-full border-[3px] border-surface flex items-center justify-center shadow-lg">
                                        <div class="w-1.5 h-1.5 bg-white rounded-full animate-pulse shadow-[0_0_5px_#fff]"></div>
                                    </div>
                                </div>
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg font-black text-on-surface tracking-tight">{{ $post->user->name }}</span>
                                        <span class="text-xs font-mono font-bold text-primary tracking-wider font-black">@<span>{{ $post->user->name }}</span></span>
                                    </div>
                                    <span class="text-[10px] uppercase tracking-[0.2em] text-on-surface-variant font-black opacity-40 mt-1 flex items-center gap-2">
{{ $post->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center gap-4">
                                <x-ui.button variant="ghost" size="sm" class="!bg-primary/5 border-primary/10 hover:!bg-primary/20 hover:!text-primary !rounded-xl !relative group/inspect-btn" href="{{ route('posts.detail', $post->id) }}" static="true">
                                    <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <span class="pointer-events-none">{{ __('View Post') }}</span>
                                </x-ui.button>
                            </div>
                        </div>

                        <!-- Content Headers (Dynamic Expansion) -->
                        <div class="relative group/header cursor-default">
                             <div class="flex items-start gap-6 transition-all duration-700 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:translate-y-[-4px]">
                                <div class="flex-grow min-h-[48px] flex flex-col justify-center">
                                    <h3 class="text-3xl font-display font-black text-on-surface leading-tight tracking-tighter group-active:scale-[0.98] transition-all duration-500">
                                        {{ $post->title }}
                                    </h3>
                                    
                                    <div class="max-h-0 opacity-0 group-hover:max-h-32 group-hover:opacity-100 transition-all duration-700 ease-in-out overflow-hidden">
                                        <div class="pt-4 space-y-3">
                                            @if($post->short_description)
                                                <p class="text-lg text-on-surface font-semibold leading-relaxed max-w-4xl opacity-90">
                                                    {{ $post->short_description }}
                                                </p>
                                            @endif
                                            @if($post->description && $post->description !== $post->short_description)
                                                <p class="text-base text-on-surface-variant font-medium leading-relaxed max-w-4xl opacity-60">
                                                    {{ $post->description }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                             </div>
                        </div>

                        <!-- The Code Lens (Primary Display: Multi-file Explorer) -->
                        <div class="transition-all duration-700 transform group-hover:scale-[1.002]">
                            <x-ui.code-block 
                                :title="$post->title" 
                                :snippets="$post->snippets"
                                :type="$post->lens ?? 'elegant'" 
                                :goals="$post->review_goals"
                                :context="$post->context"
                            />
                        </div>

                        <!-- Tags & Social Counter -->
                        <div class="flex items-center gap-8 pt-6 border-t border-white/[0.03]">
                             <div class="flex gap-2">
                                @foreach(explode(',', $post->lens ?? 'Review') as $l)
                                    @php $lKey = strtolower(trim($l)); @endphp
                                    <span 
                                        class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] transition-all hover:scale-105 cursor-default border"
                                        style="color: var(--lens-{{ $lKey }}); background-color: rgba(var(--lens-{{ $lKey }}-rgb), 0.1); border-color: rgba(var(--lens-{{ $lKey }}-rgb), 0.3); box-shadow: 0 0 15px rgba(var(--lens-{{ $lKey }}-rgb), 0.1);"
                                    >#{{ strtoupper(trim($l)) }}</span>
                                @endforeach
                                <span class="px-4 py-1.5 rounded-xl bg-surface-container-highest text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/60 border border-white/[0.03] hover:border-secondary/30 transition-colors cursor-default">#{{ strtoupper($post->latestSnippet->language ?? 'PHP') }}</span>
                             </div>
                             <div class="flex-grow"></div>
                             <div class="flex items-center gap-8 text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/30">
                                <div class="flex items-center gap-3 group/stat cursor-pointer hover:text-primary transition-all duration-300">
                                    <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center group-hover/stat:bg-primary/10 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    </div>
                                    <span>{{ $post->reviews_count ?? 0 }} {{ __('Reviews') }}</span>
                                </div>
                             </div>
                        </div>
                    </div>
                </div>
                
                <!-- Corner Decoration -->
            </article>
        @endforeach
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-32 mb-10">
        <div class="glass-panel bg-[#0f111a]/40 backdrop-blur-3xl rounded-3xl border border-white/5 p-8 flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden group/pagination shadow-[0_45px_100px_-20px_rgba(0,0,0,0.6)]">
            <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-primary/30 to-transparent opacity-40 transition-opacity group-hover/pagination:opacity-100"></div>
            
            <div class="flex flex-col">
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-primary mb-1">{{ __('Result') }}</span>
                <div class="flex items-center gap-4">
                    <div class="flex items-baseline gap-1.5 opacity-80">
                        <span class="text-[10px] font-bold text-on-surface-variant/40 tracking-widest uppercase">{{ __('Showing') }}</span>
                        <span class="text-2xl font-display font-black text-on-surface tracking-tighter">{{ $posts->firstItem() ?? 0 }}-{{ $posts->lastItem() ?? 0 }}</span>
                        <span class="text-[10px] font-bold text-on-surface-variant/40 tracking-widest uppercase">of</span>
                        <span class="text-2xl font-display font-black text-on-surface tracking-tighter">{{ $posts->total() }}</span>
                    </div>
                </div>
            </div>

            <div class="pagination-container">
                <style>
                    .pagination-container nav { @apply flex items-center gap-2; }
                    .pagination-container .relative.z-0 { @apply !shadow-none !border-none !flex !gap-2; }
                    .pagination-container span[aria-current="page"] span { 
                        @apply !bg-primary !text-on-primary !rounded-xl !border-none !w-11 !h-11 !flex !items-center !justify-center !text-sm !font-black !font-display !shadow-[0_0_20px_rgba(190,194,255,0.3)] !scale-105 !transition-all;
                    }
                    .pagination-container a, .pagination-container span[aria-disabled="true"] { 
                        @apply !bg-white/[0.03] !text-on-surface-variant/40 !rounded-xl !border !border-white/5 !max-w-none !w-11 !h-11 !flex !items-center !justify-center !text-sm !font-bold !font-display !transition-all hover:!bg-white/[0.08] hover:!text-white hover:!border-white/10;
                    }
                    .pagination-container span[aria-disabled="true"] { @apply !opacity-20 !cursor-not-allowed; }
                    .pagination-container nav { @apply border border-white/5 rounded-2xl p-1 bg-black/20; }
                    .pagination-container svg { @apply !w-4 !h-4; }
                </style>
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>
