<div class="relative group/logo">
    <div class="relative w-32 h-32 flex items-center justify-center">
        <!-- Structural layers -->
        <div class="absolute inset-0 bg-primary/20 rounded-[2.5rem] rotate-45 group-hover/logo:rotate-90 transition-all duration-1000 ease-[cubic-bezier(0.34,1.56,0.64,1)] border border-primary/30 shadow-[0_0_50px_rgba(190,194,255,0.2)]"></div>
        <div class="absolute inset-2 bg-surface rounded-[2rem] flex items-center justify-center border border-white/5 overflow-hidden">
             <!-- Scanner line effect -->
             <div class="absolute inset-0 bg-gradient-to-b from-transparent via-primary/5 to-transparent h-1/2 w-full animate-scan opacity-0 group-hover/logo:opacity-100"></div>
             <span class="text-primary font-display font-black text-6xl tracking-tighter group-hover/logo:scale-125 transition-transform duration-700">R</span>
        </div>
        
        <!-- Orbital particles -->
        <div class="absolute -inset-4 border border-primary/10 rounded-[3rem] opacity-0 group-hover/logo:opacity-100 group-hover/logo:rotate-180 transition-all duration-1000">
             <div class="absolute top-0 left-1/2 -translate-x-1/2 w-2 h-2 bg-secondary rounded-full"></div>
             <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-2 h-2 bg-secondary rounded-full"></div>
        </div>
    </div>
</div>

<style>
@keyframes scan {
    from { transform: translateY(-100%); }
    to { transform: translateY(200%); }
}
.animate-scan {
    animation: scan 3s linear infinite;
}
</style>
