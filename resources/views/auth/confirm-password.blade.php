<x-guest-layout>
    <div class="w-full max-w-md animate-fade-in-up" style="animation-delay: 0.1s">
        <div class="glass-panel p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
            <!-- Internal Glow -->
            <div class="absolute -inset-px bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

            <div class="mb-10 text-center relative z-10">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-6 text-primary shadow-[0_0_30px_rgba(190,194,255,0.1)]">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-display font-black text-on-surface tracking-tight mb-4 uppercase">{{ __('Confirm Password') }}</h1>
                <p class="text-on-surface-variant text-sm italic px-4">
                    {{ __('This is a secure area. Please confirm your password before continuing.') }}
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-8 relative z-10">
                @csrf

                <!-- Password -->
                <div class="space-y-3">
                    <label for="password" class="text-[9px] font-black uppercase tracking-[0.3em] text-on-surface-variant opacity-40 ml-1">
                        {{ __('Password') }}
                    </label>
                    <x-text-input 
                        id="password" 
                        class="block w-full" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password" 
                        autofocus
                        placeholder="••••••••"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="pt-4 flex flex-col gap-6">
                    <x-ui.button type="submit" variant="primary" class="w-full py-4 tracking-[0.3em] uppercase text-[10px] font-black transition-all hover:scale-[1.02] active:scale-[0.98]">
                        {{ __('Confirm') }}
                    </x-ui.button>
                </div>
            </form>

            <div class="mt-12 pt-8 border-t border-white/5 text-center">
                <p class="text-[9px] font-black uppercase tracking-[0.4em] text-on-surface-variant opacity-10">
                    {{ __('Verification Required') }}
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
