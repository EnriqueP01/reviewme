<x-app-layout>
    <div class="py-10 max-w-7xl mx-auto px-8 lg:px-12 stagger-in">
        <div class="mb-8">
            <x-ui.back-button fallback="{{ route('dashboard') }}" />
        </div>
        <div class="flex flex-col lg:flex-row items-start gap-12">
            
            <!-- Sidebar header: Compact & Aligned -->
            <div class="lg:w-1/3 lg:sticky lg:top-32 space-y-8">
                <div>
                    <h1 class="text-4xl font-display font-black tracking-tighter text-on-surface uppercase">{{ __('Settings') }}</h1>
                    <div class="h-1 w-12 bg-primary mt-4"></div>
                    <p class="mt-6 text-sm text-on-surface-variant font-editorial opacity-60 leading-relaxed max-w-xs">
                        {{ __('Update your profile and security settings.') }}
                    </p>
                </div>

                <div class="glass-panel p-6 rounded-3xl border border-white/5 space-y-6">
                    <nav class="space-y-1">
                        @foreach(['Profile' => '#identity', 'Security' => '#security', 'Danger Zone' => '#danger'] as $label => $id)
                            <a href="{{ $id }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-white/5 transition-all group">
                                <span class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant group-hover:text-primary">{{ __($label) }}</span>
                                <svg class="w-4 h-4 text-primary/20 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endforeach
                    </nav>
                </div>

                <div class="p-6 rounded-3xl bg-primary/5 border border-primary/10">
                    <span class="text-[10px] font-black uppercase tracking-widest text-primary">{{ __('Info') }}</span>
                    <p class="mt-2 text-xs text-on-surface-variant leading-relaxed italic opacity-70">{{ __('Settings correctly updated.') }}</p>
                </div>
            </div>

            <!-- Main forms section -->
            <div class="lg:w-2/3 space-y-8">
                <!-- Profile Info -->
                <section id="identity" class="glass-panel p-10 rounded-[2.5rem] border border-white/5 relative overflow-hidden group/card shadow-2xl">
                    <div class="absolute -top-4 -right-4 p-8">
                        <svg class="w-24 h-24 text-primary opacity-[0.03] group-hover/card:opacity-[0.05] transition-opacity" fill="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="relative z-10">
                        <div class="mb-10">
                            <h3 class="font-display font-black text-2xl text-on-surface tracking-tighter uppercase">{{ __('Profile Information') }}</h3>
                            <div class="h-0.5 w-12 bg-primary mt-2"></div>
                        </div>
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </section>

                <!-- Password -->
                <section id="security" class="glass-panel p-10 rounded-[2.5rem] border border-white/5 relative overflow-hidden group/card shadow-2xl">
                    <div class="absolute -top-4 -right-4 p-8">
                        <svg class="w-24 h-24 text-secondary opacity-[0.03] group-hover/card:opacity-[0.05] transition-opacity" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    </div>
                    <div class="relative z-10">
                        <div class="mb-10">
                            <h3 class="font-display font-black text-2xl text-on-surface tracking-tighter uppercase">{{ __('Update Password') }}</h3>
                            <div class="h-0.5 w-12 bg-secondary mt-2"></div>
                        </div>
                        @include('profile.partials.update-password-form')
                    </div>
                </section>

                <!-- Danger Zone -->
                <section id="danger" class="p-10 rounded-[2.5rem] border border-error/20 bg-error/[0.02] relative group/card">
                    <div class="relative z-10">
                        <div class="mb-10">
                            <h3 class="font-display font-black text-2xl text-error tracking-tighter uppercase">{{ __('Delete Account') }}</h3>
                            <div class="h-0.5 w-12 bg-error mt-2"></div>
                        </div>
                        @include('profile.partials.delete-user-form')
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
