<x-guest-layout>
    <div class="w-full max-w-lg mb-20 animate-fade-in-up" 
         x-data="{ 
            mode: '{{ request()->query('mode', 'register') }}',
            init() {
                // If there are specific errors for certain fields, switch mode
                @if($errors->hasAny(['email', 'password']) && !$errors->hasAny(['name', 'password_confirmation']))
                    // Only show login if it's purely login errors
                @endif
            }
         }"
         style="animation-delay: 0.1s">
        
        <div class="glass-panel p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
            <!-- Internal Glow -->
            <div class="absolute -inset-px bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

            <div class="mb-12 text-center relative z-10 space-y-4">
                <div class="flex items-center justify-center gap-4 mb-8">
                    <x-ui.logo size="w-16 h-16" font="text-3xl" />
                </div>
                
                <!-- Authentic Mode Slider -->
                <div class="flex items-center gap-2 bg-black/20 backdrop-blur-3xl rounded-[1.5rem] p-1.5 border border-white/5 shadow-2xl w-fit mx-auto">
                    <button 
                        type="button"
                        @click="mode = 'login'"
                        :class="mode === 'login' ? 'bg-primary text-on-primary shadow-[0_0_30px_rgba(190,194,255,0.3)] scale-105' : 'text-on-surface-variant/40 hover:text-white hover:bg-white/5'"
                        class="px-10 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] transition-all duration-500"
                    >
                        {{ __('Sign In') }}
                    </button>
                    <button 
                        type="button"
                        @click="mode = 'register'"
                        :class="mode === 'register' ? 'bg-primary text-on-primary shadow-[0_0_30px_rgba(190,194,255,0.3)] scale-105' : 'text-on-surface-variant/40 hover:text-white hover:bg-white/5'"
                        class="px-10 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] transition-all duration-500"
                    >
                        {{ __('Join') }}
                    </button>
                </div>

                <div class="h-10 flex items-center justify-center">
                    <p x-show="mode === 'login'" x-cloak x-transition class="text-on-surface-variant text-sm italic">{{ __('Welcome back.') }}</p>
                    <p x-show="mode === 'register'" x-cloak x-transition class="text-on-surface-variant text-sm italic">{{ __('Join the community of experts.') }}</p>
                </div>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6" :status="session('status')" />

            @if (session('error'))
                <div class="mb-6 p-4 bg-error/10 border border-error/20 rounded-xl text-error text-sm font-medium flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="space-y-8 relative z-10">
                <!-- GitHub OAuth (Unified) -->
                <a href="{{ route('login.github') }}" class="flex items-center justify-center gap-4 w-full py-4 px-6 bg-white/[0.03] hover:bg-white/[0.06] border border-white/10 rounded-2xl text-on-surface font-black text-xs uppercase tracking-[0.4em] transition-all shadow-xl hover:shadow-primary/5 group">
                    <svg class="w-6 h-6 fill-current text-primary group-hover:scale-110 transition-transform" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.041-1.412-4.041-1.412-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    <span x-text="mode === 'login' ? '{{ __('Login with GitHub') }}' : '{{ __('Register with GitHub') }}'"></span>
                </a>

                <div class="relative flex items-center py-2">
                    <div class="flex-grow border-t border-white/5"></div>
                    <span class="flex-shrink mx-6 text-[8px] font-black text-on-surface-variant uppercase tracking-[0.5em] opacity-30">{{ __('Standard Access') }}</span>
                    <div class="flex-grow border-t border-white/5"></div>
                </div>

                <!-- LOGIN FORM -->
                <form x-show="mode === 'login'" x-transition x-cloak method="POST" action="{{ route('login') }}" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                    @csrf
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant opacity-40 ml-1">{{ __('Email') }}</label>
                            <x-text-input id="login_email" type="email" name="email" :value="old('email')" required autofocus placeholder="email@example.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between px-1">
                                <label class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant opacity-40">{{ __('Password') }}</label>
                                @if (Route::has('password.request'))
                                    <a class="text-[8px] uppercase tracking-widest text-primary/40 hover:text-primary transition-colors font-black" href="{{ route('password.request') }}">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                @endif
                            </div>
                            <x-text-input id="login_password" type="password" name="password" required placeholder="••••••••" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                            <input id="remember_me" type="checkbox" class="rounded bg-white/5 border-white/10 text-primary focus:ring-primary/20" name="remember">
                            <span class="ms-3 text-[10px] font-black uppercase tracking-widest text-on-surface-variant group-hover:text-on-surface transition-colors">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <x-ui.button type="submit" variant="primary" class="w-full py-4 uppercase text-[10px] tracking-[0.3em] font-black transition-all hover:scale-[1.02] active:scale-[0.98]">
                        {{ __('Login') }}
                    </x-ui.button>
                </form>

                <!-- REGISTER FORM -->
                <form x-show="mode === 'register'" x-transition x-cloak method="POST" action="{{ route('register') }}" class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
                    @csrf
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant opacity-40 ml-1">{{ __('Full Name') }}</label>
                            <x-text-input id="name" type="text" name="name" :value="old('name')" required placeholder="John Doe" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant opacity-40 ml-1">{{ __('Email Address') }}</label>
                            <x-text-input id="register_email" type="email" name="email" :value="old('email')" required placeholder="email@example.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant opacity-40 ml-1">{{ __('Password') }}</label>
                                <x-text-input id="register_password" type="password" name="password" required placeholder="••••••••" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant opacity-40 ml-1">{{ __('Confirm') }}</label>
                                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required placeholder="••••••••" />
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-0" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-0" />
                    </div>

                    <div class="pt-4">
                        <x-ui.button type="submit" variant="primary" class="w-full py-4 tracking-[0.3em] uppercase text-[10px] font-black transition-all hover:scale-[1.02] active:scale-[0.98]">
                            {{ __('Register') }}
                        </x-ui.button>
                    </div>
                </form>
            </div>
            
            <div class="mt-12 pt-8 border-t border-white/5 text-center">
                <p class="text-[9px] font-black uppercase tracking-[0.4em] text-on-surface-variant opacity-20">
                    {{ __('Secure Connection') }}
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
