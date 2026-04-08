<div
    x-data="{ 
        notifications: [],
        add(event) {
            const id = Date.now();
            this.notifications.push({
                id,
                type: event.type || 'info',
                message: event.message,
                show: false
            });
            
            // Son de notification
            if (window.fx) {
                window.fx.play(event.type === 'error' ? 'error' : 'success');
            }

            setTimeout(() => {
                const index = this.notifications.findIndex(n => n.id === id);
                if (index !== -1) this.notifications[index].show = true;
            }, 100);

            setTimeout(() => {
                this.remove(id);
            }, 5000);
        },
        remove(id) {
            const index = this.notifications.findIndex(n => n.id === id);
            if (index !== -1) {
                this.notifications[index].show = false;
                setTimeout(() => {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                }, 500);
            }
        }
    }"
    @vibe-notif.window="add($event.detail)"
    class="fixed bottom-12 right-12 z-[100] flex flex-col gap-4 items-end pointer-events-none"
>
    <template x-for="n in notifications" :key="n.id">
        <div 
            x-show="n.show"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-12 scale-90"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-400"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="pointer-events-auto"
        >
            <div 
                class="glass-panel px-8 py-5 rounded-2xl flex items-center gap-6 border-l-4 shadow-[0_20px_50px_rgba(0,0,0,0.5)] min-w-[320px]"
                :class="{
                    'border-primary bg-primary/5': n.type === 'info' || n.type === 'success',
                    'border-error bg-error/5': n.type === 'error'
                }"
            >
                <div class="flex-grow">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[8px] font-black uppercase tracking-[0.3em] opacity-40" :class="n.type === 'error' ? 'text-error' : 'text-primary'">
                            {{ __('System Notification') }}
                        </span>
                        <div class="h-px flex-grow bg-white/5"></div>
                    </div>
                    <p class="text-sm font-display font-medium text-on-surface italic" x-text="n.message"></p>
                </div>

                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center bg-white/5 border border-white/5">
                        <svg x-show="n.type !== 'error'" class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                        <svg x-show="n.type === 'error'" class="w-4 h-4 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
