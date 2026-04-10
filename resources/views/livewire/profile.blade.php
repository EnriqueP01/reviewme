<div class="w-full max-w-none px-12 py-10" 
    x-data="{ 
    copied: false,
    share() {
        navigator.clipboard.writeText(window.location.href);
        this.copied = true;
        setTimeout(() => this.copied = false, 3000);
    }
}" wire:init="loadData"
    x-init="$watch('$wire.readyToLoad', value => {
        if(value && {{ Auth::id() === $user->id ? 'true' : 'false' }}) {
            confetti({
                particleCount: 150,
                spread: 70,
                origin: { y: 0.6 },
                colors: ['#bec2ff', '#10b981', '#ffffff']
            });
        }
    })">
    <!-- Profile Hero -->
    <div class="relative mb-12">
        <div class="mb-8">
            <x-ui.back-button fallback="{{ route('dashboard') }}" />
        </div>
        <div class="flex flex-col md:flex-row items-center gap-12">
            <!-- Avatar Monolith -->
            <div class="relative group">
                <div class="absolute inset-0 bg-primary/20 blur-3xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <div class="w-48 h-48 rounded-[2.5rem] bg-surface-container flex items-center justify-center text-primary relative z-10 overflow-hidden border border-outline-variant/10 group-hover:border-primary/30 transition-all duration-700 shadow-2xl">
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    <!-- Scanline effect -->
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-primary/5 to-transparent pointer-events-none animate-scan opacity-30"></div>
                </div>
            </div>

            <!-- Identity -->
            <div class="flex-grow space-y-6 text-center md:text-left">
                <div>
                    <h1 class="font-display text-5xl font-bold tracking-tight text-on-surface flex flex-wrap items-center gap-x-4 gap-y-2 justify-center md:justify-start">
                        <span>{{ $user->name }}</span>
                        <span class="text-primary opacity-40 font-mono text-2xl">@</span><span class="text-primary opacity-60 font-mono text-3xl">{{ $user->handle }}</span>
                    </h1>
                    
                    @if($user->bio)
                        <p class="mt-4 text-on-surface-variant text-lg max-w-2xl italic leading-relaxed opacity-80 decoration-primary/10">
                            "{{ $user->bio }}"
                        </p>
                    @endif

                    <div class="inline-flex flex-wrap items-center gap-3 mt-6">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-container border border-white/5 shadow-sm">
                            <div class="w-2 h-2 rounded-full {{ str_replace('text-', 'bg-', $stats['level']['color']) }} animate-pulse"></div>
                            <span class="{{ $stats['level']['color'] }} text-xs font-black uppercase tracking-widest">{{ __($stats['level']['label']) }}</span>
                        </div>

                        <!-- GitHub Connection Indicator -->
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-container border border-white/5 shadow-sm cursor-help transition-colors hover:bg-white/5" 
                             title="{{ $user->github_id ? __('Connected to GitHub') : __('Not connected to GitHub') }}">
                            @if($user->github_id)
                                <svg class="w-3.5 h-3.5 text-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.041-1.416-4.041-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                <span class="text-[10px] font-black uppercase tracking-widest text-on-surface">{{ __('GitHub') }}</span>
                            @else
                                <svg class="w-3.5 h-3.5 text-on-surface-variant opacity-40" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.041-1.416-4.041-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                <span class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant opacity-60">{{ __('GitHub') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-4">
                    @foreach($stats as $label => $value)
                        @if($label !== 'level')
                            <div class="bg-solid-container-blur border border-primary/10 px-8 py-4 rounded-2xl shadow-xl group/stat hover:border-primary/30 transition-all">
                                <span class="block text-[9px] uppercase tracking-[0.3em] text-on-surface-variant font-black mb-2 opacity-50">{{ __($label) }}</span>
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
                @if($this->isOwnProfile)
                    <a href="{{ route('profile.edit') }}" wire:navigate>
                        <x-ui.button variant="primary" size="sm" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            {{ __('Edit Profile') }}
                        </x-ui.button>
                    </a>
                @endif
                <x-ui.button variant="ghost" size="sm" @click="share()">
                    <span x-show="!copied">{{ __('Share Profile') }}</span>
                    <span x-show="copied" x-cloak class="text-secondary">{{ __('Link Copied') }}</span>
                </x-ui.button>
            </div>
        </div>
    </div>

    <!-- Activity & Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
        <!-- Sidebar: Details -->
        <div class="space-y-12">
            <div class="glass-panel p-10 rounded-[2.5rem] border-subtle">
                <h3 class="font-display font-black text-xl text-on-surface mb-8 italic">{{ __('Expertise') }}</h3>
                <div class="grid grid-cols-1 gap-4">
                    @forelse($this->skills as $skill)
                        <div class="flex items-center justify-between p-4 bg-[#0d0e12]/40 rounded-2xl border border-white/5 group/skill hover:bg-primary/5 transition-colors">
                            <span class="text-on-surface-variant font-bold text-sm tracking-tight group-hover/skill:text-primary transition-colors">{{ __($skill->lens) }}</span>
                            <span class="font-display font-black text-on-surface">{{ number_format($skill->score) }}</span>
                        </div>
                    @empty
                        <p class="text-on-surface-variant text-xs opacity-50">{{ __('No specialized skills yet.') }}</p>
                    @endforelse
                </div>
            </div>


        </div>

        <!-- Main Column: Activity -->
        <div class="lg:col-span-2 space-y-12">
            <!-- Contribution Heatmap (ReviewMe centric) -->
            <div class="glass-panel p-10 rounded-[2.5rem] border-subtle overflow-hidden relative">
                <!-- Loading Overlay -->
                <div wire:loading wire:target="setPeriod" class="absolute inset-x-10 inset-y-12 z-50 bg-surface/80 backdrop-blur-sm flex items-center justify-center rounded-2xl">
                    <div class="flex gap-1.5 flex-wrap justify-center max-w-md">
                        @foreach(range(1, 28) as $i)
                            <x-ui.skeleton type="block" class="w-3.5 h-3.5 opacity-40" />
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-between mb-8">
                    <div class="space-y-1">
                        <h3 class="font-display font-bold text-xl text-on-surface italic">{{ __('ReviewMe Activity') }}</h3>
                        <div class="flex gap-2">
                            @foreach(['week' => '7D', 'month' => '30D', 'year' => '1Y'] as $key => $label)
                                <button 
                                    wire:click="setPeriod('{{ $key }}')"
                                    class="text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded-md transition-all {{ $period === $key ? 'bg-primary text-on-primary' : 'bg-surface-highest text-on-surface-variant opacity-40 hover:opacity-100' }}"
                                >
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[9px] text-on-surface-variant uppercase tracking-widest opacity-40">{{ __('Less') }}</span>
                        <div class="flex gap-1">
                            <div class="w-2.5 h-2.5 rounded-sm bg-surface-highest"></div>
                            <div class="w-2.5 h-2.5 rounded-sm bg-emerald-500/20"></div>
                            <div class="w-2.5 h-2.5 rounded-sm bg-emerald-500/50"></div>
                            <div class="w-2.5 h-2.5 rounded-sm bg-emerald-500"></div>
                        </div>
                        <span class="text-[9px] text-on-surface-variant uppercase tracking-widest opacity-40">{{ __('More') }}</span>
                    </div>
                </div>

                <div class="flex flex-wrap gap-1.5 min-h-[3.5rem]">
                    @if(!$readyToLoad)
                        @foreach(range(1, 40) as $i)
                            <x-ui.skeleton type="block" class="w-3.5 h-3.5 opacity-20" />
                        @endforeach
                    @else
                        @foreach($this->activityGrid as $day)
                            <div 
                                title="{{ $day['date'] }}: {{ $day['count'] }} {{ __('contributions') }}"
                                class="w-3.5 h-3.5 rounded-sm transition-all duration-300 hover:scale-150 hover:z-20 cursor-crosshair
                                    {{ $day['count'] === 0 ? 'bg-surface-highest' : '' }}
                                    {{ $day['count'] === 1 ? 'bg-emerald-500/20' : '' }}
                                    {{ $day['count'] === 2 ? 'bg-emerald-500/50' : '' }}
                                    {{ $day['count'] >= 3 ? 'bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.4)]' : '' }}"
                            ></div>
                        @endforeach
                    @endif
                </div>
                <p class="mt-6 text-[10px] text-on-surface-variant font-mono opacity-40 uppercase tracking-widest">
                    {{ array_sum($contributions) }} {{ __('contributions in the last year') }}
                </p>
            </div>

            <h3 class="font-display font-bold text-2xl text-on-surface">{{ __('Posts') }}</h3>
            
            <div class="space-y-6 relative">
                <!-- Posts Loading State -->
                <div wire:loading.delay.longest wire:target="loadMore,setPeriod" class="space-y-6">
                    @foreach(range(1, 3) as $i)
                        <div class="p-8 bg-surface-container/50 rounded-3xl border border-white/5 flex items-center justify-between">
                            <div class="flex items-center gap-6">
                                <x-ui.skeleton type="circle" class="w-3 h-3" />
                                <div class="space-y-3">
                                    <x-ui.skeleton class="w-48" />
                                    <x-ui.skeleton class="w-24 opacity-50" />
                                </div>
                            </div>
                            <x-ui.skeleton class="w-16" />
                        </div>
                    @endforeach
                </div>

                <div wire:loading.remove wire:target="loadMore,setPeriod" class="space-y-6 animate-in fade-in duration-700">
                    @if(!$readyToLoad)
                        @foreach(range(1, 3) as $i)
                            <div class="p-8 bg-surface-container/50 rounded-3xl border border-white/5 flex items-center justify-between">
                                <div class="flex items-center gap-6">
                                    <x-ui.skeleton type="circle" class="w-3 h-3" />
                                    <div class="space-y-3">
                                        <x-ui.skeleton class="w-48" />
                                        <x-ui.skeleton class="w-24 opacity-50" />
                                    </div>
                                </div>
                                <x-ui.skeleton class="w-16" />
                            </div>
                        @endforeach
                    @else
                        @forelse($posts as $post)
                            <a href="{{ route('posts.detail', $post->id) }}" wire:navigate class="block">
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
                                <p class="text-on-surface-variant text-sm font-display italic">{{ __('No posts yet.') }}</p>
                            </div>
                        @endforelse
                    @endif
                </div>
            </div>
            
            @if($user->posts()->count() > $perPage)
                <x-ui.button variant="ghost" class="w-full" wire:click="loadMore" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ __('Load More Posts') }}</span>
                    <span wire:loading>{{ __('Loading...') }}</span>
                </x-ui.button>
            @endif
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
</div>
