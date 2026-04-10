<div class="max-w-4xl mx-auto px-6 py-12">
    <!-- Back Button -->
    <div class="mb-8 hover-trigger">
        <x-ui.back-button fallback="{{ route('dashboard') }}" />
    </div>

    <div class="mb-16 space-y-4 animate-in fade-in slide-in-from-top-8 duration-1000">
        <h1 class="text-4xl lg:text-5xl font-black tracking-tighter uppercase">{{ __('Changelog') }}</h1>
        <p class="text-on-surface-variant italic">{{ __('Tracking the evolution of the ReviewMe ecosystem.') }}</p>
    </div>

    <!-- Timeline -->
    <div class="space-y-12 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-white/5 before:to-transparent">
        
        <!-- Voting System & Data Integrity -->
        <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active animate-in fade-in zoom-in-95 duration-700">
            <!-- Icon -->
            <div class="flex items-center justify-center w-10 h-10 rounded-full border border-primary/40 bg-surface shadow-[0_0_20px_rgba(190,194,255,0.2)] md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
            </div>
            <!-- Content -->
            <div class="w-[calc(100%-4rem)] md:w-[45%] glass-panel p-6 rounded-3xl border border-white/5 hover:border-primary/30 transition-all duration-500 shadow-2xl">
                <div class="flex items-center justify-between mb-2">
                    <time class="font-mono text-[10px] uppercase tracking-widest text-primary font-black">2026-04-09</time>
                </div>
                <div class="text-xl font-bold mb-3 tracking-tight">{{ __('v1.3.1 - Interaction Stability Update') }}</div>
                <ul class="space-y-2 text-xs text-on-surface-variant/70 italic leading-relaxed">
                    <li class="flex gap-3"><span class="text-primary font-black">+</span> {{ __('Atomic voting system with database locks.') }}</li>
                    <li class="flex gap-3"><span class="text-primary font-black">+</span> {{ __('Self-voting protection (anti-fraud).') }}</li>
                    <li class="flex gap-3"><span class="text-emerald-400 font-black">~</span> {{ __('Precise karma delta calculation for reaction switches.') }}</li>
                    <li class="flex gap-3"><span class="text-emerald-400 font-black">~</span> {{ __('Reinforced DB integrity with UNIQUE reaction constraints.') }}</li>
                </ul>
            </div>
        </div>

        <!-- Global Localization & Collaboration -->
        <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group animate-in fade-in zoom-in-95 duration-700 delay-150">
            <!-- Icon -->
            <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white/10 bg-surface md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                <div class="w-2 h-2 rounded-full bg-white/20"></div>
            </div>
            <!-- Content -->
            <div class="w-[calc(100%-4rem)] md:w-[45%] glass-panel p-6 rounded-3xl border border-white/5 hover:border-white/20 transition-all duration-500 shadow-xl opacity-60 hover:opacity-100">
                <div class="flex items-center justify-between mb-2">
                    <time class="font-mono text-[10px] uppercase tracking-widest text-white/40 font-black">2026-04-09</time>
                </div>
                <div class="text-xl font-bold mb-3 tracking-tight">{{ __('v1.3.0 - Global Localization & Collaboration Sync') }}</div>
                <ul class="space-y-2 text-xs text-on-surface-variant/70 italic leading-relaxed">
                    <li class="flex gap-3"><span class="text-primary font-black">+</span> {{ __('Full site-wide internationalization (FR/EN).') }}</li>
                    <li class="flex gap-3"><span class="text-primary font-black">+</span> {{ __('Real-time group chat integration (Reverb).') }}</li>
                    <li class="flex gap-3"><span class="text-emerald-400 font-black">~</span> {{ __('Cleaned and optimized translation dictionaries.') }}</li>
                    <li class="flex gap-3"><span class="text-emerald-400 font-black">~</span> {{ __('Reinforced architectural rules (STRICT i18n & Changelog).') }}</li>
                </ul>
            </div>
        </div>

        <!-- The Versioning Update -->
        <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group animate-in fade-in zoom-in-95 duration-700 delay-150">
            <!-- Icon -->
            <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white/10 bg-surface md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                <div class="w-2 h-2 rounded-full bg-white/20"></div>
            </div>
            <!-- Content -->
            <div class="w-[calc(100%-4rem)] md:w-[45%] glass-panel p-6 rounded-3xl border border-white/5 hover:border-white/20 transition-all duration-500 shadow-xl opacity-60 hover:opacity-100">
                <div class="flex items-center justify-between mb-2">
                    <time class="font-mono text-[10px] uppercase tracking-widest text-white/40 font-black">2026-04-09</time>
                </div>
                <div class="text-xl font-bold mb-3 tracking-tight">{{ __('v1.2.0 - The Versioning Update') }}</div>
                <ul class="space-y-2 text-xs text-on-surface-variant/70 italic leading-relaxed">
                    <li class="flex gap-3"><span class="text-primary font-black">+</span> {{ __('Advanced versioning system for artifacts.') }}</li>
                    <li class="flex gap-3"><span class="text-primary font-black">+</span> {{ __('Lexical standardization (Posts/Snippets).') }}</li>
                    <li class="flex gap-3"><span class="text-emerald-400 font-black">~</span> {{ __('Refined HUD interface for better readability.') }}</li>
                </ul>
            </div>
        </div>

        <!-- Yesterday -->
        <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group animate-in fade-in zoom-in-95 duration-700 delay-200">
            <!-- Icon -->
            <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white/10 bg-surface md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                <div class="w-2 h-2 rounded-full bg-white/20"></div>
            </div>
            <!-- Content -->
            <div class="w-[calc(100%-4rem)] md:w-[45%] glass-panel p-6 rounded-3xl border border-white/5 hover:border-white/20 transition-all duration-500 shadow-xl opacity-60 hover:opacity-100">
                <div class="flex items-center justify-between mb-2">
                    <time class="font-mono text-[10px] uppercase tracking-widest text-white/40 font-black">2026-04-08</time>
                </div>
                <div class="text-xl font-bold mb-3 tracking-tight text-white/80">{{ __('v1.1.5 - Labs Optimization') }}</div>
                <ul class="space-y-2 text-xs text-on-surface-variant/70 italic leading-relaxed">
                    <li class="flex gap-3"><span>-</span> {{ __('Resolved race condition in snippet voting.') }}</li>
                    <li class="flex gap-3"><span>+</span> {{ __('Implemented Reverb real-time pulse for Labs.') }}</li>
                </ul>
            </div>
        </div>

    </div>
</div>
