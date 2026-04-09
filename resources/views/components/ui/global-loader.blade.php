<div x-data="{ 
        loading: false,
        percent: 0,
        timer: null,
        start() {
            this.loading = true;
            this.percent = 0;
            clearInterval(this.timer);
            this.timer = setInterval(() => {
                if (this.percent < 90) {
                    this.percent += (90 - this.percent) * 0.1;
                }
            }, 100);
        },
        stop() {
            this.percent = 100;
            setTimeout(() => {
                this.loading = false;
                setTimeout(() => { this.percent = 0; }, 300);
            }, 500);
            clearInterval(this.timer);
        }
    }"
    x-init="
        document.addEventListener('livewire:load', () => {
            Livewire.hook('request', ({ respond, error }) => {
                start();
                respond(() => stop());
                error(() => stop());
            });
        });
        // Pour Livewire v3 (utilisé ici d'après composer.json)
        document.addEventListener('livewire:init', () => {
            Livewire.hook('request', ({ respond, fail }) => {
                start();
                respond(() => stop());
                fail(() => stop());
            });
        });
    "
    class="fixed top-0 left-0 right-0 z-[9999] pointer-events-none"
>
    <!-- Progress Bar -->
    <div 
        x-show="loading"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="h-0.5 bg-gradient-to-r from-primary via-secondary to-primary shadow-[0_0_10px_rgba(190,194,255,0.8)] transition-all duration-300 ease-out"
        :style="`width: ${percent}%`"
    ></div>

    <!-- Spinner Top Right -->
    <div 
        x-show="loading"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="absolute top-4 right-4"
    >
        <div class="relative w-8 h-8">
            <div class="absolute inset-0 border-2 border-primary/20 rounded-full"></div>
            <div class="absolute inset-0 border-2 border-t-primary rounded-full animate-spin"></div>
        </div>
    </div>
</div>
