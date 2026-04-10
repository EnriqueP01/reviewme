<x-guest-layout>
    <div class="w-full max-w-md animate-fade-in-up" style="animation-delay: 0.1s">
        <div class="glass-panel p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
            <!-- Internal Glow -->
            <div class="absolute -inset-px bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

            <div class="mb-10 text-center relative z-10">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-6 text-primary shadow-[0_0_30px_rgba(190,194,255,0.1)]">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-display font-black text-on-surface tracking-tight mb-4 uppercase">{{ __('Verify Email') }}</h1>
                <p class="text-on-surface-variant text-sm italic px-4">
                    {{ __('Thanks for signing up! Please verify your email address by clicking on the link we just emailed to you.') }}
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-8 p-4 bg-primary/10 border border-primary/20 rounded-2xl text-primary text-xs font-black uppercase tracking-widest flex items-center gap-3 animate-in fade-in slide-in-from-top-2 duration-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                    {{ __('Verification link sent.') }}
                </div>
            @endif

            <div class="space-y-6 relative z-10">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-ui.button type="submit" variant="primary" class="w-full py-4 tracking-[0.3em] uppercase text-[10px] font-black transition-all hover:scale-[1.02] active:scale-[0.98]">
                        {{ __('Resend Verification Email') }}
                    </x-ui.button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="text-center">
                    @csrf
                    <button type="submit" class="text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant hover:text-error transition-colors">
                        {{ __('Logout') }}
                    </button>
                </form>
            </div>

            <div class="mt-12 pt-8 border-t border-white/5 text-center">
                <p class="text-[9px] font-black uppercase tracking-[0.4em] text-on-surface-variant opacity-10">
                    {{ __('Verification Pending') }}
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
