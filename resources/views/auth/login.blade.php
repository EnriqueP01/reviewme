<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-display text-2xl font-bold text-on-surface">Welcome Back</h1>
        <p class="text-on-surface-variant text-sm mt-1">Continue your journey as a curator.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="curator@reviewme.io" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded-sm bg-surface-high border-none text-primary focus:ring-offset-0 focus:ring-primary/50 transition-all cursor-pointer" name="remember">
                <span class="ms-2 text-sm text-on-surface-variant group-hover:text-on-surface transition-colors">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-on-surface-variant hover:text-primary transition-colors font-medium" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2 space-y-3">
            <x-ui.button variant="primary" class="w-full">
                {{ __('Access Platform') }}
            </x-ui.button>
            
            <div class="relative flex items-center py-2">
                <div class="flex-grow border-t border-outline-variant/30"></div>
                <span class="flex-shrink mx-4 text-xs font-bold text-on-surface-variant uppercase tracking-widest">Or continue with</span>
                <div class="flex-grow border-t border-outline-variant/30"></div>
            </div>

            <a href="{{ route('login.github') }}" class="flex items-center justify-center gap-3 w-full py-2.5 px-4 bg-surface-container-high hover:bg-surface-container-highest border border-outline-variant rounded-xl text-on-surface font-bold text-sm transition-all shadow-sm group">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.041-1.412-4.041-1.412-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                GitHub Account
            </a>
        </div>
        
        <div class="text-center pt-4">
            <span class="text-on-surface-variant text-sm">New here?</span>
            <a href="{{ route('register') }}" class="text-primary hover:text-primary/80 text-sm font-bold ml-1 transition-colors">Apply for membership</a>
        </div>
    </form>
</x-guest-layout>
