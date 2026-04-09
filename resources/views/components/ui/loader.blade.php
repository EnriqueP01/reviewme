<div {{ $attributes->merge(['class' => 'relative inline-flex flex-col items-center gap-2']) }}>
    <div class="relative w-8 h-8">
        <div class="absolute inset-0 border-[3px] border-primary/10 rounded-full"></div>
        <div class="absolute inset-0 border-[3px] border-t-primary rounded-full animate-spin shadow-[0_0_15px_rgba(var(--primary-rgb),0.3)]"></div>
    </div>
    @if($slot->isNotEmpty())
        <span class="text-[8px] font-black uppercase tracking-[0.3em] text-primary/60 animate-pulse">{{ $slot }}</span>
    @endif
</div>
