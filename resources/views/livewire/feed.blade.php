<div class="max-w-4xl mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-12">
        <div>
            <h2 class="font-display text-3xl font-bold text-on-surface">{{ __('Perspectives') }}</h2>
            <p class="text-on-surface-variant text-sm mt-1 italic">{{ __('Hand-picked code architecture insights.') }}</p>
        </div>
        <div class="flex gap-2">
            <x-ui.button variant="ghost" size="sm">{{ __('Trending') }}</x-ui.button>
            <x-ui.button variant="ghost" size="sm">{{ __('Recent') }}</x-ui.button>
        </div>
    </div>

    <div class="space-y-12">
        @foreach($perspectives as $item)
            <div class="animate-spring-hover">
                <div class="flex items-start gap-6">
                    <!-- Karma Sidebar -->
                    <div class="flex flex-col items-center gap-1 py-4 px-2 w-16">
                        <button class="text-on-surface-variant hover:text-primary transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4l-8 8h16l-8-8z"/></svg>
                        </button>
                        <span class="font-display font-bold text-lg text-primary">{{ number_format($item['points']) }}</span>
                        <button class="text-on-surface-variant hover:text-secondary transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 20l8-8H4l8 8z"/></svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="flex-grow space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-surface-highest flex items-center justify-center text-xs font-bold font-display text-on-surface-variant italic">
                                    {{ substr($item['author'], 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-on-surface">{{ $item['author'] }}</span>
                                <span class="text-xs text-on-surface-variant">• {{ $item['time_ago'] }}</span>
                            </div>
                            <x-ui.button variant="outline" size="sm" as="a" href="{{ route('vibe.detail', $item['id']) }}">{{ __('Curate') }}</x-ui.button>
                        </div>

                        <x-ui.code-block 
                            :title="$item['title']" 
                            :code="$item['snippet']" 
                            :type="$item['type']" 
                        />
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
