<div class="relative z-[100]" wire:poll.30s>
    <!-- Backdrop (Optimized opacity for performance) -->
    <div x-show="$wire.isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="$wire.isOpen = false"
         class="fixed inset-0 bg-surface/40 backdrop-blur-[2px]"></div>

    <!-- Drawer Panel (GPU Accelerated) -->
    <div x-show="$wire.isOpen"
         x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-500"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         style="will-change: transform;"
         class="fixed inset-y-0 right-0 w-full max-w-lg bg-surface-container-highest/95 backdrop-blur-3xl border-l border-white/5 shadow-[-50px_0_100px_rgba(0,0,0,0.5)] flex flex-col overflow-hidden transform-gpu">
        
        <!-- Header -->
        <div class="h-24 px-10 flex items-center justify-between border-b border-white/5 bg-white/[0.02]">
            <div class="flex items-center gap-6">
                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <div>
                    <h2 class="font-display font-black text-xl uppercase tracking-widest text-on-surface">{{ __('Notifications') }}</h2>
                    <p class="text-[10px] font-bold text-on-surface-variant opacity-40 uppercase tracking-widest mt-1">{{ $unreadCount }} {{ __('unread items') }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                @if($unreadCount > 0)
                    <button wire:click="markAllAsRead" class="text-[10px] font-black uppercase tracking-widest text-primary hover:text-white transition-colors">
                        {{ __('Clear All') }}
                    </button>
                    <div class="w-1 h-1 rounded-full bg-white/10"></div>
                @endif
                <button @click="$wire.isOpen = false" class="p-4 rounded-xl hover:bg-white/5 text-on-surface-variant transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <!-- Content (Scroll Optimized) -->
        <div class="flex-1 overflow-y-auto custom-scrollbar px-6 py-10 space-y-4" style="contain: content;">
            @forelse($notifications as $notification)
                <div class="relative group rounded-3xl transition-all duration-300 {{ $notification->read_at ? 'opacity-80' : 'bg-white/[0.02] border border-white/5' }} hover:bg-white/5">
                    @php
                        $url = $notification->data['action_url'] ?? '#';
                        $hasUrl = isset($notification->data['action_url']);
                    @endphp
                    
                    <a href="{{ $url }}" 
                       @if($hasUrl) wire:navigate @endif
                       @click="$wire.markAsRead('{{ $notification->id }}'); if('{{ $hasUrl }}') $wire.isOpen = false"
                       class="block p-6 no-underline">
                        
                        <div class="flex gap-6">
                            <!-- Icon -->
                            <div class="shrink-0">
                                <div class="w-12 h-12 rounded-2xl border border-white/5 flex items-center justify-center transition-all duration-300 {{ $notification->read_at ? 'bg-surface-container-low text-on-surface-variant opacity-40' : 'bg-primary text-on-primary shadow-[0_0_20px_rgba(190,194,255,0.2)]' }}">
                                    @php
                                        $type = $notification->data['type'] ?? 'info';
                                    @endphp
                                    @if(str_contains($type, 'karma'))
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    @elseif(str_contains($type, 'review') || str_contains($type, 'comment'))
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    @endif
                                </div>
                            </div>

                            <!-- Text -->
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-[9px] font-black uppercase tracking-[0.2em] {{ $notification->read_at ? 'text-on-surface-variant opacity-40' : 'text-primary' }}">
                                        {{ $notification->data['title'] ?? __('Notification') }}
                                    </span>
                                    <span class="text-[8px] font-medium text-on-surface-variant opacity-30">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <h3 class="text-on-surface text-sm font-display font-medium leading-relaxed group-hover:text-primary transition-colors">
                                    {!! $notification->data['message'] ?? '' !!}
                                </h3>
                                
                                <div class="flex items-center gap-2 mt-4 text-[8px] font-black uppercase tracking-widest text-on-surface-variant/40 group-hover:text-primary transition-all">
                                    <span>{{ __('Open Activity') }}</span>
                                    <svg class="w-3 h-3 translate-y-[0.5px] transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="h-64 flex flex-col items-center justify-center text-center opacity-40">
                    <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ __('No new activity') }}</span>
                    <p class="text-[9px] mt-2 opacity-60">{{ __('Your interactions will appear here.') }}</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="p-10 bg-white/[0.01] border-t border-white/5">
            <div class="grid grid-cols-2 gap-4">
                <div class="p-6 rounded-2xl bg-surface-container-high border border-white/5 overflow-hidden relative">
                    <div class="relative z-10">
                        <span class="text-[8px] font-black uppercase tracking-widest text-on-surface-variant opacity-40">{{ __('Session Total') }}</span>
                        <div class="text-2xl font-display font-black text-on-surface mt-2">+{{ $todayKarma }} <span class="text-primary text-xs tracking-normal">Karma</span></div>
                    </div>
                    <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-primary/10 rounded-full blur-xl"></div>
                </div>
                <div class="p-6 rounded-2xl bg-surface-container-high border border-white/5">
                    <span class="text-[8px] font-black uppercase tracking-widest text-on-surface-variant opacity-40">{{ __('Pulse Status') }}</span>
                    <div class="flex items-center gap-3 mt-4">
                        <div class="w-2 h-2 rounded-full bg-secondary animate-pulse"></div>
                        <span class="text-[10px] font-bold text-on-surface uppercase tracking-widest">{{ __('Active') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
