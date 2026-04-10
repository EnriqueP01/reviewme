<x-guest-layout>
    <div class="w-full max-w-6xl mb-20 animate-fade-in-up" 
         x-data="{ 
            mode: '{{ request()->query('mode', 'register') }}' 
         }"
         style="animation-delay: 0.1s">
        
        <div class="glass-panel p-8 lg:p-12 rounded-[3.5rem] shadow-2xl relative overflow-hidden group">
            <!-- Internal Glow -->
            <div class="absolute -inset-px bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-stretch relative z-10">
                
                <!-- LEFT COLUMN: IDENTITY HUB (GITHUB) -->
                <div class="flex flex-col justify-between py-4">
                    <div class="space-y-12">
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <x-ui.logo size="w-12 h-12" />
                                <h1 class="font-display font-black text-2xl tracking-tighter uppercase">{{ __('Identity Hub') }}</h1>
                            </div>
                            <p class="text-on-surface-variant text-sm italic max-w-xs">{{ __('Connect your developer profile for a seamless experience.') }}</p>
                        </div>

                        <!-- Mock Profile Visual (Filling space) -->
                        <div class="relative group/mock">
                            <div class="absolute -inset-4 bg-primary/5 rounded-[2.5rem] blur-2xl group-hover/mock:bg-primary/10 transition-colors duration-700"></div>
                            <div class="relative bg-black/40 backdrop-blur-2xl border border-white/5 rounded-3xl p-8 flex items-center gap-6 shadow-2xl overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                                
                                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-white/10 to-white/5 p-0.5 shadow-xl">
                                    <div class="w-full h-full rounded-[0.9rem] bg-surface flex items-center justify-center">
                                        <svg class="w-10 h-10 text-on-surface-variant/20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <div class="h-5 w-32 bg-white/10 rounded-full animate-pulse-slow"></div>
                                    <div class="h-3 w-20 bg-white/5 rounded-full"></div>
                                    <div class="flex gap-2 mt-4">
                                        <div class="px-3 py-1 bg-primary/20 rounded-full text-[8px] font-black text-primary uppercase tracking-widest border border-primary/20">Senior</div>
                                        <div class="px-3 py-1 bg-white/5 rounded-full text-[8px] font-black text-on-surface-variant/40 uppercase tracking-widest">Expert</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- GitHub Connect -->
                        <div class="space-y-4">
                            <div class="relative flex items-center py-2">
                                <div class="flex-grow border-t border-white/5"></div>
                                <span class="flex-shrink mx-6 text-[8px] font-black text-on-surface-variant uppercase tracking-[0.5em] opacity-30">{{ __('Express Access') }}</span>
                                <div class="flex-grow border-t border-white/5"></div>
                            </div>
                            <a href="{{ route('login.github') }}" class="flex items-center justify-center gap-4 w-full py-5 px-8 bg-white/[0.03] hover:bg-white/[0.06] border border-white/10 rounded-3xl text-on-surface font-black text-xs uppercase tracking-[0.4em] transition-all shadow-xl hover:shadow-primary/5 group/btn">
                                <svg class="w-6 h-6 fill-current text-primary group-hover/btn:scale-110 transition-transform duration-500" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.041-1.412-4.041-1.412-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                <span>{{ __('Connect with GitHub') }}</span>
                            </a>
                        </div>
                    </div>

                    <div class="mt-8">
                        <p class="text-[9px] font-black uppercase tracking-[0.3em] text-on-surface-variant opacity-20 italic">
                            {{ __('Your identity is end-to-end encrypted.') }}
                        </p>
                    </div>
                </div>

                <!-- RIGHT COLUMN: STANDARD ACCESS (SLIDER) -->
                <div class="lg:pl-20 py-4 lg:border-l lg:border-white/5 space-y-12">
                    <div class="space-y-8">
                        <!-- Mode Slider -->
                        <div class="flex items-center gap-2 bg-black/20 backdrop-blur-3xl rounded-[1.5rem] p-1.5 border border-white/5 shadow-2xl w-fit">
                            <button 
                                type="button"
                                @click="mode = 'login'"
                                :class="mode === 'login' ? 'bg-primary text-on-primary shadow-[0_0_30px_rgba(190,194,255,0.3)] scale-105' : 'text-on-surface-variant/40 hover:text-white hover:bg-white/5'"
                                class="px-8 py-3 rounded-2xl text-[9px] font-black uppercase tracking-[0.3em] transition-all duration-500"
                            >
                                {{ __('Sign In') }}
                            </button>
                            <button 
                                type="button"
                                @click="mode = 'register'"
                                :class="mode === 'register' ? 'bg-primary text-on-primary shadow-[0_0_30px_rgba(190,194,255,0.3)] scale-105' : 'text-on-surface-variant/40 hover:text-white hover:bg-white/5'"
                                class="px-8 py-3 rounded-2xl text-[9px] font-black uppercase tracking-[0.3em] transition-all duration-500"
                            >
                                {{ __('Join') }}
                            </button>
                        </div>

                        <div class="h-6">
                            <p x-show="mode === 'login'" x-cloak x-transition class="text-on-surface font-display font-medium text-lg tracking-tight">{{ __('Welcome back.') }}</p>
                            <p x-show="mode === 'register'" x-cloak x-transition class="text-on-surface font-display font-medium text-lg tracking-tight">{{ __('Create your account.') }}</p>
                        </div>
                    </div>

                    <!-- LOGIN FORM -->
                    <form x-show="mode === 'login'" x-transition x-cloak method="POST" action="{{ route('login') }}" class="space-y-8">
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
                    <form x-show="mode === 'register'" x-transition x-cloak method="POST" action="{{ route('register') }}" class="space-y-6">
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
            </div>

            <div class="mt-16 pt-8 border-t border-white/5 text-center">
                <p class="text-[9px] font-black uppercase tracking-[0.4em] text-on-surface-variant opacity-20">
                    {{ __('Secure Connection Infrastructure') }}
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
