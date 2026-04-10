<x-guest-layout>
    <div class="w-full max-w-md animate-fade-in-up" style="animation-delay: 0.1s">
        <div class="glass-panel p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
            <!-- Internal Glow -->
            <div class="absolute -inset-px bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

            <div class="mb-10 text-center relative z-10">
                <div class="w-16 h-16 rounded-2xl bg-secondary/10 flex items-center justify-center mx-auto mb-6 text-secondary shadow-[0_0_30_rgba(78,222,163,0.1)]">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-display font-black text-on-surface tracking-tight mb-4 uppercase">{{ __('Reset Password') }}</h1>
                <p class="text-on-surface-variant text-sm italic">
                    {{ __('Define your new password below.') }}
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-6 relative z-10">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="text-[9px] font-black uppercase tracking-[0.3em] text-on-surface-variant opacity-40 ml-1">
                        {{ __('Email') }}
                    </label>
                    <x-text-input id="email" class="block w-full opacity-50 select-none pointer-events-none" type="email" name="email" :value="old('email', $request->email)" required readonly />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="text-[9px] font-black uppercase tracking-[0.3em] text-on-surface-variant opacity-40 ml-1">
                        {{ __('New Password') }}
                    </label>
                    <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="text-[9px] font-black uppercase tracking-[0.3em] text-on-surface-variant opacity-40 ml-1">
                        {{ __('Confirm Password') }}
                    </label>
                    <x-text-input id="password_confirmation" class="block w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="pt-4">
                    <x-ui.button type="submit" variant="primary" class="w-full py-4 tracking-[0.3em] uppercase text-[10px] font-black transition-all hover:scale-[1.02] active:scale-[0.98]">
                        {{ __('Update Password') }}
                    </x-ui.button>
                </div>
            </form>

            <div class="mt-12 pt-8 border-t border-white/5 text-center">
                <p class="text-[9px] font-black uppercase tracking-[0.4em] text-on-surface-variant opacity-10">
                    {{ __('Secure Update In Progress') }}
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
