<div class="max-w-7xl mx-auto px-6 py-16">
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
                    <p class="text-on-surface-variant text-xl mt-2 font-display italic tracking-wide">{{ $stats['level'] }}</p>
                </div>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-4">
                    @foreach($stats as $label => $value)
                        @if($label !== 'level')
                            <div class="bg-solid-container-blur border border-primary/10 px-8 py-4 rounded-2xl shadow-xl group/stat hover:border-primary/30 transition-all">
                                <span class="block text-[9px] uppercase tracking-[0.3em] text-on-surface-variant font-black mb-2 opacity-50">{{ $label }}</span>
                                <span class="block font-display font-black text-2xl text-on-surface group-hover:text-primary transition-colors">{{ $value }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <div class="hidden lg:block w-px h-32 bg-primary/10"></div>
            
            <div class="flex flex-col gap-4">
                <x-ui.button variant="primary" size="sm">{{ __('Edit Profile') }}</x-ui.button>
                <x-ui.button variant="ghost" size="sm">{{ __('Share Portfolio') }}</x-ui.button>
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
            <h3 class="font-display font-bold text-2xl text-on-surface">{{ __('Recent Curations') }}</h3>
            
            <div class="space-y-6">
                @foreach($recent_activity as $activity)
                    <x-ui.card tonal="low" class="group hover:bg-surface-high/50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-6">
                                <div class="w-3 h-3 rounded-full bg-secondary"></div>
                                <div>
                                    <h4 class="font-display font-bold text-on-surface group-hover:text-primary transition-colors">{{ $activity['title'] }}</h4>
                                    <p class="text-on-surface-variant text-xs mt-1">{{ $activity['date'] }}</p>
                                </div>
                            </div>
                            <div class="text-primary font-mono font-bold">{{ $activity['karma'] }} {{ __('Karma') }}</div>
                        </div>
                    </x-ui.card>
                @endforeach
            </div>
            
            <x-ui.button variant="ghost" class="w-full">{{ __('Load More History') }}</x-ui.button>
        </div>
    </div>
</div>
