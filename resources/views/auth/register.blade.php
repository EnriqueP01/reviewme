<x-guest-layout>
    <div class="w-full max-w-lg animate-fade-in-up" style="animation-delay: 0.1s">
        <div class="glass-panel p-10 rounded-3xl shadow-2xl relative overflow-hidden group">
            <!-- Internal Glow -->
            <div class="absolute -inset-px bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

            <div class="mb-10 text-center relative z-10">
                <h1 class="text-3xl font-bold mb-2">{{ __('Create Account') }}</h1>
                <p class="text-on-surface-variant text-sm italic">{{ __('Sign up to start reviewing code.') }}</p>
            </div>

            <div class="space-y-6 relative z-10">
                <!-- GitHub OAuth Registration -->
                <a href="{{ route('login.github') }}" class="flex items-center justify-center gap-4 w-full py-3.5 px-6 bg-white/[0.03] hover:bg-white/[0.06] border border-white/10 rounded-2xl text-on-surface font-black text-xs uppercase tracking-widest transition-all shadow-xl hover:shadow-primary/5 group">
                    <svg class="w-6 h-6 fill-current text-primary group-hover:scale-110 transition-transform" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.041-1.412-4.041-1.412-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    {{ __('Register with GitHub') }}
                </a>

                <div class="relative flex items-center py-4">
                    <div class="flex-grow border-t border-white/5"></div>
                    <span class="flex-shrink mx-4 text-[8px] font-black text-on-surface-variant uppercase tracking-[0.3em] opacity-30">{{ __('Email registration') }}</span>
                    <div class="flex-grow border-t border-white/5"></div>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div class="space-y-2">
                        <x-input-label for="name" :value="__('Full Name')" class="text-xs uppercase tracking-widest opacity-60 ml-1" />
                        <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Johnathan Curator" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="space-y-2">
                        <x-input-label for="email" :value="__('Email Address')" class="text-xs uppercase tracking-widest opacity-60 ml-1" />
                        <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="curator@reviewme.io" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <x-input-label for="password" :value="__('Password')" class="text-xs uppercase tracking-widest opacity-60 ml-1" />
                        <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs uppercase tracking-widest opacity-60 ml-1" />
                        <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="pt-4">
                        <x-ui.button type="submit" variant="primary" class="w-full py-4 tracking-widest uppercase text-xs">
                            {{ __('Sign Up') }}
                        </x-ui.button>
                    </div>

                    <div class="text-center pt-8 border-t border-white/5 mt-8">
                        <span class="text-on-surface-variant text-xs italic">{{ __('Already have an account?') }}</span>
                        <a href="{{ route('login') }}" class="text-primary hover:primary-glow text-xs font-black ml-2 transition-all uppercase tracking-widest">{{ __('Sign In') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
