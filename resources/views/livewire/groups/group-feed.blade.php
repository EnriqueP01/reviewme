<div class="space-y-16">
    <!-- Search & Filters -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 py-8 border-b border-white/5 relative group/controls">
        <div class="absolute inset-0 bg-gradient-to-r from-primary/5 to-transparent opacity-0 group-hover/controls:opacity-100 transition-opacity"></div>
        
        <x-ui.search-input 
            model="search" 
            placeholder="{{ __('Search posts in this group...') }}" 
            containerClass="max-w-xl w-full z-10"
        />

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
                    <x-ui.post-card :post="$post" />
                @endforeach
            </div>

            <!-- Dashboard Pagination -->
            <div class="mt-24 pt-12 border-t border-white/5">
                <style>
                    .group-pagination nav { @apply flex items-center justify-center gap-4; }
                    .group-pagination span[aria-current="page"] span { 
                        @apply !bg-primary !text-on-primary !rounded-[1.25rem] !border-none !w-14 !h-14 !flex !items-center !justify-center !text-sm !font-black !font-display !shadow-[0_0_30px_rgba(190,194,255,0.4)] !scale-110 !transition-all;
                    }
                    .group-pagination a, .group-pagination span[aria-disabled="true"] { 
                        @apply !bg-surface-container-low/40 !text-on-surface-variant/40 !rounded-[1.25rem] !border !border-white/5 !w-14 !h-14 !flex !items-center !justify-center !text-sm !font-bold !font-display !transition-all hover:!bg-white/[0.08] hover:!text-white hover:!border-white/10;
                    }
                    .group-pagination span[aria-disabled="true"] { @apply !opacity-20 !cursor-not-allowed; }
                </style>
                <div class="group-pagination">
                    {{ $posts->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
