<x-app-layout>
    <div class="py-24 max-w-5xl mx-auto px-12 stagger-in">
        <!-- Header Section -->
        <div class="flex flex-col items-center text-center mb-24">
            <x-ui.logo-artifact />
            <div class="mt-12 space-y-4">
                <h1 class="text-5xl font-display font-black tracking-tight text-on-surface">Curator Configuration</h1>
                <p class="text-on-surface-variant text-lg max-w-2xl font-editorial opacity-60">Fine-tune your neural presence and security protocols within the ReviewMe collective.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
            <!-- Left Sidebar: Info -->
            <div class="lg:col-span-4 space-y-8">
                <div class="glass-panel p-8 rounded-[2.5rem] border border-white/5 space-y-10">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-2 h-8 bg-primary rounded-full"></div>
                            <h2 class="text-xl font-display font-black uppercase tracking-widest text-on-surface">Protocols</h2>
                        </div>
                        <p class="text-sm text-on-surface-variant leading-relaxed opacity-60">All changes to your profile are cryptographically signed and synced across the artifact network.</p>
                    </div>

                    <nav class="space-y-2">
                        @foreach(['Identity & Essence', 'Security Protocol', 'Terminal Action'] as $index => $label)
                            <div class="flex items-center gap-4 p-4 rounded-2xl hover:bg-white/5 transition-all cursor-pointer group">
                                <span class="text-[10px] font-black text-primary/40 font-mono">0{{ $index + 1 }}</span>
                                <span class="text-sm font-bold text-on-surface-variant group-hover:text-primary transition-colors">{{ $label }}</span>
                            </div>
                        @endforeach
                    </nav>
                </div>

                <div class="p-8 rounded-[2.5rem] bg-primary/5 border border-primary/10">
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-primary">System Notice</span>
                    <p class="mt-4 text-sm text-on-surface-variant leading-relaxed italic">"Your reputation is the only artifact that persists beyond the terminal."</p>
                </div>
            </div>

            <!-- Right Content: Forms -->
            <div class="lg:col-span-8 space-y-12">
                <!-- Profile Info -->
                <section id="identity" class="glass-panel p-12 rounded-[3rem] border border-white/5 relative overflow-hidden group/card shadow-2xl">
                     <div class="absolute top-0 right-0 p-8">
                        <svg class="w-12 h-12 text-primary opacity-[0.03] group-hover/card:opacity-[0.08] transition-opacity" fill="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="max-w-2xl relative z-10">
                        <div class="mb-12">
                            <h3 class="font-display font-black text-3xl text-on-surface tracking-tighter">Identity & Essence</h3>
                            <div class="h-1 w-20 bg-primary mt-4 rounded-full"></div>
                        </div>
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </section>

                <!-- Password -->
                <section id="security" class="glass-panel p-12 rounded-[3rem] border border-white/5 relative overflow-hidden group/card shadow-2xl">
                    <div class="absolute top-0 right-0 p-8">
                        <svg class="w-12 h-12 text-secondary opacity-[0.03] group-hover/card:opacity-[0.08] transition-opacity" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    </div>
                    <div class="max-w-2xl relative z-10">
                        <div class="mb-12">
                            <h3 class="font-display font-black text-3xl text-on-surface tracking-tighter">Security Protocol</h3>
                            <div class="h-1 w-20 bg-secondary mt-4 rounded-full"></div>
                        </div>
                        @include('profile.partials.update-password-form')
                    </div>
                </section>

                <!-- Danger Zone -->
                <section id="danger" class="p-12 rounded-[3rem] border border-error/20 bg-error/[0.02] relative overflow-hidden group/card">
                    <div class="max-w-2xl relative z-10">
                        <div class="mb-12">
                            <h3 class="font-display font-black text-3xl text-error tracking-tighter">Terminal Action</h3>
                            <div class="h-1 w-20 bg-error mt-4 rounded-full"></div>
                        </div>
                        @include('profile.partials.delete-user-form')
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
