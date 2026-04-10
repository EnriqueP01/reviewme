<nav x-data="{ open: false }" class="bg-surface/40 backdrop-blur-3xl sticky top-0 z-50 border-b border-subtle h-24 flex items-center transition-all duration-500">
    <div class="max-w-7xl mx-auto px-12 w-full">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-20">
                <!-- Logo: The Monolith Lens -->
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-5 group" @mouseenter="window.fx.play('hover')">
                    <x-ui.logo size="w-14 h-14" font="text-2xl" />
                    <div class="flex flex-col">
                        <span class="font-display font-black text-2xl tracking-tight text-on-surface group-hover:text-primary transition-colors duration-300">ReviewMe</span>
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
                            <span class="text-[9px] font-black uppercase tracking-[0.5em] text-on-surface-variant opacity-40">{{ __('Online') }}</span>
                        </div>
                    </div>
                </a>

                <div class="hidden sm:flex items-center space-x-12">
                    <a href="{{ route('dashboard') }}" 
                       wire:navigate
                       @mouseenter="window.fx.play('hover')"
                       class="relative group/link py-2 font-display font-black text-[10px] uppercase tracking-[0.3em] transition-all duration-300 {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-on-surface-variant hover:text-on-surface' }}">
                        {{ __('Feed') }}
                        <div class="absolute -bottom-1 left-0 w-0 h-px bg-primary transition-all duration-300 group-hover/link:w-full {{ request()->routeIs('dashboard') ? 'w-full' : '' }}"></div>
                    </a>

                    <a href="{{ route('groups') }}" 
                       wire:navigate
                       @mouseenter="window.fx.play('hover')"
                       class="relative group/link py-2 font-display font-black text-[10px] uppercase tracking-[0.3em] transition-all duration-300 {{ request()->routeIs('groups') ? 'text-primary' : 'text-on-surface-variant hover:text-on-surface' }}">
                        {{ __('Groups') }}
                        <div class="absolute -bottom-1 left-0 w-0 h-px bg-primary transition-all duration-300 group-hover/link:w-full {{ request()->routeIs('groups') ? 'w-full' : '' }}"></div>
                    </a>
                </div>
            </div>

            <!-- Right Actions -->
            <div class="hidden sm:flex items-center gap-10">
                <!-- Language Switcher: High Contrast Precision -->
                <div class="relative flex items-center p-1 bg-surface-container-low/50 rounded-xl border border-white/5 backdrop-blur-md"
                     x-data="{ locale: '{{ app()->getLocale() }}' }">
                    <!-- Highlight Layer: Solid & Glowing -->
                    <div class="absolute inset-y-1 transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] bg-primary rounded-lg shadow-[0_0_20px_rgba(190,194,255,0.25)]"
                         :class="locale === 'fr' ? 'translate-x-0 w-8' : 'translate-x-[36px] w-8'">
                    </div>
                    
                    <div class="flex items-center gap-1">
                        <a href="{{ route('lang', 'fr') }}" 
                           @click="locale = 'fr'"
                           class="relative z-10 w-8 h-7 flex items-center justify-center text-[10px] font-black transition-colors duration-300"
                           :class="locale === 'fr' ? 'text-surface' : 'text-on-surface-variant hover:text-on-surface'">
                           FR
                        </a>
                        <a href="{{ route('lang', 'en') }}" 
                           @click="locale = 'en'"
                           class="relative z-10 w-8 h-7 flex items-center justify-center text-[10px] font-black transition-colors duration-300"
                           :class="locale === 'en' ? 'text-surface' : 'text-on-surface-variant hover:text-on-surface'">
                           EN
                        </a>
                    </div>
                </div>

                @auth
                    <a href="{{ route('publish') }}" wire:navigate @mouseenter="window.fx.play('hover')">
                        <x-ui.button variant="primary" size="sm">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                            {{ __('Post Review') }}
                        </x-ui.button>
                    </a>

                    <div class="h-10 w-px bg-white/5"></div>

                    <!-- Notification Bell -->
                    <button @click="$dispatch('toggle-notifications')" 
                            class="relative group p-3 rounded-xl bg-surface-container-low border border-white/5 hover:border-primary/40 hover:bg-surface-container-high transition-all duration-300">
                        <svg class="w-6 h-6 text-on-surface-variant group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @php $unreadCount = auth()->user()->unreadNotifications()->count(); @endphp
                        @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-primary text-[10px] font-black text-on-primary rounded-full flex items-center justify-center border-2 border-surface shadow-[0_0_15px_rgba(190,194,255,0.4)] animate-pulse">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <div class="h-10 w-px bg-white/5"></div>

                    <div class="relative" x-data="{ dropOpen: false }">
                        <x-dropdown align="right" width="64" contentClasses="bg-transparent shadow-none">
                            <x-slot name="trigger">
                                <button @click="dropOpen = !dropOpen" class="group relative focus:outline-none">
                                    <div class="w-12 h-12 rounded-2xl bg-surface-container-highest border border-white/5 flex items-center justify-center text-primary font-display italic font-black transition-all duration-500 group-hover:border-primary/40 group-hover:shadow-[0_0_25px_rgba(190,194,255,0.1)] group-hover:-translate-y-0.5 relative overflow-hidden">
                                         <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent"></div>
                                         <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <!-- Status Indicator -->
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-secondary border-4 border-surface group-hover:scale-110 transition-transform"></div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="mt-4 glass-panel overflow-hidden rounded-[2rem] border border-white/10 shadow-[0_30px_80px_-20px_rgba(0,0,0,0.8)] animate-fade-in-up">
                                    <!-- Profile Capsule -->
                                    <div class="relative p-8 bg-white/[0.03] border-b border-white/5">
                                        <div class="absolute top-4 right-4 text-[8px] font-black uppercase tracking-widest text-primary/40">{{ __('Member') }}</div>
                                        <div class="flex items-center gap-6">
                                            <div class="w-16 h-16 rounded-2xl bg-primary text-on-primary flex items-center justify-center text-3xl font-display font-black italic shadow-2xl overflow-hidden">
                                                 <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-black text-on-surface leading-none">{{ Auth::user()->name }}</span>
                                                <span class="text-[10px] font-medium text-on-surface-variant mt-2 opacity-60">{{ Auth::user()->email }}</span>
                                                <div class="mt-3 flex items-center gap-2">
                                                    <div class="h-1.5 w-16 bg-white/5 rounded-full overflow-hidden">
                                                        <div class="h-full bg-primary w-2/3"></div>
                                                    </div>
                                                    <span class="text-[8px] font-black uppercase text-primary">Rep {{ Auth::user()->reputation_score }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Grid -->
                                    <div class="p-4 grid grid-cols-2 gap-2">
                                        <a href="{{ route('profile') }}" wire:navigate @mouseenter="window.fx.play('hover')" class="flex flex-col items-center gap-3 p-4 rounded-2xl hover:bg-white/5 transition-all group/item">
                                            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-on-surface-variant group-hover/item:text-primary transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            </div>
                                            <span class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant group-hover/item:text-on-surface">{{ __('Profile') }}</span>
                                        </a>
                                        <a href="{{ route('profile.edit') }}" wire:navigate @mouseenter="window.fx.play('hover')" class="flex flex-col items-center gap-3 p-4 rounded-2xl hover:bg-white/5 transition-all group/item">
                                            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-on-surface-variant group-hover/item:text-primary transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            </div>
                                            <span class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant group-hover/item:text-on-surface">{{ __('Settings') }}</span>
                                        </a>
                                    </div>

                                    <!-- Footer Action -->
                                    <form method="POST" action="{{ route('logout') }}" class="p-2 border-t border-white/5">
                                        @csrf
                                        <button onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center justify-between w-full px-6 py-4 rounded-3xl hover:bg-error/10 text-on-surface-variant hover:text-error transition-all group/logout">
                                            <span class="text-[10px] font-black uppercase tracking-wider">{{ __('Log out') }}</span>
                                            <svg class="w-4 h-4 opacity-40 group-hover/logout:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <div class="flex items-center gap-6">
                        <a href="{{ route('login', ['mode' => 'login']) }}" class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant hover:text-white transition-colors">{{ __('Sign In') }}</a>
                        <x-ui.button variant="primary" size="sm" href="{{ route('register', ['mode' => 'register']) }}">
                            {{ __('Get Started') }}
                        </x-ui.button>
                    </div>
                @endauth
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="sm:hidden">
                <button @click="open = ! open" class="p-4 rounded-2xl bg-surface-container-high text-on-surface-variant border border-white/5">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 8h16M4 16h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Menu -->
    <div :class="{'translate-x-0': open, 'translate-x-full': !open}" class="fixed inset-y-0 right-0 w-80 bg-surface-container-highest/95 backdrop-blur-2xl z-[60] border-l border-white/5 transition-transform duration-500 transform ease-in-out sm:hidden">
        <div class="p-10 space-y-10">
            <div class="flex justify-between items-center">
                <span class="font-display font-black text-xl uppercase tracking-widest text-primary">{{ __('System') }}</span>
                <button @click="open = false" class="text-on-surface-variant"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="space-y-6">
                 <a href="{{ route('dashboard') }}" class="block text-3xl font-display font-black text-on-surface hover:text-primary transition-colors">{{ __('Feed') }}</a>
            </div>
            <div class="pt-10 border-t border-white/5">
                <a href="{{ route('publish') }}" class="block">
                    <x-ui.button variant="primary" size="lg" class="w-full">{{ __('Post Review') }}</x-ui.button>
                </a>
            </div>
        </div>
    </div>
</nav>
