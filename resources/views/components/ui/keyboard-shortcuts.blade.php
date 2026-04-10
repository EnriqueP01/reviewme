<div x-data="{ 
    showShortcuts: false,
    handleKey(e) {
        // Don't trigger if typing in an input
        if (['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName) || document.activeElement.isContentEditable) {
            return;
        }

        const key = e.key.toLowerCase();
        
        // Modal trigger
        if (e.shiftKey && e.key === '?') {
            this.showShortcuts = !this.showShortcuts;
            return;
        }

        // Navigation shortcuts (G + key)
        if (this.pressedG && ['h', 'p', 'l', 'g', 'c', 'k'].includes(key)) {
            const routes = {
                'h': '{{ route('dashboard') }}',
                'p': '{{ route('publish') }}',
                'l': '{{ route('leaderboard') }}',
                'g': '{{ route('groups') }}',
                'c': '{{ route('changelog') }}',
                'k': '{{ route('karma') }}'
            };
            window.location.href = routes[key];
            this.pressedG = false;
            return;
        }

        if (key === 'g') {
            this.pressedG = true;
            setTimeout(() => this.pressedG = false, 500);
        }
    },
    pressedG: false
}" @keydown.window="handleKey($event)">
    <!-- Shortcuts Modal -->
    <div x-show="showShortcuts" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-surface/80 backdrop-blur-xl"
         style="display: none;"
         @click.self="showShortcuts = false"
         @keydown.escape.window="showShortcuts = false">
        
        <div class="glass-panel w-full max-w-lg rounded-round-4 border border-white/10 shadow-2xl p-10 space-y-8">
            <div class="flex items-center justify-between">
                <h2 class="text-3xl font-black tracking-tighter uppercase">{{ __('Keyboard Shortcuts') }}</h2>
                <button @click="showShortcuts = false" class="text-on-surface-variant hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <!-- Group Navigation -->
                <div class="space-y-4">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary">{{ __('Navigation') }}</h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between group">
                            <span class="text-xs font-bold text-on-surface-variant group-hover:text-white transition-colors">{{ __('Go to Dashboard') }}</span>
                            <div class="flex items-center gap-1">
                                <kbd class="px-2 py-1 rounded bg-white/5 border border-white/10 font-mono text-[10px] text-primary font-black">G</kbd>
                                <kbd class="px-2 py-1 rounded bg-white/5 border border-white/10 font-mono text-[10px] text-white">H</kbd>
                            </div>
                        </div>
                        <div class="flex items-center justify-between group">
                            <span class="text-xs font-bold text-on-surface-variant group-hover:text-white transition-colors">{{ __('New Publication') }}</span>
                            <div class="flex items-center gap-1">
                                <kbd class="px-2 py-1 rounded bg-white/5 border border-white/10 font-mono text-[10px] text-primary font-black">G</kbd>
                                <kbd class="px-2 py-1 rounded bg-white/5 border border-white/10 font-mono text-[10px] text-white">P</kbd>
                            </div>
                        </div>
                        <div class="flex items-center justify-between group">
                            <span class="text-xs font-bold text-on-surface-variant group-hover:text-white transition-colors">{{ __('Leaderboard') }}</span>
                            <div class="flex items-center gap-1">
                                <kbd class="px-2 py-1 rounded bg-white/5 border border-white/10 font-mono text-[10px] text-primary font-black">G</kbd>
                                <kbd class="px-2 py-1 rounded bg-white/5 border border-white/10 font-mono text-[10px] text-white">L</kbd>
                            </div>
                        </div>
                        <div class="flex items-center justify-between group">
                            <span class="text-xs font-bold text-on-surface-variant group-hover:text-white transition-colors">{{ __('Karma Guide') }}</span>
                            <div class="flex items-center gap-1">
                                <kbd class="px-2 py-1 rounded bg-white/5 border border-white/10 font-mono text-[10px] text-primary font-black">G</kbd>
                                <kbd class="px-2 py-1 rounded bg-white/5 border border-white/10 font-mono text-[10px] text-white">K</kbd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Global -->
                <div class="space-y-4 pt-4 border-t border-white/5">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-secondary">{{ __('Global') }}</h4>
                    <div class="flex items-center justify-between group">
                        <span class="text-xs font-bold text-on-surface-variant group-hover:text-white transition-colors">{{ __('Show this help') }}</span>
                        <kbd class="px-2 py-1 rounded bg-white/5 border border-white/10 font-mono text-[10px] text-secondary font-black">Shift + ?</kbd>
                    </div>
                </div>
            </div>

            <div class="text-center pt-4">
            </div>
        </div>
    </div>
</div>
