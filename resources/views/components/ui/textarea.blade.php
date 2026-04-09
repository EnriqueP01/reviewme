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
</div>
