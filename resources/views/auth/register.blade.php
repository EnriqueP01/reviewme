<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-display text-2xl font-bold text-on-surface">Apply for Membership</h1>
        <p class="text-on-surface-variant text-sm mt-1">Join the elite circle of code curators.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="curator@reviewme.io" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="pt-2">
            <x-ui.button variant="primary" class="w-full">
                {{ __('Submit Application') }}
            </x-ui.button>
        </div>

        <div class="text-center pt-4">
            <span class="text-on-surface-variant text-sm">Already a member?</span>
            <a href="{{ route('login') }}" class="text-primary hover:text-primary/80 text-sm font-bold ml-1 transition-colors">Sign in here</a>
        </div>
    </form>
</x-guest-layout>
