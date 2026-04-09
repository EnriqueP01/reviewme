@props(['disabled' => false, 'placeholder' => '', 'label' => ''])

@php
    $textareaClasses = 'bg-[#1a1b26] border border-white/5 text-on-surface placeholder:text-on-surface-variant/20 focus:border-primary/60 focus:ring-0 rounded-2xl transition-all duration-300 w-full px-4 py-3 group-hover:bg-[#1e1f2b] font-medium tracking-tight selection:bg-primary/20 shadow-inner min-h-[120px]';
@endphp

<div class="relative group space-y-2">
    @if($label)
        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-primary/80 ml-2">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        <div class="absolute inset-0 rounded-2xl pointer-events-none group-focus-within:shadow-[0_0_20px_rgba(190,194,255,0.05)] transition-all duration-500"></div>

        <textarea 
            @disabled($disabled) 
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => $textareaClasses]) }}
        >{{ $slot }}</textarea>
        
        <div class="absolute -inset-[2px] rounded-[1.2rem] border-2 border-primary/40 opacity-0 group-focus-within:opacity-100 transition-all duration-300 pointer-events-none scale-[0.98] group-focus-within:scale-100"></div>
    </div>
    @if($attributes->has('wire:model'))
        @error($attributes->get('wire:model'))
            <p class="text-[10px] uppercase font-black tracking-widest text-error/90 mt-1 ml-2 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ $message }}
            </p>
        @enderror
    @endif
</div>
