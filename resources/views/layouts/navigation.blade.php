<nav x-data="{ open: false }" class="bg-surface-container/80 backdrop-blur-xl sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center gap-12">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 bg-primary/20 rounded-round-4 flex items-center justify-center transition-all duration-500 group-hover:scale-110 group-hover:bg-primary/30">
                            <span class="text-primary font-display font-bold text-xl leading-none">R</span>
                        </div>
                        <span class="font-display font-bold text-xl tracking-tight text-on-surface group-hover:text-primary transition-colors">ReviewMe</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:flex h-full">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 font-display font-medium text-sm leading-5 transition-all duration-300 {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-on-surface-variant hover:text-on-surface' }}">
                        {{ __('Feed') }}
                    </a>
                    <!-- Placeholder for other links -->
                    <a href="#" class="inline-flex items-center px-1 pt-1 font-display font-medium text-sm leading-5 text-on-surface-variant hover:text-on-surface transition-all duration-300">
                        {{ __('Discover') }}
                    </a>
                    <a href="#" class="inline-flex items-center px-1 pt-1 font-display font-medium text-sm leading-5 text-on-surface-variant hover:text-on-surface transition-all duration-300">
                        {{ __('Karma') }}
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                <a href="{{ route('publish') }}">
                    <x-ui.button variant="primary" size="sm" class="h-10">
                        {{ __('Post Review') }}
                    </x-ui.button>
                </a>

                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-3 px-3 py-1.5 rounded-round-4 bg-surface-high hover:bg-surface-highest transition-all duration-300 group">
                                <div class="w-8 h-8 rounded-full bg-secondary/20 flex items-center justify-center text-secondary font-bold text-xs">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="text-sm font-medium text-on-surface-variant group-hover:text-on-surface transition-colors">{{ Auth::user()->name }}</div>
                                <svg class="w-4 h-4 text-on-surface-variant group-hover:text-on-surface transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 border-b border-outline-variant/10">
                                <p class="text-xs text-on-surface-variant uppercase tracking-widest font-bold">Curator</p>
                            </div>
                            <x-dropdown-link :href="route('profile')" class="text-on-surface hover:bg-surface-high">
                                {{ __('My Portrait') }}
                            </x-dropdown-link>
                            
                            <x-dropdown-link :href="route('profile.edit')" class="text-on-surface hover:bg-surface-high">
                                {{ __('Configuration') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        class="text-on-surface hover:bg-surface-high"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-round-4 text-on-surface-variant hover:text-on-surface hover:bg-surface-high transition-all">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-surface-high">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-on-surface">
                {{ __('Feed') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-outline-variant/10">
            <div class="px-6">
                <div class="font-display font-bold text-base text-on-surface">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-on-surface-variant">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-on-surface">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            class="text-on-surface"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
