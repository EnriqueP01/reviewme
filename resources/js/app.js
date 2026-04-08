import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
// Alpine.start() is NOT needed here as Livewire 3 handles it.

window.haptic = {
    ctx: null,
    initCtx() {
        if (!this.ctx) this.ctx = new (window.AudioContext || window.webkitAudioContext)();
        if (this.ctx.state === 'suspended') this.ctx.resume();
    },
    play(type = 'default') {
        this.initCtx();
        
        // 1. Tactile (Vibration)
        if ('vibrate' in navigator) {
            const pattern = type === 'up' ? [10] : (type === 'down' ? [20] : [5]);
            navigator.vibrate(pattern);
        }

        // 2. Audio (Procedural)
        try {
            const osc = this.ctx.createOscillator();
            const gain = this.ctx.createGain();
            
            osc.connect(gain);
            gain.connect(this.ctx.destination);

            let freq = 1200;
            let duration = 0.08;

            if (type === 'up') {
                freq = 2200;
                osc.frequency.exponentialRampToValueAtTime(2600, this.ctx.currentTime + duration);
            } else if (type === 'down') {
                freq = 1400;
                osc.frequency.exponentialRampToValueAtTime(1000, this.ctx.currentTime + duration);
            } else {
                freq = 1800;
                osc.frequency.exponentialRampToValueAtTime(1800, this.ctx.currentTime + duration);
            }

            osc.frequency.setValueAtTime(freq, this.ctx.currentTime);
            gain.gain.setValueAtTime(0.04, this.ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, this.ctx.currentTime + duration);

            osc.type = 'sine';
            osc.start();
            osc.stop(this.ctx.currentTime + duration);
        } catch (e) {}
    }
};

