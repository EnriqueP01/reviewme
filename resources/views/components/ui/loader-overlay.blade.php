@props(['target' => null])

<div 
    @if($target) wire:target="{{ $target }}" @endif
    wire:loading.flex
    class="absolute inset-0 z-50 flex items-center justify-center backdrop-blur-md bg-surface/40 rounded-[inherit]"
>
    <div class="flex flex-col items-center gap-4 py-12">
        <div class="relative w-12 h-12">
            <div class="absolute inset-0 border-4 border-primary/20 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-t-primary rounded-full animate-spin shadow-[0_0_15px_var(--primary)]"></div>
        </div>
        <span class="text-[10px] font-black uppercase tracking-[0.3em] text-primary animate-pulse">{{ __('Traitement Neural en cours...') }}</span>
    </div>
</div>
