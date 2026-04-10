<div wire:poll.30s="refresh">
    <button @click="$wire.toggle()" 
            class="relative group p-3 rounded-xl bg-surface-container-low border border-white/5 hover:border-primary/40 hover:bg-surface-container-high transition-all duration-300">
        <svg class="w-6 h-6 text-on-surface-variant group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 w-5 h-5 bg-primary text-[10px] font-black text-on-primary rounded-full flex items-center justify-center border-2 border-surface shadow-[0_0_15px_rgba(190,194,255,0.4)] animate-pulse">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>
</div>
