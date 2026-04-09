@props([
    'name',
    'title' => __('Are you sure?'),
    'content' => '',
    'confirmText' => __('Confirm'),
    'cancelText' => __('Cancel'),
    'variant' => 'primary',
])

<x-modal :name="$name" maxWidth="md">
    <div class="p-10 relative overflow-hidden group/modal bg-surface-lowest border border-white/5 shadow-2xl rounded-[2.5rem]">
        <!-- Orbital Glows (Post-Detail Style) -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/10 blur-[100px] rounded-full group-hover/modal:bg-primary/20 transition-all duration-700 pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-rose-500/5 blur-[100px] rounded-full pointer-events-none"></div>

        <div class="relative space-y-10">
            <!-- Icon & Title Monolith -->
            <div class="flex flex-col items-center text-center gap-6">
                <div @class([
                    'w-24 h-24 rounded-[2rem] flex items-center justify-center shadow-2xl relative overflow-hidden group/icon transition-all duration-500 hover:scale-110',
                    'bg-primary/10 text-primary border border-primary/20' => $variant === 'primary',
                    'bg-rose-500/10 text-rose-500 border border-rose-500/20' => $variant === 'danger',
                ])>
                    <div class="absolute inset-0 bg-gradient-to-br from-current to-transparent opacity-10"></div>
                    <svg class="w-12 h-12 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                
                <div class="space-y-4">
                    <h3 class="text-4xl font-display font-black text-on-surface tracking-tighter leading-none">{{ $title }}</h3>
                    <div class="flex items-center justify-center gap-3">
                        <div class="h-[1px] w-12 bg-white/5"></div>
                        <span class="text-[10px] font-black uppercase tracking-[0.4em] text-on-surface-variant/30">{{ __('Operation Terminal') }}</span>
                        <div class="h-[1px] w-12 bg-white/5"></div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="bg-black/20 rounded-[1.5rem] p-6 border border-white/5 shadow-inner">
                <p class="text-[11px] font-medium text-on-surface-variant/80 leading-relaxed text-center italic">
                    {{ $content }}
                </p>
            </div>

            <!-- Global Actions -->
            <div class="flex items-center gap-4">
                <x-ui.button 
                    variant="ghost" 
                    x-on:click="show = false" 
                    class="flex-1 !rounded-2xl !px-8 !py-5 text-[10px] font-black uppercase tracking-[0.2em] !text-on-surface-variant/40 hover:!text-white hover:!bg-white/5 border-transparent outline-none"
                >
                    {{ $cancelText }}
                </x-ui.button>
                
                <x-ui.button 
                    variant="{{ $variant === 'danger' ? 'ghost' : $variant }}"
                    class="flex-1 !rounded-2xl !px-8 !py-5 text-[10px] font-black uppercase tracking-[0.2em] shadow-[0_0_40px_rgba(0,0,0,0.5)] outline-none"
                    :class="$variant === 'danger' ? '!text-rose-500 !bg-rose-500/5 hover:!bg-rose-500/20 !border-rose-500/20 shadow-rose-500/10' : ''"
                    {{ $attributes }}
                >
                    {{ $confirmText }}
                </x-ui.button>
            </div>
        </div>
    </div>
</x-modal>
