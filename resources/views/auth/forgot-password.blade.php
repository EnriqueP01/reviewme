<x-guest-layout>
    <div class="w-full max-w-md animate-fade-in-up" style="animation-delay: 0.1s">
        <div class="glass-panel p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
            <!-- Internal Glow -->
            <div class="absolute -inset-px bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

            <div class="mb-10 text-center relative z-10">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-6 text-primary shadow-[0_0_30px_rgba(190,194,255,0.1)]">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-display font-black text-on-surface tracking-tight mb-4 uppercase">{{ __('Reset Password') }}</h1>
                <p class="text-on-surface-variant text-sm italic px-4">
                    {{ __('Forgot your password? Enter your email to receive a reset link.') }}
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-8 relative z-10">
                @csrf

                <!-- Email Address -->
                <div class="space-y-3">
                    <label for="email" class="text-[9px] font-black uppercase tracking-[0.3em] text-on-surface-variant opacity-40 ml-1">
                        {{ __('Email Address') }}
                    </label>
                    <x-text-input 
                        id="email" 
                        class="block w-full" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                        placeholder="email@example.com"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="pt-4 flex flex-col gap-6">
                    <x-ui.button type="submit" variant="primary" class="w-full py-4 tracking-[0.3em] uppercase text-[10px] font-black transition-all hover:scale-[1.02] active:scale-[0.98]">
                        {{ __('Send Reset Link') }}
                    </x-ui.button>
                    
                    <a href="{{ route('login') }}" class="text-center text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant hover:text-primary transition-colors">
                        <span class="flex items-center justify-center gap-2">
                             <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                             {{ __('Back to Login') }}
                        </span>
                    </a>
                </div>
            </form>

            <div class="mt-12 pt-8 border-t border-white/5 text-center">
                <p class="text-[9px] font-black uppercase tracking-[0.4em] text-on-surface-variant opacity-10">
                    {{ __('Secure Recovery Active') }}
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
