import './bootstrap';

// Alpine.js est automatiquement injecté par Livewire 3.
// On enregistre les plugins et les données globales via le hook livewire:init pour éviter les doubles instances.
document.addEventListener('livewire:init', () => {
    // window.Alpine est déjà disponible ici.
});

// Service Audio Premium & Haptique
window.fx = {
    ctx: null,

    init() {
        if (!this.ctx) {
            this.ctx = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (this.ctx.state === 'suspended') {
            this.ctx.resume();
        }
    },

    /**
     * Joue un son procédural pour une interaction.
     */
    play(type = 'default') {
        this.init();

        // Haptique (Mobile)
        if ('vibrate' in navigator) {
            const pattern =
                type === 'up' ? [15] : type === 'down' ? [40, 30] : [5];
            navigator.vibrate(pattern);
        }

        try {
            const osc = this.ctx.createOscillator();
            const gain = this.ctx.createGain();

            osc.connect(gain);
            gain.connect(this.ctx.destination);

            let freq = 1000;
            let duration = 0.1;
            let volume = 0.05;

            switch (type) {
                case 'up':
                    freq = 1800;
                    osc.frequency.exponentialRampToValueAtTime(
                        2400,
                        this.ctx.currentTime + 0.1
                    );
                    duration = 0.15;
                    volume = 0.04;
                    break;
                case 'down':
                    freq = 600;
                    osc.frequency.exponentialRampToValueAtTime(
                        400,
                        this.ctx.currentTime + 0.2
                    );
                    duration = 0.2;
                    volume = 0.06;
                    break;
                case 'success':
                    freq = 880; // A5
                    osc.frequency.setValueAtTime(880, this.ctx.currentTime);
                    osc.frequency.exponentialRampToValueAtTime(
                        1760,
                        this.ctx.currentTime + 0.1
                    );
                    duration = 0.3;
                    volume = 0.03;
                    break;
                case 'hover':
                    freq = 1200;
                    duration = 0.01;
                    volume = 0.005;
                    break;
                case 'scan':
                    freq = 200;
                    osc.frequency.exponentialRampToValueAtTime(
                        1200,
                        this.ctx.currentTime + 0.3
                    );
                    duration = 0.4;
                    volume = 0.02;
                    break;
                case 'error':
                    freq = 200;
                    osc.frequency.linearRampToValueAtTime(
                        100,
                        this.ctx.currentTime + 0.3
                    );
                    duration = 0.4;
                    volume = 0.08;
                    break;
                default:
                    freq = 1400;
                    duration = 0.05;
            }

            osc.frequency.setValueAtTime(freq, this.ctx.currentTime);
            gain.gain.setValueAtTime(volume, this.ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(
                0.001,
                this.ctx.currentTime + duration
            );

            osc.type = type === 'error' ? 'sawtooth' : 'sine';
            osc.start();
            osc.stop(this.ctx.currentTime + duration);
        } catch (e) {
            console.warn('Audio FX failed:', e);
        }
    },
};

// Aliasing pour compatibilité avec l'existant
window.haptic = window.fx;
