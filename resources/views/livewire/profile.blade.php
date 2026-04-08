<div class="max-w-7xl mx-auto px-6 py-16" x-data="{ 
    copied: false,
    share() {
        navigator.clipboard.writeText(window.location.href);
        this.copied = true;
        setTimeout(() => this.copied = false, 3000);
    }
}">
    <!-- Profile Hero -->
    <div class="relative mb-20">
        <div class="flex flex-col md:flex-row items-center gap-12">
            <!-- Avatar Monolith -->
            <div class="relative group">
                <div class="absolute inset-0 bg-primary/20 blur-3xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <div class="w-48 h-48 rounded-round-4 bg-surface-container flex items-center justify-center text-primary relative z-10 overflow-hidden border border-outline-variant/10">
                    <span class="font-display font-bold text-6xl italic">{{ substr($user->name, 0, 1) }}</span>
                    <!-- Scanline effect -->
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-primary/5 to-transparent pointer-events-none animate-pulse"></div>
                </div>
            </div>

            <!-- Identity -->
            <div class="flex-grow space-y-6 text-center md:text-left">
                <div>
                    <h1 class="font-display text-5xl font-bold tracking-tight text-on-surface">{{ $user->name }}</h1>
                    <p class="text-on-surface-variant text-xl mt-2 font-display italic tracking-wide">{{ __($stats['level']) }}</p>
                </div>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-4">
                    @foreach($stats as $label => $value)
                        @if($label !== 'level')
                            <div class="bg-solid-container-blur border border-primary/10 px-8 py-4 rounded-2xl shadow-xl group/stat hover:border-primary/30 transition-all">
                                <span class="block text-[9px] uppercase tracking-[0.3em] text-on-surface-variant font-black mb-2 opacity-50">{{ $label }}</span>
                                <span class="block font-display font-black text-2xl text-on-surface group-hover:text-primary transition-colors">
                                    {{ is_numeric($value) ? number_format($value) : $value }}
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <div class="hidden lg:block w-px h-32 bg-primary/10"></div>
            
            <div class="flex flex-col gap-4">
                <a href="{{ route('profile.edit') }}" wire:navigate>
                    <x-ui.button variant="primary" size="sm" class="w-full">{{ __('Edit Profile') }}</x-ui.button>
                </a>
                <x-ui.button variant="ghost" size="sm" @click="share()">
                    <span x-show="!copied">{{ __('Share Portfolio') }}</span>
                    <span x-show="copied" x-cloak class="text-secondary">{{ __('Link Secured') }}</span>
                </x-ui.button>
            </div>
        </div>
    </div>

    <!-- Activity & Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
        <!-- Sidebar: Details -->
        <div class="space-y-12">
            <div class="glass-panel p-10 rounded-[2.5rem] border-subtle">
                <h3 class="font-display font-black text-xl text-on-surface mb-8 italic">{{ __('About Curator') }}</h3>
                <p class="text-on-surface-variant leading-relaxed text-sm opacity-70">
                    {{ __('Passionate about micro-optimizations and clean architectural patterns. Currently exploring the intersection of PHP and physics-based UI.') }}
                </p>
                <div class="mt-10 pt-10 border-t border-primary/10 space-y-5">
                    <div class="flex justify-between text-sm">
                        <span class="text-on-surface-variant">{{ __('Location') }}</span>
                        <span class="text-on-surface">{{ __('Remote (Void)') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-on-surface-variant">{{ __('Member since') }}</span>
                        <span class="text-on-surface">{{ $stats['joined'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Column: Activity -->
        <div class="lg:col-span-2 space-y-12">
            <!-- Contribution Heatmap -->
            <div class="glass-panel p-10 rounded-[2.5rem] border-subtle overflow-hidden">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="font-display font-bold text-xl text-on-surface italic">{{ __('Sync Density') }}</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-[9px] text-on-surface-variant uppercase tracking-widest opacity-40">{{ __('Less') }}</span>
                        <div class="flex gap-1">
                            <div class="w-2.5 h-2.5 rounded-sm bg-surface-highest"></div>
                            <div class="w-2.5 h-2.5 rounded-sm bg-primary/30"></div>
                            <div class="w-2.5 h-2.5 rounded-sm bg-primary/60"></div>
                            <div class="w-2.5 h-2.5 rounded-sm bg-primary"></div>
                        </div>
                        <span class="text-[9px] text-on-surface-variant uppercase tracking-widest opacity-40">{{ __('More') }}</span>
                    </div>
                </div>

                <div class="flex flex-wrap gap-1.5" x-data="{
                    days: Array.from({length: 365}, (_, i) => {
                        const d = new Date();
                        d.setDate(d.getDate() - (364 - i));
                        const dateStr = d.toISOString().split('T')[0];
                        return {
                            date: dateStr,
                            count: {{ json_encode($contributions) }}[dateStr] || 0
                        };
                    })
                }">
                    <template x-for="day in days" :key="day.date">
                        <div 
                            :title="day.date + ': ' + day.count + ' artifacts'"
                            class="w-3 h-3 rounded-sm transition-all duration-500 hover:scale-150 hover:z-20 cursor-crosshair"
                            :class="{
                                'bg-surface-highest': day.count === 0,
                                'bg-primary/30': day.count === 1,
                                'bg-primary/60': day.count === 2,
                                'bg-primary': day.count >= 3,
                                'shadow-[0_0_8px_rgba(190,194,255,0.4)]': day.count >= 3
                            }"
                        ></div>
                    </template>
                </div>
                <p class="mt-6 text-[10px] text-on-surface-variant font-mono opacity-40 uppercase tracking-widest">
                    {{ count($contributions) }} {{ __('active nodes discovered in the last cycle') }}
                </p>
            </div>

            <h3 class="font-display font-bold text-2xl text-on-surface">{{ __('Dispatch History') }}</h3>
            
            <div class="space-y-6">
                @forelse($posts as $post)
                    <a href="{{ route('vibe.detail', $post->id) }}" wire:navigate class="block">
                        <x-ui.card tonal="low" class="group hover:bg-surface-high/50 transition-all border-none">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-6">
                                    <div class="w-3 h-3 rounded-full bg-primary shadow-[0_0_10px_rgba(190,194,255,0.4)]"></div>
                                    <div>
                                        <h4 class="font-display font-bold text-on-surface group-hover:text-primary transition-colors text-lg">{{ $post->title }}</h4>
                                        <p class="text-on-surface-variant text-[10px] uppercase tracking-widest mt-1 font-black opacity-40">{{ $post->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                     <div class="text-on-surface-variant font-mono text-sm group-hover:text-primary transition-colors">
                                        {{ number_format($post->reactions_count) }} <span class="text-[10px] uppercase tracking-tighter opacity-40 ml-1">{{ __('Karma') }}</span>
                                     </div>
                                     <svg class="w-5 h-5 text-on-surface-variant/20 group-hover:text-primary group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        </x-ui.card>
                    </a>
                @empty
                    <div class="py-20 text-center border-2 border-dashed border-outline-variant/10 rounded-3xl">
                        <p class="text-on-surface-variant text-sm font-display italic">{{ __('No artifacts dispatched yet.') }}</p>
                    </div>
                @endforelse
            </div>
            
            @if($user->posts()->count() > $perPage)
                <x-ui.button variant="ghost" class="w-full" wire:click="loadMore" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ __('Load More History') }}</span>
                    <span wire:loading>{{ __('Syncing...') }}</span>
                </x-ui.button>
            @endif
        </div>
    </div>
</div>
