<div class="space-y-16">
    <!-- Search & Filters -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 py-8 border-b border-white/5 relative group/controls">
        <div class="absolute inset-0 bg-gradient-to-r from-primary/5 to-transparent opacity-0 group-hover/controls:opacity-100 transition-opacity"></div>
        
        <div class="relative group/search max-w-xl w-full z-10">
            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-on-surface-variant/30 group-focus-within/search:text-primary transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="{{ __('Search posts in this group...') }}" 
                class="w-full bg-black/40 border border-white/5 rounded-2xl py-4 pl-14 pr-6 text-xs text-on-surface placeholder:text-on-surface-variant/10 focus:outline-none focus:border-primary/40 transition-all font-bold tracking-wide shadow-2xl"
            >
        </div>

        <div class="flex items-center gap-3 bg-white/[0.02] backdrop-blur-3xl rounded-[1.5rem] p-1.5 border border-white/5 z-10">
            <button 
                wire:click="sortBy('recent')"
                @class(['px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] transition-all', 'bg-primary text-on-primary shadow-xl' => $sort === 'recent', 'text-on-surface-variant/40 hover:text-white hover:bg-white/5' => $sort !== 'recent'])
            >
                {{ __('Recent') }}
            </button>
            <button 
                wire:click="sortBy('trending')"
                @class(['px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] transition-all', 'bg-primary text-on-primary shadow-xl' => $sort === 'trending', 'text-on-surface-variant/40 hover:text-white hover:bg-white/5' => $sort !== 'trending'])
            >
                {{ __('Trending') }}
            </button>
        </div>
    </div>

    <!-- Group Posts Feed -->
    <div class="relative min-h-[400px]">
        <x-ui.loader-overlay target="search, sortBy, gotoPage, nextPage, previousPage" />

        @if($posts->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-center space-y-8 animate-fade-in">
                <div class="w-32 h-32 rounded-[2.5rem] bg-white/[0.02] border border-white/5 flex items-center justify-center relative overflow-hidden group/empty">
                    <div class="absolute inset-0 bg-primary/5 opacity-0 group-hover/empty:opacity-100 transition-opacity blur-2xl"></div>
                    <svg class="w-16 h-16 text-white/5 group-hover/empty:text-primary/20 transition-all duration-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-2xl font-display font-black text-on-surface/40 tracking-tighter">{{ __('No Posts Found') }}</h4>
                    <p class="text-[9px] font-black uppercase tracking-[0.4em] text-on-surface-variant/20 mt-4 max-w-xs">{{ __('No posts have been shared in this group yet.') }}</p>
                </div>
            </div>
        @else
            <div class="space-y-12">
                @foreach($posts as $post)
                    @php
                        $myReaction = $post->reactions->first()?->type;
                        $votedState = $myReaction === 'mindblown' ? 'up' : ($myReaction === 'optimisable' ? 'down' : '');
                        $score = $post->up_count - $post->down_count;
                    @endphp
                    <article wire:key="group-post-{{ $post->id }}" class="group relative bg-surface-container-low/40 border border-white/5 rounded-[2.5rem] p-10 transition-all duration-500 hover:bg-white/[0.02] active:scale-[0.99] hover:border-primary/20 hover:shadow-2xl">
                        <div class="flex items-start gap-10">
                             <!-- Karma Lens -->
                             <div class="flex flex-col items-center gap-3 py-2"
                                  x-data="{ 
                                     voted: '{{ $votedState }}',
                                     score: {{ $score }},
                                     handleVote(dir) {
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
                                     }
                                  }"
                                  wire:loading.class="opacity-60 pointer-events-none transition-opacity"
                                  wire:target="vote({{ $post->id }}, 'up'), vote({{ $post->id }}, 'down')"
                             >
                                <button 
                                    wire:click="vote({{ $post->id }}, 'up')"
                                    @click="handleVote('up')"
                                    wire:loading.attr="disabled"
                                    class="w-12 h-12 rounded-xl border transition-all duration-300 flex items-center justify-center group/vote-up"
                                    :class="voted === 'up' ? 'bg-emerald-500 border-emerald-500 text-white shadow-[0_0_20px_rgba(16,185,129,0.3)]' : 'bg-white/5 border-white/5 text-on-surface-variant/40 hover:text-emerald-500 hover:border-emerald-500/20'"
                                >
                                    <svg class="w-5 h-5 transition-transform group-hover/vote-up:-translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                </button>

                                <div class="flex flex-col items-center">
                                    <span class="text-2xl font-display font-black tracking-tighter transition-colors duration-300"
                                          :class="voted === 'up' ? 'text-emerald-500' : (voted === 'down' ? 'text-rose-500' : 'text-on-surface/20')"
                                          x-text="score"></span>
                                </div>

                                <button 
                                    wire:click="vote({{ $post->id }}, 'down')"
                                    @click="handleVote('down')"
                                    wire:loading.attr="disabled"
                                    class="w-12 h-12 rounded-xl border transition-all duration-300 flex items-center justify-center group/vote-down"
                                    :class="voted === 'down' ? 'bg-rose-500 border-rose-500 text-white shadow-[0_0_20px_rgba(244,63,94,0.3)]' : 'bg-white/5 border-white/5 text-on-surface-variant/40 hover:text-rose-500 hover:border-rose-500/20'"
                                >
                                    <svg class="w-5 h-5 transition-transform group-hover/vote-down:translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                             </div>

                             <div class="flex-grow space-y-6">
                                <!-- Meta Row -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <img src="{{ $post->user->profile_photo_url }}" class="w-10 h-10 rounded-xl border border-white/10 shadow-lg object-cover">
                                            <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-primary border-2 border-[#0d0e12] rounded-full shadow-lg"></div>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-xs font-black text-on-surface tracking-tight leading-none">{{ $post->user->name }}</span>
                                            <span class="text-[9px] font-mono font-black text-on-surface-variant/20 uppercase tracking-[0.2em] mt-1.5">{{ $post->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <x-ui.button variant="ghost" size="sm" class="!bg-white/5 !border-white/5 hover:!bg-primary/20 hover:!border-primary/30 hover:!text-primary !rounded-xl transition-all" href="{{ route('posts.detail', $post->id) }}" static="true">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        {{ __('View Post') }}
                                    </x-ui.button>
                                </div>

                                <!-- Content -->
                                <div class="space-y-3">
                                    <h4 class="text-2xl font-display font-black text-on-surface tracking-tighter leading-tight group-hover:text-primary transition-colors duration-500">{{ $post->title }}</h4>
                                    <p class="text-base text-on-surface-variant/60 font-semibold italic leading-relaxed line-clamp-2 max-w-3xl">{{ $post->short_description }}</p>
                                </div>

                                <!-- Code Preview -->
                                @if($post->latestSnippet)
                                    <div class="rounded-3xl overflow-hidden border border-white/5 bg-black/60 relative group/hud">
                                        <div class="absolute inset-0 bg-primary/2 opacity-0 group-hover/hud:opacity-100 transition-opacity"></div>
                                        <div class="px-5 py-3 bg-white/[0.03] border-b border-white/5 flex items-center justify-between relative z-10">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-3.5 h-3.5 text-on-surface-variant/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                <span class="text-[10px] font-mono font-bold text-on-surface-variant/30 tracking-tight">{{ $post->latestSnippet->filename ?? 'snippet' }}</span>
                                            </div>
                                            <span class="text-[9px] font-black uppercase tracking-[0.3em] text-primary/40">{{ $post->latestSnippet->language }}</span>
                                        </div>
                                        <div class="p-6 relative z-10">
                                            <pre class="text-[11px] text-on-surface/40 font-mono leading-relaxed line-clamp-4"><code>{{ $post->latestSnippet->code }}</code></pre>
                                        </div>
                                    </div>
                                @endif
                             </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Dashboard Pagination -->
            <div class="mt-16 pt-12 border-t border-white/5">
                <style>
                    .group-pagination nav { @apply flex items-center justify-center gap-3; }
                    .group-pagination span[aria-current="page"] span { 
                        @apply !bg-primary !text-on-primary !rounded-xl !border-none !w-12 !h-12 !flex !items-center !justify-center !text-sm !font-black !font-display !shadow-[0_0_20px_rgba(190,194,255,0.3)] !scale-110 !transition-all;
                    }
                    .group-pagination a, .group-pagination span[aria-disabled="true"] { 
                        @apply !bg-white/[0.03] !text-on-surface-variant/40 !rounded-xl !border !border-white/5 !w-12 !h-12 !flex !items-center !justify-center !text-sm !font-bold !font-display !transition-all hover:!bg-white/[0.08] hover:!text-white hover:!border-white/10;
                    }
                    .group-pagination span[aria-disabled="true"] { @apply !opacity-10 !cursor-not-allowed; }
                </style>
                <div class="group-pagination">
                    {{ $posts->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
