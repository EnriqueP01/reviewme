<div 
    x-data="{ 
        mouseX: 0, 
        mouseY: 0,
        updateMouse(e) {
            this.mouseX = e.clientX;
            this.mouseY = e.clientY;
            document.body.style.setProperty('--mouse-x', this.mouseX + 'px');
            document.body.style.setProperty('--mouse-y', this.mouseY + 'px');
        }
    }"
    @mousemove.window="updateMouse($event)"
    class="fixed inset-0 pointer-events-none z-0 overflow-hidden"
    aria-hidden="true"
>
    <!-- Base Layer: Subtle Static Grid -->
    <div class="absolute inset-0 opacity-[0.25]" style="background-image: radial-gradient(circle at 1px 1px, var(--primary) 1px, transparent 0); background-size: 32px 32px;"></div>

    <!-- Interactive Layer: The "Lens" Grid -->
    <svg class="absolute inset-0 w-full h-full text-primary" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <!-- Pattern for the reactive grid -->
            <pattern id="lens-grid" width="64" height="64" patternUnits="userSpaceOnUse">
                <!-- Grid Lines -->
                <path d="M 64 0 L 0 0 0 64" fill="none" stroke="currentColor" stroke-width="1.5" stroke-opacity="0.4" />
                <!-- Intersection Points -->
                <circle cx="0" cy="0" r="2.5" fill="currentColor" fill-opacity="0.8" />
            </pattern>

            <!-- Radial Mask following mouse -->
            <mask id="mouse-mask">
                <radialGradient id="mouse-gradient" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="white" />
                    <stop offset="100%" stop-color="black" />
                </radialGradient>
                <circle r="400" fill="url(#mouse-gradient)">
                    <animateTransform 
                        attributeName="transform" 
                        type="translate" 
                        :from="`${mouseX} ${mouseY}`" 
                        :to="`${mouseX} ${mouseY}`" 
                        dur="0s" 
                        fill="freeze" 
                    />
                </circle>
            </mask>
            
            <!-- Alternative CSS-based masking for better performance -->
        </defs>

        <!-- The actual reactive grid, masked by a CSS radial gradient -->
        <rect width="100%" height="100%" fill="url(#lens-grid)" 
              style="mask-image: radial-gradient(circle 300px at var(--mouse-x, 0) var(--mouse-y, 0), black 0%, transparent 100%);
                     -webkit-mask-image: radial-gradient(circle 300px at var(--mouse-x, 0) var(--mouse-y, 0), black 0%, transparent 100%);" />
    </svg>
    
    <!-- Ambient Glows -->
    <div class="absolute inset-0 opacity-20"
         style="background: radial-gradient(circle 600px at var(--mouse-x, 0) var(--mouse-y, 0), rgba(190, 194, 255, 0.1), transparent 100%);">
    </div>
</div>
