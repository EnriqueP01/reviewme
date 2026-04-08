<div class="max-w-5xl mx-auto px-12 py-16">
    <!-- Feed Header -->
    <div class="flex items-end justify-between mb-20 border-b border-outline-variant/10 pb-10">
        <div class="space-y-2">
            <h2 class="font-display text-5xl font-black text-on-surface tracking-tight">{{ __('Perspectives') }}</h2>
            <p class="text-on-surface-variant text-base tracking-wide font-medium">{{ __('Synthetic analysis of architectural patterns.') }}</p>
        </div>
        <div class="flex items-center gap-4 bg-surface-container rounded-full p-1 border border-outline-variant/20">
            <x-ui.button 
                variant="{{ $sort === 'trending' ? 'primary' : 'ghost' }}" 
                size="sm" class="!px-6" pill 
                wire:click="sortBy('trending')"
                wire:loading.attr="disabled"
            >
                {{ __('Trending') }}
            </x-ui.button>
            <x-ui.button 
                variant="{{ $sort === 'recent' ? 'primary' : 'ghost' }}" 
                size="sm" class="!px-6" pill 
                wire:click="sortBy('recent')"
                wire:loading.attr="disabled"
            >
                {{ __('Recent') }}
            </x-ui.button>
        </div>
    </div>

    <!-- Feed Content -->
    <div class="space-y-24">
        @foreach($perspectives as $item)
            <article wire:key="post-{{ $item['id'] }}" class="group relative animate-fade-in-up" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
                <div class="flex items-start gap-12">
                    <!-- Karma Lens (Vertical Sidebar) -->
                    <div class="flex flex-col items-center gap-3 sticky top-32" x-data="{ localVoted: '{{ $item['my_vote'] }}' }">
                        <button 
                            wire:click="vote({{ $item['id'] }}, 'up')"
                            wire:loading.attr="disabled"
                            @click="localVoted = localVoted === 'up' ? null : 'up'"
                            :class="localVoted === 'up' ? 'bg-primary btn-primary-fix border-primary shadow-[0_0_20px_rgba(190,194,255,0.4)]' : 'bg-solid-container-blur text-on-surface-variant hover:text-primary hover:border-primary/30 active:scale-90 shadow-sm'"
                            class="w-14 h-14 rounded-2xl border border-primary-subtle flex items-center justify-center transition-all duration-300 group/vote"
                        >
                            <svg class="w-6 h-6 transition-transform group-hover/vote:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                        </button>
                        
                        <div class="py-1 flex flex-col items-center select-none">
                            <span class="font-display font-black text-2xl text-primary tracking-tighter leading-none" :class="localVoted ? 'scale-110' : ''">{{ number_format($item['points']) }}</span>
                            <span class="text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/40 mt-1">{{ __('Karma') }}</span>
                        </div>
                        
                        <button 
                            wire:click="vote({{ $item['id'] }}, 'down')"
                            wire:loading.attr="disabled"
                            @click="localVoted = localVoted === 'down' ? null : 'down'"
                            :class="localVoted === 'down' ? 'bg-error text-white border-error shadow-[0_0_10px_rgba(239,68,68,0.4)]' : 'bg-solid-container-blur text-on-surface-variant hover:text-error hover:border-error/30 active:scale-90 shadow-sm'"
                            class="w-14 h-14 rounded-2xl border border-primary-subtle flex items-center justify-center transition-all duration-300 group/vote"
                        >
                            <svg class="w-6 h-6 transition-transform group-hover/vote:translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
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
