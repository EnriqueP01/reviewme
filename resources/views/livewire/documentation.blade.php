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
                    <a href="#versioning" class="block px-4 py-2 text-xs font-bold text-on-surface-variant/60 hover:text-on-surface hover:bg-white/5 rounded-xl transition-all">{{ __('Versioning') }}</a>
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
                        {{ __('ReviewMe is more than a code sharing tool; it\'s a dedicated environment for architectural validation. Designed for developer teams and educational institutions, it focuses on the "Why" and "How" rather than just the "What".') }}
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                        <div class="p-6 rounded-3xl bg-white/[0.02] border border-white/5 space-y-3 hover:bg-white/5 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center text-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <h4 class="font-bold text-on-surface">{{ __('Secure Audits') }}</h4>
                            <p class="text-[11px] leading-relaxed opacity-60">{{ __('Private Labs ensure your experimental logic stays within your team.') }}</p>
                        </div>
                        <div class="p-6 rounded-3xl bg-white/[0.02] border border-white/5 space-y-3 hover:bg-white/5 transition-colors">
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
                <h3 class="text-2xl font-black tracking-tighter uppercase text-secondary">{{ __('Deployment') }}</h3>
                <div class="space-y-4">
                    <p class="text-sm text-on-surface-variant italic">{{ __('Setting up ReviewMe in your environment is straightforward.') }}</p>
                    <div class="rounded-2xl bg-black/40 border border-white/5 p-6 font-mono text-xs text-secondary/80 leading-relaxed overflow-hidden relative group">
                        <p><span class="text-primary/60">#</span> {{ __('Spin up the infrastructure') }}</p>
                        <p>git clone https://github.com/EnriqueP01/reviewme.git</p>
                        <p>docker-compose up -d --build</p>
                        <p class="mt-4 text-white/20 italic">{{ __('# Note: Requires Docker and Docker Compose.') }}</p>
                    </div>
                </div>
            </section>

            <!-- Authentication -->
            <section id="authentication" class="space-y-6">
                <h3 class="text-2xl font-black tracking-tighter uppercase text-primary">{{ __('Secure Identity') }}</h3>
                <div class="prose prose-invert max-w-none">
                    <p class="text-sm text-on-surface-variant/80 italic">
                        {{ __('ReviewMe uses GitHub as the primary identity provider. This links your professional reputation to your platform activity.') }}
                    </p>
                    <ul class="text-xs space-y-2 mt-4 text-on-surface-variant/60">
                        <li>{{ __('● Single Sign-On (SSO) via GitHub OAuth.') }}</li>
                        <li>{{ __('● Automatic profile synchronization (Avatar, Username).') }}</li>
                        <li>{{ __('● Private repository integration for authorized teams.') }}</li>
                    </ul>
                </div>
            </section>

            <!-- Labs & Groups -->
            <section id="labs" class="space-y-8">
                <div class="space-y-4">
                    <h3 class="text-2xl font-black tracking-tighter uppercase text-secondary">{{ __('Collaborative Labs') }}</h3>
                    <p class="text-sm text-on-surface-variant/80 italic">
                        {{ __('Groups are the heart of ReviewMe. Create private spaces for your team or students.') }}
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-6 rounded-2xl bg-white/[0.01] border border-white/5">
                        <div class="text-[10px] font-black text-primary mb-2 uppercase">{{ __('Private Labs') }}</div>
                        <p class="text-[11px] opacity-40 leading-relaxed">{{ __('Control who can view and review your blueprints.') }}</p>
                    </div>
                    <div class="p-6 rounded-2xl bg-white/[0.01] border border-white/5">
                        <div class="text-[10px] font-black text-secondary mb-2 uppercase">{{ __('Group Chat') }}</div>
                        <p class="text-[11px] opacity-40 leading-relaxed">{{ __('Discuss architecture in real-time with integrated messaging.') }}</p>
                    </div>
                    <div class="p-6 rounded-2xl bg-white/[0.01] border border-white/5">
                        <div class="text-[10px] font-black text-on-surface mb-2 uppercase">{{ __('Administration') }}</div>
                        <p class="text-[11px] opacity-40 leading-relaxed">{{ __('Manage roles, members, and curate the best shared content.') }}</p>
                    </div>
                </div>
            </section>

            <!-- Curation Workflow -->
            <section id="curation" class="space-y-6">
                <h3 class="text-2xl font-black tracking-tighter uppercase text-primary">{{ __('The Curation Factory') }}</h3>
                <div class="prose prose-invert max-w-none space-y-4">
                    <p class="text-sm text-on-surface-variant italic">
                        {{ __('Publishing a post is more than just a code dump. It\'s an invitation for architectural analysis.') }}
                    </p>
                    <div class="space-y-6 border-l-2 border-primary/20 pl-6 mt-8">
                        <div>
                            <h4 class="text-sm font-bold text-on-surface">{{ __('1. Fragment Collection') }}</h4>
                            <p class="text-[11px] opacity-60">{{ __('Gather multiple files (Snippets) that form a logical unit.') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-on-surface">{{ __('2. Contextual Tuning') }}</h4>
                            <p class="text-[11px] opacity-60">{{ __('Add review goals, technical challenges, and reorder files for logical reading.') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-on-surface">{{ __('3. Architectural Validation') }}</h4>
                            <p class="text-[11px] opacity-60">{{ __('Wait for the community or your team to analyze and react.') }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Versioning -->
            <section id="versioning" class="space-y-6">
                <h3 class="text-2xl font-black tracking-tighter uppercase text-secondary">{{ __('Code Iterations') }}</h3>
                <div class="flex flex-col md:flex-row gap-8 items-center bg-white/[0.01] p-8 rounded-3xl border border-white/5">
                    <div class="flex-1 space-y-4">
                        <p class="text-sm text-on-surface-variant italic">
                            {{ __('Good code is never finished. Use our versioning system to evolve based on feedback.') }}
                        </p>
                        <p class="text-[11px] leading-relaxed opacity-40">
                            {{ __('Authors can deploy updated versions of their posts. Reviewers can easily switch between iterations to see improvements and track the evolution of the logic.') }}
                        </p>
                    </div>
                    <div class="w-full md:w-48 p-4 rounded-2xl bg-black/20 border border-white/5 text-center">
                        <div class="text-[9px] font-black text-primary uppercase tracking-widest mb-1">{{ __('Current Flow') }}</div>
                        <div class="text-lg font-black tracking-tighter">V1 → V2 → V3</div>
                    </div>
                </div>
            </section>

            <!-- Karma System -->
            <section id="karma" class="space-y-6">
                <h3 class="text-2xl font-black tracking-tighter uppercase text-on-surface">{{ __('Karma & Reputation') }}</h3>
                <div class="glass-panel p-8 rounded-round-4 border border-white/5 space-y-6">
                    <p class="text-sm text-on-surface-variant italic leading-relaxed">
                        {{ __('Your contribution to the platform is quantified through Karma. It represents your influence and expertise within the ecosystem.') }}
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-primary/5 border border-primary/10">
                            <h5 class="text-[10px] font-black uppercase text-primary mb-1">{{ __('Earn Karma') }}</h5>
                            <p class="text-[11px] opacity-50">{{ __('Get upvoted for helpful reviews, shared insights, and valid implementations.') }}</p>
                        </div>
                        <div class="p-4 rounded-xl bg-secondary/5 border border-secondary/10">
                            <h5 class="text-[10px] font-black uppercase text-secondary mb-1">{{ __('Rise in Rank') }}</h5>
                            <p class="text-[11px] opacity-50">{{ __('climb the Global Leaderboard and establish yourself as an Architectural Expert.') }}</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>
