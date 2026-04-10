<div
    x-data="{ 
        notifications: [],
        add(event) {
            const id = Date.now();
            const type = event.type || 'info';
            this.notifications.push({
                id,
                type: type,
                title: event.title || this.getDefaultTitle(type),
                message: event.message,
                show: false
            });
            
            // Son de notification Premium
            if (window.fx) {
                window.fx.play(type);
            }

            setTimeout(() => {
                const index = this.notifications.findIndex(n => n.id === id);
                if (index !== -1) this.notifications[index].show = true;
            }, 50);

            // Auto-remove
            setTimeout(() => {
                this.remove(id);
            }, 6000);
        },
        remove(id) {
            const index = this.notifications.findIndex(n => n.id === id);
            if (index !== -1) {
                this.notifications[index].show = false;
                setTimeout(() => {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                }, 400);
            }
        },
        getDefaultTitle(type) {
            switch(type) {
                case 'success': return '{{ __("Opération Réussie") }}';
                case 'error': return '{{ __("Échec du Système") }}';
                default: return '{{ __("Information Système") }}';
            }
        }
    }"
    x-init="
        @if(session()->has('success'))
            add({ type: 'success', message: '{!! addslashes(session('success')) !!}' });
        @endif
        @if(session()->has('status'))
            add({ type: 'success', message: '{!! addslashes(session('status')) !!}' });
        @endif
        @if(session()->has('error'))
            add({ type: 'error', message: '{!! addslashes(session('error')) !!}' });
        @endif
        @if(session()->has('info'))
            add({ type: 'info', message: '{!! addslashes(session('info')) !!}' });
        @endif
    "
    @vibe-notif.window="add($event.detail)"
    class="fixed bottom-10 right-10 z-[200] flex flex-col gap-4 items-end pointer-events-none"
>
    <template x-for="n in notifications" :key="n.id">
        <div 
            x-show="n.show"
            x-transition:enter="transition cubic-bezier(0.175, 0.885, 0.32, 1.275) duration-600"
            x-transition:enter-start="opacity-0 translate-y-10 scale-50"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in-out duration-400"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 translate-x-20 scale-90"
            class="pointer-events-auto"
        >
            <div 
                @click="remove(n.id)"
                class="relative group cursor-pointer overflow-hidden p-[1px] rounded-[1.25rem] shadow-[0_30px_60px_-15px_rgba(0,0,0,0.6)]"
                :class="{
                    'bg-gradient-to-br from-emerald-500/50 to-transparent': n.type === 'success',
                    'bg-gradient-to-br from-rose-500/50 to-transparent': n.type === 'error',
                    'bg-gradient-to-br from-primary/50 to-transparent': n.type === 'info'
                }"
            >
                <div class="bg-[#0a0a0c]/90 backdrop-blur-3xl rounded-[1.2rem] px-6 py-5 flex items-start gap-5 min-w-[340px] max-w-[450px]">
                    <!-- Progress Bar (Timer) -->
                    <div class="absolute bottom-0 left-0 h-0.5 bg-white/10 w-full overflow-hidden">
                        <div class="h-full bg-white/20" :class="{ 'bg-emerald-500/40': n.type === 'success', 'bg-rose-500/40': n.type === 'error', 'bg-primary/40': n.type === 'info' }" style="animation: toast-progress 6s linear forwards"></div>
                    </div>

                    <!-- Icon Slot -->
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center relative">
                            <div class="absolute inset-0 opacity-20 blur-lg rounded-full" :class="{ 'bg-emerald-500': n.type === 'success', 'bg-rose-500': n.type === 'error', 'bg-primary': n.type === 'info' }"></div>
                            
                            <!-- Success -->
                            <template x-if="n.type === 'success'">
                                <svg class="w-6 h-6 text-emerald-400 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            </template>

                            <!-- Error -->
                            <template x-if="n.type === 'error'">
                                <svg class="w-6 h-6 text-rose-400 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>
                            </template>

                            <!-- Info -->
                            <template x-if="n.type === 'info'">
                                <svg class="w-6 h-6 text-primary relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" /></svg>
                            </template>
                        </div>
                    </div>

                    <div class="flex-grow space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-black uppercase tracking-[0.4em] opacity-40" x-text="n.title"></span>
                            <span class="text-[8px] font-mono opacity-20 font-bold" x-text="new Date(n.id).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></span>
                        </div>
                        <p class="text-[13px] font-medium leading-relaxed text-on-surface/90 antialiased" x-text="n.message"></p>
                    </div>

                    <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="p-1 hover:bg-white/5 rounded-md transition-colors">
                            <svg class="w-3 h-3 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<style>
    @keyframes toast-progress {
        from { width: 100%; }
        to { width: 0%; }
    }
</style>
