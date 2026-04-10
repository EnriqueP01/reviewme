<x-guest-layout>
    <div x-data="{ 
        scrolled: false,
        mouse: { x: 0, y: 0 },
        updateMouse(e) {
            this.mouse.x = e.clientX;
            this.mouse.y = e.clientY;
        }
    }" @scroll.window="scrolled = window.pageYOffset > 50" @mousemove="updateMouse($event)" class="relative w-full">

        <!-- Hero Section -->
        <section class="relative min-h-[70vh] flex items-center justify-center overflow-hidden pt-0 lg:pt-4">
            <div class="max-w-7xl mx-auto px-6 lg:px-12 relative z-10 w-full">
                <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-24">
                    <div class="flex-1 text-left stagger-in" 
                         x-data="{ visible: false }" x-init="setTimeout(() => visible = true, 100)"
                         :class="{ 'opacity-100': visible, 'opacity-0': !visible }">
                        
                        <div class="flex items-center gap-6 mb-6 transition-all duration-700 delay-100"
                             :style="`transform: translate(${mouse.x * 0.01}px, ${mouse.y * 0.01}px)`">
                            <x-ui.logo size="w-14 h-14" font="text-xl" class="hover:rotate-12 transition-transform duration-500" />
                            <div class="inline-flex items-center gap-3 px-4 py-1.5 rounded-full bg-surface-container-high border border-outline-variant text-[10px] font-black uppercase tracking-widest text-primary shadow-[0_0_20px_rgba(190,194,255,0.1)]">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                                {{ __('Online Now') }}
                            </div>
                        </div>
                        
                        <h1 class="text-6xl lg:text-[80px] font-black mb-6 leading-[0.85] tracking-tighter transition-all duration-1000 delay-200">
                            {{ __('CREATE') }} <br/>
                            <span class="text-transparent bg-clip-text bg-gradient-to-b from-white to-white/40">{{ __('YOUR IDEAS') }}</span> <br/>
                            {{ __('TOGETHER.') }}
                        </h1>
                        
                        <p class="text-base lg:text-lg text-on-surface-variant max-w-lg mb-8 font-medium leading-relaxed italic transition-all duration-1000 delay-300">
                            {{ __('Tired of building in isolation? Stop the solitary grind. ReviewMe connects students with peers and supervisors to validate concepts before they hit production.') }}
                        </p>
                        
                        <div class="flex items-center gap-8 transition-all duration-1000 delay-400">
                            <x-ui.button variant="primary" size="lg" shadow="shadow-[0_0_50px_rgba(190,194,255,0.2)]" href="{{ route('register') }}" class="group">
                                <span class="group-hover:translate-x-1 transition-transform inline-block">{{ __('Start a Review') }}</span>
                            </x-ui.button>
                            <a href="#benefits" class="text-sm font-black uppercase tracking-widest text-on-surface-variant hover:text-white transition-all flex items-center gap-3 group">
                                {{ __('Explore') }}
                                <svg class="w-4 h-4 group-hover:translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Code Preview -->
                    <div class="flex-1 w-full relative group"
                         x-data="{ orient: { x: 0, y: 0 } }"
                         @mousemove="orient.x = ($event.clientX - ($el.offsetLeft + $el.offsetWidth/2)) / 35; orient.y = ($event.clientY - ($el.offsetTop + $el.offsetHeight/2)) / 35"
                         :style="`transform: perspective(1000px) rotateY(${orient.x}deg) rotateX(${-orient.y}deg)`">
                        
                        <div class="absolute -inset-10 bg-gradient-to-tr from-primary/30 via-secondary/10 to-transparent blur-3xl opacity-30 group-hover:opacity-60 transition-opacity"></div>
                        
                        <!-- Peer Comment -->
                        <div class="absolute -top-12 left-1/2 -translate-x-1/2 w-72 glass-panel p-5 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.6)] border border-primary/40 z-50 scale-90 group-hover:scale-105 opacity-0 group-hover:opacity-100 transition-all duration-700 pointer-events-none">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-5 h-5 rounded-full bg-secondary shadow-[0_0_15px_rgba(78,222,163,0.5)] animate-pulse"></div>
                                <span class="text-[9px] font-black uppercase tracking-widest text-secondary">{{ __('Review feedback') }}</span>
                            </div>
                            <p class="text-[11px] text-white/90 leading-relaxed italic">"{{ __('The coupling here seems a bit strong. Let\'s try to inject the logic dependency?') }}"</p>
                        </div>

                        <div class="relative glass-panel rounded-3xl overflow-hidden border border-white/10 shadow-2xl hover:border-primary/30 transition-colors z-10">
                            <div class="flex items-center gap-4 px-6 py-3 bg-white/5 border-b border-white/5">
                                <div class="flex gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded-full bg-error/40"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-tertiary/40"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-secondary/40"></div>
                                </div>
                                <div class="text-[9px] font-mono text-white/40 uppercase tracking-widest">engine_v2.ts</div>
                            </div>
                            <div class="p-8 font-mono text-sm leading-relaxed overflow-hidden relative min-h-[280px]">
                                <div class="absolute inset-0 bg-grid opacity-10"></div>
                                <div class="relative z-10 space-y-1">
                                    <p class="text-on-surface-variant"><span class="text-secondary">class</span> <span class="text-primary">ReviewEngine</span> {</p>
                                    <p class="text-on-surface-variant pl-4"><span class="text-primary-container bg-primary/10 px-1 rounded">/* @post-node */</span></p>
                                    <p class="text-on-surface-variant pl-4"><span class="text-primary">process</span>(data: Buffer) {</p>
                                    <p class="text-on-surface-variant pl-8 text-primary/60">return this.analyze(data);</p>
                                    <p class="text-on-surface-variant pl-4">}</p>
                                    <p class="text-on-surface-variant">}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Live Pulse Stats (Real Data) -->
        <div class="max-w-7xl mx-auto px-6 lg:px-12 -mt-12 relative z-20">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="glass-panel p-8 rounded-[2rem] border-white/5 shadow-2xl group hover:border-primary/20 transition-all duration-700">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[9px] font-black uppercase tracking-[0.3em] text-on-surface-variant opacity-40">{{ __('Contributors') }}</span>
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    </div>
                    <div class="text-4xl font-display font-black text-on-surface group-hover:text-primary transition-colors">{{ \App\Models\User::count() }}</div>
                </div>
                <div class="glass-panel p-8 rounded-[2rem] border-white/5 shadow-2xl group hover:border-secondary/20 transition-all duration-700">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[9px] font-black uppercase tracking-[0.3em] text-on-surface-variant opacity-40">{{ __('Architecture Posts') }}</span>
                        <div class="w-2 h-2 rounded-full bg-primary/40"></div>
                    </div>
                    <div class="text-4xl font-display font-black text-on-surface group-hover:text-secondary transition-colors">{{ \App\Models\Post::count() }}</div>
                </div>
                <div class="glass-panel p-8 rounded-[2rem] border-white/5 shadow-2xl group hover:border-tertiary/20 transition-all duration-700">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[9px] font-black uppercase tracking-[0.3em] text-on-surface-variant opacity-40">{{ __('Peer Evaluations') }}</span>
                        <div class="w-2 h-2 rounded-full bg-tertiary/40"></div>
                    </div>
                    <div class="text-4xl font-display font-black text-on-surface group-hover:text-tertiary transition-colors">{{ \App\Models\FullReview::count() }}</div>
                </div>
            </div>
        </div>

        <!-- Premium Separator -->
        <div class="max-w-7xl mx-auto px-12 py-8">
            <div class="glow-divider" x-intersect="$el.classList.add('visible')"></div>
        </div>

        <!-- Benefits Section -->
        <section id="benefits" class="py-12" x-data="{ revealed: false }" x-intersect="revealed = true">
            <div class="max-w-7xl mx-auto px-6 lg:px-12">
                <div class="text-center mb-12 transition-all duration-1000" :class="revealed ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
                    <h2 class="text-4xl lg:text-5xl font-black tracking-tighter mb-4 uppercase">{{ __('Advantages') }}</h2>
                    <p class="text-on-surface-variant italic">{{ __('Optimized for speed, privacy, and educational evolution.') }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <!-- Large Feature Card -->
                    <div class="md:col-span-8 glass-panel rounded-3xl p-8 relative overflow-hidden group hover-lift hover-glow transition-all duration-1000 delay-100"
                         :class="revealed ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
                        <div class="absolute inset-0 bg-grid opacity-5 group-hover:opacity-10 transition-opacity"></div>
                        <div class="absolute bottom-0 right-0 w-96 h-96 bg-primary/20 blur-[120px] rounded-full translate-x-1/2 translate-y-1/2 group-hover:scale-110 transition-transform duration-1000"></div>
                        
                        <h2 class="text-3xl lg:text-4xl font-bold mb-4 relative z-10">{{ __('Educational Synergy') }}</h2>
                        <p class="text-base text-on-surface-variant/80 max-w-md relative z-10 italic leading-relaxed">
                            {{ __('ReviewMe is built for students who want to master code architecture through peer evaluation and mentorship.') }}
                        </p>
                        
                        <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 gap-6 relative z-10">
                            <div class="p-6 rounded-2xl bg-white/5 border border-white/5 group-hover:border-secondary/30 transition-all hover:bg-white/[0.08]">
                                <span class="text-secondary font-black block mb-2 tracking-[0.2em] text-[9px] uppercase">{{ __('Reputation') }}</span>
                                <div class="text-xl font-bold mb-2">{{ __('Proof of Expertise') }}</div>
                                <p class="text-xs text-on-surface-variant/60 leading-relaxed">{{ __('Earn reputation for every meaningful feedback you provide.') }}</p>
                            </div>
                            <div class="p-6 rounded-2xl bg-white/5 border border-white/5 group-hover:border-tertiary/30 transition-all hover:bg-white/[0.08]">
                                <span class="text-tertiary font-black block mb-2 tracking-[0.2em] text-[9px] uppercase">{{ __('Collaboration') }}</span>
                                <div class="text-xl font-bold mb-2">{{ __('Threaded Feedbacks') }}</div>
                                <p class="text-xs text-on-surface-variant/60 leading-relaxed">{{ __('Discuss code nuances in rich, organized conversation threads.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Side Card 1 -->
                    <div class="md:col-span-4 glass-panel rounded-3xl p-8 relative overflow-hidden group hover-lift hover-glow border-primary/20 transition-all duration-1000 delay-200"
                         :class="revealed ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
                        <div class="absolute inset-0 bg-grid opacity-10 pointer-events-none"></div>
                        <div class="text-4xl text-white/5 group-hover:text-primary/40 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-700">
                            <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.205.084 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.22 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        </div>
                        <div class="mt-6">
                            <h3 class="text-2xl font-bold mb-2 tracking-tight">{{ __('Instant Identity') }}</h3>
                            <p class="text-on-surface-variant text-sm italic leading-relaxed">{{ __('One-click GitHub entry. No forms. No barriers. Just code.') }}</p>
                        </div>
                    </div>

                    <!-- Side Card 2 -->
                    <div class="md:col-span-4 bg-primary/10 border border-primary/20 backdrop-blur-xl rounded-3xl p-8 relative overflow-hidden group hover-lift transition-all duration-1000 delay-300"
                         :class="revealed ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
                        <div class="absolute inset-0 bg-grid-interactive opacity-20"></div>
                        <div class="flex flex-col h-full justify-between relative z-10">
                            <div class="w-12 h-12 rounded-xl bg-primary/20 flex items-center justify-center border border-primary/30 group-hover:rotate-6 transition-transform">
                                <svg class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <div class="mt-6">
                                <h3 class="text-2xl font-black uppercase text-white tracking-tighter">{{ __('Private Groups') }}</h3>
                                <p class="text-white/60 text-[13px] mt-2 italic font-medium leading-relaxed">{{ __('Need a safe space? Create groups for your class or team.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Large Feature Card 2 -->
                    <div class="md:col-span-8 glass-panel rounded-3xl p-8 overflow-hidden relative group hover-lift hover-glow border-tertiary/20 transition-all duration-1000 delay-400"
                         :class="revealed ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
                        <div class="flex flex-col md:flex-row gap-10 h-full items-center">
                            <div class="flex-1">
                                <h3 class="text-3xl lg:text-4xl font-bold mb-4 tracking-tight">{{ __('Oversight') }}</h3>
                                <p class="text-on-surface-variant/80 italic mb-6 leading-relaxed">
                                    {{ __('Designated reviewers guide the conversation, ensuring high standards.') }}
                                </p>
                            </div>
                            <div class="flex-1 w-full bg-white/5 rounded-2xl border border-white/5 p-6 relative overflow-hidden flex flex-col justify-center items-center">
                                <div class="absolute inset-0 bg-grid opacity-10"></div>
                                <div class="space-y-3 w-full">
                                     <div class="w-full h-12 bg-surface-container-highest rounded-xl border border-white/10 flex items-center px-4 gap-4 hover:border-tertiary/40 transition-colors group/row">
                                         <div class="w-5 h-5 rounded-full bg-tertiary shadow-[0_0_30px_rgba(255,185,95,0.4)] group-hover/row:scale-110 transition-transform"></div>
                                         <div class="h-1.5 w-20 bg-white/10 rounded"></div>
                                         <span class="ml-auto text-[8px] font-black text-tertiary uppercase tracking-widest">{{ __('Supervisor') }}</span>
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Premium Separator -->
        <div class="max-w-7xl mx-auto px-12 py-8">
            <div class="glow-divider" x-intersect="$el.classList.add('visible')"></div>
        </div>

        <!-- Workflow Section -->
        <section id="how-it-works" class="relative py-12 bg-surface-container-lowest/30 overflow-hidden" 
                 x-data="{ revealed: false }" x-intersect="revealed = true">
            <div class="absolute inset-0 bg-grid opacity-5"></div>
            <div class="max-w-7xl mx-auto px-6 lg:px-12 relative z-10">
                <div class="text-center mb-12 transition-all duration-1000" :class="revealed ? 'opacity-100 scale-100' : 'opacity-0 scale-95'">
                    <h2 class="text-5xl lg:text-6xl font-black tracking-tighter mb-4">{{ __('THE WORKFLOW') }}</h2>
                    <p class="text-on-surface-variant italic text-lg">{{ __('From code to quality in three steps.') }}</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 lg:gap-20">
                    <!-- Steps -->
                    <div class="relative group transition-all duration-1000 delay-100" :class="revealed ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-12'">
                        <div class="text-[80px] font-black text-white/5 absolute -top-12 -left-4 group-hover:text-primary/10 transition-all duration-700">01</div>
                        <h4 class="text-2xl font-bold mb-3 relative z-10">{{ __('Upload') }}</h4>
                        <p class="text-on-surface-variant text-sm italic relative z-10 opacity-70 group-hover:opacity-100 transition-opacity">
                            {{ __('Paste your code fragments or drag & drop files. Add context.') }}
                        </p>
                    </div>
                    <div class="relative group transition-all duration-1000 delay-200" :class="revealed ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'">
                        <div class="text-[80px] font-black text-white/5 absolute -top-12 -left-4 group-hover:text-secondary/10 transition-all duration-700">02</div>
                        <h4 class="text-2xl font-bold mb-3 relative z-10">{{ __('Engage') }}</h4>
                        <p class="text-on-surface-variant text-sm italic relative z-10 opacity-70 group-hover:opacity-100 transition-opacity">
                            {{ __('Your peers and mentors react in real-time. Use the Lens system.') }}
                        </p>
                    </div>
                    <div class="relative group transition-all duration-1000 delay-300" :class="revealed ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-12'">
                        <div class="text-[80px] font-black text-white/5 absolute -top-12 -left-4 group-hover:text-tertiary/10 transition-all duration-700">03</div>
                        <h4 class="text-2xl font-bold mb-3 relative z-10">{{ __('Evolve') }}</h4>
                        <p class="text-on-surface-variant text-sm italic relative z-10 opacity-70 group-hover:opacity-100 transition-opacity">
                            {{ __('Iterate based on feedback. Improve your code with community feedback.') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Premium Separator -->
        <div class="max-w-7xl mx-auto px-12 py-8">
            <div class="glow-divider" x-intersect="$el.classList.add('visible')"></div>
        </div>

        <!-- Final CTA -->
        <section class="py-16 relative overflow-hidden" x-data="{ revealed: false }" x-intersect="revealed = true">
            <div class="absolute inset-0 bg-grid-interactive opacity-10"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-primary/5 to-transparent"></div>
            <div class="max-w-4xl mx-auto text-center px-6 relative z-10 transition-all duration-1000" :class="revealed ? 'opacity-100 scale-100' : 'opacity-0 scale-90'">
                <h2 class="text-4xl lg:text-5xl font-black mb-6 tracking-tighter uppercase">{{ __('Join us.') }}</h2>
                <p class="text-lg text-on-surface-variant mb-10 italic font-medium">
                    {{ __('Join the best community of developers.') }}
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-8">
                    <x-ui.button variant="primary" size="lg" shadow="shadow-[0_0_80px_rgba(190,194,255,0.4)]" href="{{ route('register') }}" class="px-10 py-5 text-lg group overflow-hidden relative">
                        <span class="relative z-10 group-hover:scale-110 transition-transform inline-block">{{ __('Join us') }}</span>
                    </x-ui.button>
                    <a href="mailto:contact@reviewme.dev" class="text-[10px] font-black uppercase tracking-[0.4em] text-on-surface-variant hover:text-white transition-all border-b-2 border-white/5 pb-1 hover:border-primary">
                        {{ __('Contact us') }}
                    </a>
                </div>
            </div>
        </section>
        
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.dispatchEvent(new CustomEvent('intersect'));
                        }
                    });
                }, { threshold: 0.1 });

                document.querySelectorAll('[x-intersect]').forEach(el => {
                    observer.observe(el);
                });
            });
        </script>
    </div>
</x-guest-layout>
