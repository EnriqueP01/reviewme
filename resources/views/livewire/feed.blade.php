<div class="w-full max-w-none px-12 py-10" wire:init="loadData">
    <!-- Feed Header -->
    <div class="flex items-end justify-between mb-24 relative">
        <div class="space-y-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-1 bg-primary rounded-full"></div>
                <span class="text-[10px] font-black uppercase tracking-[0.5em] text-primary/60 italic">{{ __('FORGE BETTER CODE') }}</span>
            </div>
            <h1 class="font-display text-7xl font-black text-on-surface tracking-tighter leading-none">
                {{ __('Feed') }}<span class="text-primary">.</span>
            </h1>
        </div>

        <!-- Refined Controls -->
        <div class="flex items-center gap-6">
            <x-ui.search-input 
                model="search" 
                placeholder="{{ __('Search posts by title, technology or lens...') }}" 
                class="!w-[200px] focus:!w-[320px]"
            />

            <div class="flex items-center gap-2 bg-black/20 backdrop-blur-3xl rounded-[1.25rem] p-1.5 border border-white/5 shadow-2xl">
                <button 
                    wire:click="sortBy('recent')"
                    @class(['px-6 py-2 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] transition-all duration-500', 'bg-primary text-on-primary shadow-xl scale-105' => $sort === 'recent', 'text-on-surface-variant/40 hover:text-white hover:bg-white/5' => $sort !== 'recent'])
                >
                    {{ __('Recent') }}
                </button>
                <button 
                    wire:click="sortBy('trending')"
                    @class(['px-6 py-2 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] transition-all duration-500', 'bg-primary text-on-primary shadow-xl scale-105' => $sort === 'trending', 'text-on-surface-variant/40 hover:text-white hover:bg-white/5' => $sort !== 'trending'])
                >
                    {{ __('Trending') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Feed Content -->
    <div class="relative min-h-[400px]">
        @if(!$readyToLoad)
            <div class="space-y-24 animate-pulse">
                @foreach(range(1, 3) as $i)
                    <div class="w-full bg-surface-container-low/40 rounded-[2.5rem] p-12 border border-white/5 space-y-8">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-6">
                                <x-ui.skeleton class="w-16 h-16 rounded-[1.5rem]" />
                                <div class="space-y-3">
                                    <x-ui.skeleton class="w-64 h-8" />
                                    <x-ui.skeleton class="w-32 h-4 opacity-40" />
                                </div>
                            </div>
                            <x-ui.skeleton class="w-24 h-10 rounded-xl" />
                        </div>
                        <div class="space-y-4">
                            <x-ui.skeleton class="w-full h-4 opacity-20" />
                            <x-ui.skeleton class="w-3/4 h-4 opacity-20" />
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-ui.loader-overlay target="search, sortBy, gotoPage, nextPage, previousPage" />
            
            <div class="space-y-24">
            @foreach($posts as $post)
                <x-ui.post-card :post="$post" />
            @endforeach
            </div>
        @endif
    </div>

    <!-- Unified Pagination -->
    <div class="mt-32 pt-16 border-t border-white/5">
        <style>
            .feed-pagination nav { @apply flex items-center justify-center gap-4; }
            .feed-pagination span[aria-current="page"] span { 
                @apply !bg-primary !text-on-primary !rounded-[1.5rem] !border-none !w-16 !h-16 !flex !items-center !justify-center !text-sm !font-black !font-display !shadow-[0_0_40px_rgba(190,194,255,0.3)] !scale-110 !transition-all;
            }
            .feed-pagination a, .feed-pagination span[aria-disabled="true"] { 
                @apply !bg-surface-container-low/40 !text-on-surface-variant/40 !rounded-[1.5rem] !border !border-white/5 !w-16 !h-16 !flex !items-center !justify-center !text-sm !font-bold !font-display !transition-all hover:!bg-white/[0.08] hover:!text-white hover:!border-white/10;
            }
            .feed-pagination span[aria-disabled="true"] { @apply !opacity-20 !cursor-not-allowed; }
        </style>
        <div class="feed-pagination">
            {{ $posts->links() }}
        </div>
    </div>
</div>
