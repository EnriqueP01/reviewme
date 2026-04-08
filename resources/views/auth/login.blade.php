<x-guest-layout>
    <div class="w-full max-w-md animate-fade-in-up" style="animation-delay: 0.1s">
        <div class="glass-panel p-10 rounded-3xl shadow-2xl relative overflow-hidden group">
            <!-- Internal Glow -->
            <div class="absolute -inset-px bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

            <div class="mb-10 text-center relative z-10">
                <h1 class="text-3xl font-bold mb-2">Welcome Back</h1>
                <p class="text-on-surface-variant text-sm italic">Identify yourself to continue the curation.</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6 relative z-10">
                @csrf

                <!-- Email Address -->
                <div class="space-y-2">
                    <x-input-label for="email" :value="__('Curation Handle')" class="text-xs uppercase tracking-widest opacity-60 ml-1" />
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="curator@reviewme.io" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between ml-1">
                        <x-input-label for="password" :value="__('Secret Key')" class="text-xs uppercase tracking-widest opacity-60" />
                        @if (Route::has('password.request'))
                            <a class="text-[10px] uppercase tracking-tighter text-on-surface-variant hover:text-primary transition-colors font-bold" href="{{ route('password.request') }}">
                                Lost Access?
                            </a>
                        @endif
                    </div>
                    <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center group cursor-pointer ml-1">
                    <input id="remember_me" type="checkbox" class="w-4 h-4 rounded-sm bg-surface-container-highest border-none text-primary focus:ring-offset-0 focus:ring-primary/20 transition-all cursor-pointer" name="remember">
                    <span class="ms-3 text-sm text-on-surface-variant group-hover:text-on-surface transition-colors">Keep me signed in</span>
                </div>

                <div class="pt-4">
                    <x-ui.button variant="primary" class="w-full py-4 tracking-widest uppercase text-xs">
                        Unlock Workspace
                    </x-ui.button>
                </div>
                
                <div class="text-center pt-6 border-t border-outline-variant/30 mt-8">
                    <span class="text-on-surface-variant text-sm italic">New curator?</span>
                    <a href="{{ route('register') }}" class="text-primary hover:primary-glow text-sm font-bold ml-2 transition-all">Apply Now</a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
