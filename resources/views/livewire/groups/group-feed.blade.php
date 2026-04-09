<div class="space-y-12">
    <!-- Search & Sort -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-6 border-b border-white/5">
        <div class="relative group/search max-w-md w-full">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="{{ __('Search posts in this group...') }}" 
                class="w-full bg-white/[0.03] border border-white/5 rounded-xl py-2.5 pl-10 pr-4 text-xs text-white placeholder:text-white/20 focus:outline-none focus:border-[#8B5CF6]/40 transition-all font-medium"
            >
        </div>

        <div class="flex items-center gap-2 bg-black/20 rounded-lg p-1 border border-white/5 self-end">
            <button 
                wire:click="sortBy('recent')"
                @class(['px-4 py-1.5 rounded-md text-[10px] font-bold uppercase tracking-wider transition-all', 'bg-[#8B5CF6] text-white shadow-[0_0_15px_rgba(139,92,246,0.3)]' => $sort === 'recent', 'text-white/40 hover:text-white' => $sort !== 'recent'])
            >
                {{ __('Recent') }}
            </button>
            <button 
                wire:click="sortBy('trending')"
                @class(['px-4 py-1.5 rounded-md text-[10px] font-bold uppercase tracking-wider transition-all', 'bg-[#8B5CF6] text-white shadow-[0_0_15px_rgba(139,92,246,0.3)]' => $sort === 'trending', 'text-white/40 hover:text-white' => $sort !== 'trending'])
            >
                {{ __('Trending') }}
            </button>
        </div>
    </div>

    <!-- Group Posts Feed -->
    <div class="relative min-h-[400px]">
        @if($posts->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-16 h-16 rounded-2xl bg-white/[0.02] border border-white/5 flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h4 class="text-white/60 font-bold mb-2">{{ __('No posts found in this group') }}</h4>
                <p class="text-white/20 text-sm max-w-xs">{{ __('Start sharing your work with the group to get feedback.') }}</p>
            </div>
        @else
            <div class="space-y-16">
                @foreach($posts as $post)
                    @php
                        $myReaction = $post->reactions->first()?->type;
                        $votedState = $myReaction === 'mindblown' ? 'up' : ($myReaction === 'optimisable' ? 'down' : '');
                        $score = $post->up_count - $post->down_count;
                    @endphp
                    <article wire:key="group-post-{{ $post->id }}" class="group relative bg-[#0f111a]/40 border border-white/5 rounded-3xl p-8 transition-all hover:bg-white/[0.02] hover:border-[#8B5CF6]/20">
                        <div class="flex items-start gap-8">
                             <!-- Compact Karma -->
                             <div class="flex flex-col items-center gap-2">
                                <button wire:click="vote({{ $post->id }}, 'up')" class="p-2 rounded-xl border {{ $votedState === 'up' ? 'bg-[#8B5CF6] border-[#8B5CF6] text-white shadow-[0_0_15px_rgba(139,92,246,0.4)]' : 'bg-white/5 border-white/5 text-white/40 hover:text-[#8B5CF6] hover:border-[#8B5CF6]/30' }} transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                </button>
                                <span class="text-lg font-black font-display {{ $votedState === 'up' ? 'text-[#8B5CF6]' : ($votedState === 'down' ? 'text-red-500' : 'text-white/30') }}">
                                    {{ $score }}
                                </span>
                                <button wire:click="vote({{ $post->id }}, 'down')" class="p-2 rounded-xl border {{ $votedState === 'down' ? 'bg-red-500 border-red-500 text-white shadow-[0_0_15px_rgba(239,68,68,0.4)]' : 'bg-white/5 border-white/5 text-white/40 hover:text-red-500 hover:border-red-500/30' }} transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                             </div>

                             <div class="flex-grow space-y-4">
                                <!-- Meta -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $post->user->profile_photo_url }}" class="w-8 h-8 rounded-lg border border-white/10">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-white/80 leading-tight">{{ $post->user->name }}</span>
                                            <span class="text-[10px] text-white/20 uppercase tracking-wider font-black">{{ $post->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('posts.detail', $post->id) }}" class="p-2 rounded-xl bg-white/5 border border-white/5 text-white/40 hover:text-white hover:bg-[#8B5CF6]/20 hover:border-[#8B5CF6]/30 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                </div>

                                <!-- Title -->
                                <div>
                                    <h4 class="text-xl font-display font-black text-white tracking-tight">{{ $post->title }}</h4>
                                    <p class="text-sm text-white/40 line-clamp-2 mt-2">{{ $post->short_description }}</p>
                                </div>

                                <!-- Code Snippet Preview (First file) -->
                                @if($post->latestSnippet)
                                    <div class="mt-4 rounded-xl overflow-hidden border border-white/5 bg-black/40">
                                        <div class="px-3 py-1.5 bg-white/[0.02] border-b border-white/5 flex items-center justify-between">
                                            <span class="text-[9px] font-mono text-white/30">{{ $post->latestSnippet->filename ?? 'snippet' }}</span>
                                            <span class="text-[9px] font-black uppercase text-[#8B5CF6]/60">{{ $post->latestSnippet->language }}</span>
                                        </div>
                                        <div class="p-3">
                                            <pre class="text-[10px] text-white/60 font-mono line-clamp-4"><code>{{ $post->latestSnippet->code }}</code></pre>
                                        </div>
                                    </div>
                                @endif
                             </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination (Compact) -->
            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
