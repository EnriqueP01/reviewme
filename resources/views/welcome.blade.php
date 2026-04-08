<x-guest-layout>
    <!-- Hero Concept -->
    <div class="relative w-full max-w-7xl mx-auto pt-32 pb-48 px-12 overflow-hidden">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            <div class="flex-1 text-left relative z-10">
                <div class="inline-flex items-center gap-3 px-4 py-1.5 rounded-full bg-surface-container-high border border-outline-variant text-[10px] font-black uppercase tracking-widest text-primary mb-12 animate-fade-in-up">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                    Ask: "What do you think?"
                </div>
                
                <h1 class="text-7xl lg:text-9xl font-black mb-10 leading-[0.8] tracking-tighter animate-fade-in-up">
                    FORGE <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-b from-white to-white/40">BETTER</span> <br/>
                    CODE.
                </h1>
                
                <p class="text-xl text-on-surface-variant max-w-xl mb-12 animate-fade-in-up font-medium leading-relaxed italic" style="animation-delay: 0.1s">
                    Not a debugger. Not a stack overflow. <br/>
                    A collaborative space for developers to share perspective and evolve their thinking.
                </p>
                
                <div class="flex items-center gap-6 animate-fade-in-up" style="animation-delay: 0.2s">
                    <x-ui.button variant="primary" size="lg" onclick="window.location.href='{{ route('register') }}'">
                        Start a Vibe
                    </x-ui.button>
                    <a href="#concept" class="text-sm font-black uppercase tracking-widest text-on-surface-variant hover:text-white transition-colors">How it works</a>
                </div>
            </div>

            <!-- Code Preview Artifact -->
            <div class="flex-1 w-full relative group animate-fade-in-up" style="animation-delay: 0.3s">
                <div class="absolute -inset-4 bg-gradient-to-tr from-primary/20 via-secondary/10 to-transparent blur-3xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative glass-panel rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
                    <div class="flex items-center gap-4 px-6 py-4 bg-white/5 border-b border-white/5">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-error/40"></div>
                            <div class="w-3 h-3 rounded-full bg-tertiary/40"></div>
                            <div class="w-3 h-3 rounded-full bg-secondary/40"></div>
                        </div>
                        <div class="text-[10px] font-mono text-white/40 uppercase tracking-widest">identity_validation.ts — v1</div>
                    </div>
                    <div class="p-8 font-mono text-sm leading-relaxed overflow-hidden relative">
                        <div class="absolute inset-0 bg-grid opacity-10"></div>
                        <div class="relative z-10 space-y-1">
                            <p class="text-on-surface-variant"><span class="text-secondary">export</span> <span class="text-primary">const</span> validateIdentity = (token: string) => {</p>
                            <p class="text-on-surface-variant pl-4"><span class="text-primary-container bg-primary/20 px-1 rounded">/* What do you think about this map? */</span></p>
                            <p class="text-on-surface-variant pl-4"><span class="text-primary">return</span> claims.reduce((acc, claim) => {</p>
                            <p class="text-on-surface-variant pl-8">...logic here</p>
                            <p class="text-on-surface-variant pl-4">}, {});</p>
                            <p class="text-on-surface-variant">};</p>
                        </div>
                        
                        <!-- Inline Review Mock -->
                        <div class="absolute right-10 top-1/2 -translate-y-1/2 w-64 glass-panel p-4 rounded-xl shadow-2xl border border-primary/30 translate-x-4 group-hover:translate-x-0 transition-transform">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-5 h-5 rounded-full bg-secondary shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                                <span class="text-[9px] font-black uppercase text-secondary">Expert Opinion</span>
                            </div>
                            <p class="text-[11px] text-white leading-tight italic">"This reduce feels robust, but have you considered using typed schemas here for better DX?"</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Bento Concept Grid -->
    <div id="concept" class="max-w-7xl mx-auto px-12 py-32">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 h-[800px]">
            
            <!-- Target Audience Card -->
            <div class="md:col-span-8 glass-panel rounded-3xl p-12 relative overflow-hidden group">
                <div class="absolute inset-0 bg-grid opacity-5 group-hover:opacity-10 transition-opacity"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-primary/20 blur-[100px] rounded-full translate-x-1/2 translate-y-1/2"></div>
                
                <h2 class="text-4xl font-bold mb-8 relative z-10">For Curators & Creators</h2>
                <p class="text-lg text-on-surface-variant max-w-md relative z-10 italic">
                    Whether you are a student hungry for perspective or a senior dev sharing wisdom, ReviewMe is your tactical layer.
                </p>
                
                <div class="mt-16 grid grid-cols-2 gap-8 relative z-10">
                    <div class="p-6 rounded-2xl bg-white/5 border border-white/5">
                        <span class="text-secondary font-black block mb-2 tracking-[0.2em] text-[10px]">REPUTATION</span>
                        <div class="text-2xl font-bold">Karma System</div>
                    </div>
                    <div class="p-6 rounded-2xl bg-white/5 border border-white/5">
                        <span class="text-tertiary font-black block mb-2 tracking-[0.2em] text-[10px]">SOCIAL</span>
                        <div class="text-2xl font-bold">Tech Threads</div>
                    </div>
                </div>
            </div>

            <!-- GitHub Card -->
            <div class="md:col-span-4 glass-panel rounded-3xl p-12 relative overflow-hidden group flex flex-col justify-between">
                <div class="absolute inset-0 bg-grid opacity-10 pointer-events-none"></div>
                <div class="text-6xl text-white/10 group-hover:text-primary/40 transition-colors">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.205.084 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.22 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold mb-4">Native Authentication</h3>
                    <p class="text-on-surface-variant text-sm italic">One-click GitHub entry. No forms. No barriers. Only developers.</p>
                </div>
            </div>

            <!-- Live Vibe Card -->
            <div class="md:col-span-4 bg-primary rounded-3xl p-12 relative overflow-hidden group">
                <div class="absolute inset-0 bg-grid-interactive opacity-20 transition-opacity"></div>
                <div class="flex flex-col h-full justify-between relative z-10">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-3xl font-black uppercase text-white tracking-tighter">Real-Time Pulse</h3>
                        <p class="text-white/80 text-sm mt-4 italic font-medium">See who is reading. See who is typing. Feel the collaboration.</p>
                    </div>
                </div>
            </div>

            <!-- Detailed Features Card -->
            <div class="md:col-span-8 glass-panel rounded-3xl p-12 overflow-hidden relative group">
                <div class="flex flex-col md:flex-row gap-12 h-full">
                    <div class="flex-1">
                        <h3 class="text-4xl font-bold mb-8">Evolutionary Workflow</h3>
                        <ul class="space-y-6">
                            <li class="flex items-center gap-4 group/item">
                                <div class="w-2 h-2 rounded-full bg-secondary"></div>
                                <span class="text-on-surface-variant group-hover/item:text-white transition-colors">V1 to V2 progression system</span>
                            </li>
                            <li class="flex items-center gap-4 group/item">
                                <div class="w-2 h-2 rounded-full bg-tertiary"></div>
                                <span class="text-on-surface-variant group-hover/item:text-white transition-colors">Inline line-by-line commenting</span>
                            </li>
                            <li class="flex items-center gap-4 group/item">
                                <div class="w-2 h-2 rounded-full bg-accent-blue"></div>
                                <span class="text-on-surface-variant group-hover/item:text-white transition-colors">Private group spaces (Labs)</span>
                            </li>
                        </ul>
                    </div>
                    <div class="flex-1 bg-white/5 rounded-2xl border border-white/5 p-8 relative overflow-hidden">
                        <div class="absolute inset-0 bg-grid opacity-10"></div>
                        <div class="text-[10px] font-black tracking-[0.3em] text-white/20 mb-12 uppercase">Stack Compliance</div>
                        <div class="flex flex-wrap gap-3">
                             <span class="px-3 py-1 bg-surface-container-highest rounded text-[10px] font-mono text-secondary">Laravel 11</span>
                             <span class="px-3 py-1 bg-surface-container-highest rounded text-[10px] font-mono text-primary">Livewire 3</span>
                             <span class="px-3 py-1 bg-surface-container-highest rounded text-[10px] font-mono text-tertiary">Shiki.php</span>
                             <span class="px-3 py-1 bg-surface-container-highest rounded text-[10px] font-mono text-accent-blue">Reverb</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>
