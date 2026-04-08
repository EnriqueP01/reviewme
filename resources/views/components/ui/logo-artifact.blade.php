@props(['size' => 'w-32 h-32'])

<div class="relative group/logo {{ $size }}">
    <div class="relative w-full h-full flex items-center justify-center">
        <!-- Structural layers -->
        <div class="absolute inset-0 bg-primary/20 rounded-[25%] rotate-45 group-hover/logo:rotate-90 transition-all duration-1000 ease-[cubic-bezier(0.34,1.56,0.64,1)] border border-primary/30 shadow-[0_0_50px_rgba(190,194,255,0.2)]"></div>
        <div class="absolute inset-[10%] bg-surface rounded-[20%] flex items-center justify-center border border-white/5 overflow-hidden">
             <!-- Scanner line effect -->
             <div class="absolute inset-0 bg-gradient-to-b from-transparent via-primary/5 to-transparent h-1/2 w-full animate-scan opacity-0 group-hover/logo:opacity-100"></div>
             
             <!-- Official RV Logo (PNG) -->
             <div class="relative w-[75%] h-[75%] group-hover/logo:scale-125 transition-transform duration-700 pointer-events-none drop-shadow-[0_0_15px_rgba(190,194,255,0.4)]">
                <img src="{{ asset('images/logo.png') }}" alt="ReviewMe Logo" class="w-full h-full object-contain">
             </div>
        </div>
        
        <!-- Orbital particles -->
        <div class="absolute -inset-[15%] border border-primary/10 rounded-full opacity-0 group-hover/logo:opacity-100 group-hover/logo:rotate-180 transition-all duration-1000">
             <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[10%] h-[10%] bg-secondary rounded-full"></div>
             <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[10%] h-[10%] bg-secondary rounded-full"></div>
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
