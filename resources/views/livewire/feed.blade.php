<div class="w-full max-w-none px-12 py-10">
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
            <div class="relative group/search">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                    <svg class="h-3.5 w-3.5 text-on-surface-variant/20 group-focus-within/search:text-primary transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="{{ __('Search...') }}" 
                    class="w-[200px] group-focus-within/search:w-[320px] bg-black/20 border border-white/5 rounded-2xl py-3 pl-12 pr-6 text-[10px] text-on-surface placeholder:text-on-surface-variant/10 focus:outline-none focus:border-primary/40 focus:ring-4 focus:ring-primary/2 transition-all duration-700 font-black uppercase tracking-widest shadow-2xl backdrop-blur-3xl"
                >
            </div>

            <div class="flex items-center gap-2 bg-black/20 backdrop-blur-3xl rounded-[1.25rem] p-1.5 border border-white/5 shadow-2xl">
                <button 
                    wire:click="sortBy('recent')"
                    @class(['px-6 py-2 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] transition-all duration-500', 'bg-primary text-on-primary shadow-xl scale-105' => $sort === 'recent', 'text-on-surface-variant/40 hover:text-white hover:bg-white/5' => $sort !== 'recent'])
                >
                    {{ __('Recent') }}
                </button>
                <button 
                    wire:click="sortBy('trending')"
                    @class(['px-6 py-2 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] transition-all duration-500', 'bg-primary text-on-primary shadow-xl scale-105' => $sort === 'trending', 'text-on-surface-variant/40 hover:text-white hover:bg-white/5' => $sort !== 'recent'])
                >
                    {{ __('Trending') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Feed Content -->
    <div class="relative min-h-[400px]">
        <x-ui.loader-overlay target="search, sortBy, gotoPage, nextPage, previousPage" />
        
        <div class="space-y-24">
        @foreach($posts as $post)
            <x-ui.post-card :post="$post" />
        @endforeach
        </div>
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
