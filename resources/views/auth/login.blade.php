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

        <div class="pt-2">
            <x-ui.button variant="primary" class="w-full">
                {{ __('Access Platform') }}
            </x-ui.button>
        </div>
        
        <div class="text-center pt-4">
            <span class="text-on-surface-variant text-sm">New here?</span>
            <a href="{{ route('register') }}" class="text-primary hover:text-primary/80 text-sm font-bold ml-1 transition-colors">Apply for membership</a>
        </div>
    </form>
</x-guest-layout>
