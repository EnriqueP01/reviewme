@props([
    'fallback' => null,
    'label'    => null,
])

{{-- Bouton retour animé — style Monolith avec son Web Audio --}}
<button
    x-data="{
        rippleX: 0, rippleY: 0, rippling: false,

        {{-- Génère un son de navigation grave via Web Audio API --}}
        playSound() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();

                const osc = ctx.createOscillator();
                const gain = ctx.createGain();

                osc.connect(gain);
                gain.connect(ctx.destination);

                osc.type = 'sine';
                osc.frequency.setValueAtTime(440, ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(220, ctx.currentTime + 0.12);

                gain.gain.setValueAtTime(0.18, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.18);

                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.18);
            } catch(e) {}
        },

        handleClick(event) {
            {{-- Ripple au point de clic --}}
            const rect = event.currentTarget.getBoundingClientRect();
            this.rippleX = event.clientX - rect.left;
            this.rippleY = event.clientY - rect.top;
            this.rippling = true;
            setTimeout(() => this.rippling = false, 600);

            this.playSound();

            setTimeout(() => {
                if (window.history.length > 1) {
                    window.history.back();
                } else {
                    Livewire.navigate('{{ $fallback ?? url('/') }}');
                }
            }, 120);
        }
    }"
    @click="handleClick($event)"
    {{ $attributes->merge(['class' => 'back-btn-monolith group relative overflow-hidden inline-flex items-center gap-3 px-5 py-2.5 rounded-2xl border border-white/8 bg-surface-container-low/60 backdrop-blur-xl text-on-surface-variant hover:text-on-surface hover:border-primary/30 hover:bg-surface-container transition-all duration-300 shadow-sm hover:shadow-primary/10 hover:shadow-lg cursor-pointer']) }}
>
    {{-- Lueur de fond au hover --}}
    <span class="absolute inset-0 rounded-2xl bg-gradient-to-r from-primary/0 via-primary/5 to-primary/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></span>

    {{-- Ripple effect --}}
    <span
        x-show="rippling"
        x-transition:enter="transition-none"
        x-transition:leave="transition duration-500 ease-out"
        x-transition:leave-start="opacity-40 scale-0"
        x-transition:leave-end="opacity-0 scale-[4]"
        :style="`left: ${rippleX}px; top: ${rippleY}px;`"
        class="absolute w-10 h-10 -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary/30 pointer-events-none"
        x-cloak
    ></span>

    {{-- Icône fléche --}}
    <span class="relative flex items-center justify-center w-7 h-7 rounded-xl bg-surface-container-highest/60 group-hover:bg-primary/15 border border-white/5 group-hover:border-primary/25 transition-all duration-300">
        <svg
            class="w-3.5 h-3.5 text-on-surface-variant group-hover:text-primary transition-all duration-300 group-hover:-translate-x-0.5"
            fill="none" stroke="currentColor" viewBox="0 0 24 24"
            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
        >
            <polyline points="15 18 9 12 15 6"/>
        </svg>
    </span>

    {{-- Label --}}
    <span class="relative font-mono text-[10px] font-black uppercase tracking-[0.18em] pt-px">
        {{ $label ?? __('Back') }}
    </span>
</button>
