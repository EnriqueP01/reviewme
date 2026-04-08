<x-guest-layout>
    <div class="w-full max-w-lg animate-fade-in-up" style="animation-delay: 0.1s">
        <div class="glass-panel p-10 rounded-3xl shadow-2xl relative overflow-hidden group">
            <!-- Internal Glow -->
            <div class="absolute -inset-px bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

            <div class="mb-10 text-center relative z-10">
                <h1 class="text-3xl font-bold mb-2">Platform Access</h1>
                <p class="text-on-surface-variant text-sm italic">Initialize your curator profile to begin evaluation.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6 relative z-10">
                @csrf

                <!-- Name -->
                <div class="space-y-2">
                    <x-input-label for="name" :value="__('Full Identity')" class="text-xs uppercase tracking-widest opacity-60 ml-1" />
                    <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Johnathan Curator" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="space-y-2">
                    <x-input-label for="email" :value="__('Primary Artifact (Email)')" class="text-xs uppercase tracking-widest opacity-60 ml-1" />
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="curator@reviewme.io" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <x-input-label for="password" :value="__('New Secret Key')" class="text-xs uppercase tracking-widest opacity-60 ml-1" />
                    <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <x-input-label for="password_confirmation" :value="__('Verify Secret Key')" class="text-xs uppercase tracking-widest opacity-60 ml-1" />
                    <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="pt-4">
                    <x-ui.button variant="primary" class="w-full py-4 tracking-widest uppercase text-xs">
                        Request Membership
                    </x-ui.button>
                </div>

                <div class="text-center pt-6 border-t border-outline-variant/30 mt-8">
                    <span class="text-on-surface-variant text-sm italic">Already authorized?</span>
                    <a href="{{ route('login') }}" class="text-primary hover:primary-glow text-sm font-bold ml-2 transition-all">Sign In</a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
