<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <!-- Sidebar Navigation -->
        <aside class="lg:col-span-3 space-y-8 animate-in fade-in slide-in-from-left-8 duration-1000">
            <div class="space-y-4">
                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-primary px-4">{{ __('Quick Start') }}</h4>
                <nav class="space-y-1">
                    <a href="#introduction" class="block px-4 py-2 text-xs font-bold text-on-surface hover:bg-white/5 rounded-xl transition-all">{{ __('Introduction') }}</a>
                    <a href="#installation" class="block px-4 py-2 text-xs font-bold text-on-surface-variant/60 hover:text-on-surface hover:bg-white/5 rounded-xl transition-all">{{ __('Installation') }}</a>
                    <a href="#authentication" class="block px-4 py-2 text-xs font-bold text-on-surface-variant/60 hover:text-on-surface hover:bg-white/5 rounded-xl transition-all">{{ __('Authentication') }}</a>
                </nav>
            </div>
            <div class="space-y-4">
                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-secondary px-4">{{ __('Core Concepts') }}</h4>
                <nav class="space-y-1">
                    <a href="#labs" class="block px-4 py-2 text-xs font-bold text-on-surface-variant/60 hover:text-on-surface hover:bg-white/5 rounded-xl transition-all">{{ __('Labs & Groups') }}</a>
                    <a href="#curation" class="block px-4 py-2 text-xs font-bold text-on-surface-variant/60 hover:text-on-surface hover:bg-white/5 rounded-xl transition-all">{{ __('Curation Workflow') }}</a>
                    <a href="#karma" class="block px-4 py-2 text-xs font-bold text-on-surface-variant/60 hover:text-on-surface hover:bg-white/5 rounded-xl transition-all">{{ __('Karma System') }}</a>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="lg:col-span-9 space-y-24 animate-in fade-in slide-in-from-right-8 duration-1000 delay-200">
            <!-- Introduction -->
            <section id="introduction" class="space-y-6">
                <h2 class="text-4xl font-black tracking-tighter uppercase">{{ __('Platform Overview') }}</h2>
                <div class="prose prose-invert prose-p:italic prose-p:text-on-surface-variant/80 max-w-none">
                    <p>
                        ReviewMe is more than a code sharing tool; it's a dedicated environment for <strong>architectural validation</strong>. 
                        Designed for developer teams and educational institutions, it focuses on the "Why" and "How" rather than just the "What".
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                        <div class="p-6 rounded-3xl bg-white/[0.02] border border-white/5 space-y-3">
                            <div class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center text-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <h4 class="font-bold text-on-surface">{{ __('Secure Audits') }}</h4>
                            <p class="text-[11px] leading-relaxed opacity-60">{{ __('Private Labs ensure your experimental logic stays within your team.') }}</p>
                        </div>
                        <div class="p-6 rounded-3xl bg-white/[0.02] border border-white/5 space-y-3">
                            <div class="w-8 h-8 rounded-lg bg-secondary/20 flex items-center justify-center text-secondary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <h4 class="font-bold text-on-surface">{{ __('Rapid Feedback') }}</h4>
                            <p class="text-[11px] leading-relaxed opacity-60">{{ __('Real-time interactions powered by Reverb for instant mentorship.') }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Installation -->
            <section id="installation" class="space-y-6">
                <h3 class="text-2xl font-black tracking-tighter uppercase text-secondary">{{ __('Getting Started') }}</h3>
                <div class="space-y-4">
                    <p class="text-sm text-on-surface-variant italic">{{ __('The fastest way to deploy ReviewMe is via Docker Compose.') }}</p>
                    <div class="rounded-2xl bg-black/40 border border-white/5 p-6 font-mono text-xs text-secondary/80 leading-relaxed overflow-hidden relative group">
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="p-2 hover:bg-white/5 rounded-lg transition-all" title="Copy">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                            </button>
                        </div>
                        <div class="flex items-center gap-3 mb-4 opacity-40">
                            <div class="w-2.5 h-2.5 rounded-full bg-error/20"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-secondary/20"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-primary/20"></div>
                        </div>
                        <p><span class="text-primary/60">#</span> Clone the elite stack</p>
                        <p>git clone https://github.com/EnriqueP01/reviewme.git</p>
                        <p class="mt-2"><span class="text-primary/60">#</span> Ignite the engine</p>
                        <p>docker-compose up -d --build</p>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>
