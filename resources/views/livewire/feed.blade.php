<div class="max-w-5xl mx-auto px-12 py-16">
    <!-- Feed Header -->
    <div class="flex items-end justify-between mb-20 border-b border-outline-variant/10 pb-10">
        <div class="space-y-2">
            <h2 class="font-display text-5xl font-black text-on-surface tracking-tight">{{ __('Perspectives') }}</h2>
            <p class="text-on-surface-variant text-base tracking-wide font-medium">{{ __('Synthetic analysis of architectural patterns.') }}</p>
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
                    placeholder="{{ __('Search vibes...') }}" 
                    class="bg-[#0f111a]/60 backdrop-blur-2xl border border-white/5 rounded-full py-3.5 pl-12 pr-8 text-xs text-on-surface placeholder:text-on-surface-variant/20 focus:outline-none focus:border-white/20 transition-all w-64 group-hover/search:w-80 group-focus-within/search:!w-[32rem] shadow-2xl shadow-black/40 font-medium tracking-wide"
                >
                <div class="absolute inset-0 rounded-full bg-primary/5 opacity-0 group-focus-within/search:opacity-100 blur-xl -z-10 transition-opacity duration-700"></div>
            </div>

            <div class="flex items-center gap-4 bg-surface-container-low/30 backdrop-blur-md rounded-full p-1 border border-white/5">
                <x-ui.button 
                    variant="{{ $sort === 'trending' ? 'primary' : 'ghost' }}" 
                    size="sm" class="!px-8" pill 
                    wire:click="sortBy('trending')"
                    wire:loading.attr="disabled"
                >
                    {{ __('Trending') }}
                </x-ui.button>
                <x-ui.button 
                    variant="{{ $sort === 'recent' ? 'primary' : 'ghost' }}" 
                    size="sm" class="!px-8" pill 
                    wire:click="sortBy('recent')"
                    wire:loading.attr="disabled"
                >
                    {{ __('Recent') }}
                </x-ui.button>
            </div>
        </div>
    </div>

    <!-- Feed Content -->
    <div class="space-y-24">
        @foreach($perspectives as $item)
            <article wire:key="post-{{ $item['id'] }}" class="group relative animate-fade-in-up" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
                <div class="flex items-start gap-12">
                    <!-- Karma Lens (Vertical Sidebar) -->
                    <div class="flex flex-col items-center gap-3 sticky top-32" 
                         x-data="{ 
                             voted: '{{ $item['my_vote'] ?: '' }}',
                             score: {{ $item['points'] }},
                             isVoting: false,
                             handleVote(dir) {
                                if (this.isVoting) return;
                                this.isVoting = true;

                                // Feedback Haptique & Sonore
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

                                // Libérer le verrou après la réponse serveur (simulé ici par un mini-timeout pour fluidité ou via événement Livewire)
                                setTimeout(() => { this.isVoting = false; }, 300);
                             }
                         }">
                        <button 
                            wire:click="vote({{ $item['id'] }}, 'up')"
                            wire:loading.attr="disabled"
                            @click="handleVote('up')"
                            :class="voted === 'up' ? 'bg-primary btn-primary-fix border-primary shadow-[0_0_25px_rgba(190,194,255,0.5)] scale-110' : 'bg-surface-container-low text-on-surface-variant hover:text-primary hover:border-primary/40 active:scale-95 border-primary/5'"
                            class="w-14 h-14 rounded-2xl border flex items-center justify-center transition-all duration-300 group/vote"
                        >
                            <svg class="w-6 h-6 transition-transform group-hover/vote:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                        </button>
                        
                        <div class="py-2 flex flex-col items-center select-none">
                            <span class="font-display font-black text-3xl tracking-tighter transition-all duration-300"
                                  :class="voted === 'up' ? 'text-primary' : (voted === 'down' ? 'text-error' : 'text-on-surface/50')"
                                  x-text="score"></span>
                            <span class="text-[8px] font-black uppercase tracking-[0.3em] text-on-surface-variant/30 mt-1">{{ __('Score') }}</span>
                        </div>
                        
                        <button 
                            wire:click="vote({{ $item['id'] }}, 'down')"
                            wire:loading.attr="disabled"
                            @click="handleVote('down')"
                            :class="voted === 'down' ? 'bg-error text-white border-error shadow-[0_0_20px_rgba(239,68,68,0.4)] scale-110' : 'bg-surface-container-low text-on-surface-variant hover:text-error hover:border-error/40 active:scale-95 border-primary/5'"
                            class="w-14 h-14 rounded-2xl border flex items-center justify-center transition-all duration-300 group/vote"
                        >
                            <svg class="w-6 h-6 transition-transform group-hover/vote:translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>

                    <!-- Main Article Content -->
                    <div class="flex-grow space-y-8">
                        <!-- Metadata Row -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-5">
                                <div class="relative group/avatar">
                                    <div class="w-12 h-12 rounded-2xl bg-solid-container-blur border border-primary/20 flex items-center justify-center text-sm font-black font-display text-primary italic overflow-hidden transition-all duration-500 group-hover/avatar:rotate-12 group-hover/avatar:scale-110 group-hover/avatar:border-primary/40 shadow-lg">
                                        {{ strtoupper(substr($item['author'], 0, 1)) }}
                                        <div class="absolute inset-x-0 bottom-0 h-1/3 bg-primary/10"></div>
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-secondary rounded-full border-[3px] border-surface flex items-center justify-center shadow-lg">
                                        <div class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></div>
                                    </div>
                                </div>
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-3">
                                        <span class="text-base font-black text-on-surface tracking-tight">{{ $item['author'] }}</span>
                                        <span class="px-2 py-0.5 rounded bg-primary/5 border border-primary/10 text-[8px] font-black uppercase tracking-widest text-primary/60">{{ __('Expert') }}</span>
                                    </div>
                                    <span class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold opacity-60 mt-1">{{ __('Lead Architect') }} • {{ $item['time_ago'] }}</span>
                                </div>
                            </div>
                            
                            <div class="opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-4 group-hover:translate-x-0">
                                <x-ui.button variant="ghost" size="sm" class="!bg-primary/5 border-primary/10 hover:!bg-primary/10 hover:!text-primary" href="{{ route('vibe.detail', $item['id']) }}">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    {{ __('Inspect') }}
                                </x-ui.button>
                            </div>
                        </div>

                        <!-- The Code Lens (Primary Display) -->
                        <div class="transition-all duration-500 transform group-hover:translate-x-2">
                            <x-ui.code-block 
                                :title="$item['title']" 
                                :code="$item['snippet']" 
                                :type="$item['type']" 
                                language="php"
                            />
                        </div>

                        <!-- Tags & Social Counter -->
                        <div class="flex items-center gap-4 pt-2">
                             <div class="flex gap-2">
                                <span class="px-3 py-1 rounded-md bg-surface-container text-[10px] font-black uppercase tracking-widest text-on-surface-variant border border-outline-variant/10">#Refactoring</span>
                                <span class="px-3 py-1 rounded-md bg-surface-container text-[10px] font-black uppercase tracking-widest text-on-surface-variant border border-outline-variant/10">#Laravel</span>
                             </div>
                             <div class="flex-grow border-t border-outline-variant/5"></div>
                             <div class="flex items-center gap-6 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40">
                                <span>24 {{ __('Reviews') }}</span>
                                <span>12 {{ __('Shares') }}</span>
                             </div>
                        </div>
                    </div>
                </div>
                
                <!-- Corner Decoration -->
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary/5 blur-3xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
            </article>
        @endforeach
    </div>
</div>
