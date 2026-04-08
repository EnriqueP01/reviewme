<x-guest-layout>
    <div class="w-full max-w-md animate-fade-in-up" style="animation-delay: 0.1s">
        <div class="glass-panel p-10 rounded-3xl shadow-2xl relative overflow-hidden group">
            <!-- Internal Glow -->
            <div class="absolute -inset-px bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

            <div class="mb-10 text-center relative z-10">
                <h1 class="text-3xl font-bold mb-2">{{ __('Welcome Back') }}</h1>
                <p class="text-on-surface-variant text-sm italic">{{ __('Identify yourself to continue the curation.') }}</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6" :status="session('status')" />

            @if (session('error'))
                <div class="mb-6 p-4 bg-error/10 border border-error/20 rounded-xl text-error text-sm font-medium flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="space-y-6 relative z-10">
                <!-- GitHub OAuth -->
                <a href="{{ route('login.github') }}" class="flex items-center justify-center gap-4 w-full py-5 px-6 bg-white/[0.03] hover:bg-white/[0.06] border border-white/10 rounded-2xl text-on-surface font-black text-xs uppercase tracking-widest transition-all shadow-xl hover:shadow-primary/5 group">
                    <svg class="w-6 h-6 fill-current text-primary group-hover:scale-110 transition-transform" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.041-1.412-4.041-1.412-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    {{ __('Continue with GitHub') }}
                </a>

                <div class="relative flex items-center py-4">
                    <div class="flex-grow border-t border-white/5"></div>
                    <span class="flex-shrink mx-4 text-[8px] font-black text-on-surface-variant uppercase tracking-[0.3em] opacity-30">{{ __('Standard Access') }}</span>
                    <div class="flex-grow border-t border-white/5"></div>
                </div>

                <!-- Legacy Login Form (Collapsible) -->
                <div x-data="{ showForm: false }">
                    <button @click="showForm = !showForm" class="w-full text-center text-[10px] text-on-surface-variant hover:text-primary transition-colors font-black uppercase tracking-widest">
                        {{ __('Use credential passkey') }}
                    </button>

                    <form x-show="showForm" x-transition method="POST" action="{{ route('login') }}" class="mt-8 space-y-6 pt-8 border-t border-white/5">
                        @csrf
                        <div class="space-y-4">
                            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="{{ __('Curation Handle') }}" />
                            <x-text-input id="password" type="password" name="password" required placeholder="{{ __('Secret Key') }}" />
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                                <input id="remember_me" type="checkbox" class="rounded bg-white/5 border-white/10 text-primary focus:ring-primary/20" name="remember">
                                <span class="ms-2 text-xs text-on-surface-variant group-hover:text-on-surface transition-colors">{{ __('Remember') }}</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a class="text-[10px] uppercase tracking-widest text-primary/60 hover:text-primary transition-colors font-black" href="{{ route('password.request') }}">
                                    {{ __('Lost Access?') }}
                                </a>
                            @endif
                        </div>

                        <x-ui.button type="submit" variant="primary" class="w-full py-4 uppercase text-[10px] tracking-[0.2em]">
                            {{ __('Unlock Workspace') }}
                        </x-ui.button>
                    </form>
                </div>
                
                <div class="text-center pt-8 border-t border-white/5 mt-8">
                    <span class="text-on-surface-variant text-xs italic">{{ __('New curator?') }}</span>
                    <a href="{{ route('register') }}" class="text-primary hover:primary-glow text-xs font-black ml-2 transition-all uppercase tracking-widest">{{ __('Apply Now') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
