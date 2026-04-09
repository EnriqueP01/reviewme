@props(['disabled' => false, 'type' => 'text', 'placeholder' => ''])

@php
    $inputClasses = 'bg-[#1a1b26] border border-white/5 text-on-surface placeholder:text-on-surface-variant/20 focus:border-primary/60 focus:ring-0 rounded-2xl transition-all duration-300 w-full px-6 py-4 group-hover:bg-[#1e1f2b] font-medium tracking-tight selection:bg-primary/20 shadow-inner';
@endphp

<div x-data="{ 
    inputType: '{{ $type }}',
    showPassword: false,
    toggle() {
        this.showPassword = !this.showPassword;
        this.inputType = this.showPassword ? 'text' : 'password';
    }
}" class="relative group">
    <!-- Inner Shadow Glow -->
    <div class="absolute inset-0 rounded-2xl pointer-events-none group-focus-within:shadow-[0_0_20px_rgba(190,194,255,0.05)] transition-all duration-500"></div>

    <input 
        :type="inputType"
        @disabled($disabled) 
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'bg-black/20 border border-white/5 text-on-surface placeholder:text-on-surface-variant/10 focus:border-primary/40 focus:ring-0 rounded-2xl transition-all duration-300 w-full px-6 py-4 font-bold tracking-tight selection:bg-primary/20 shadow-inner outline-none']) }}
    >
    
    @if($type === 'password')
        <button 
            type="button" 
            @click="toggle()" 
            class="absolute right-6 top-1/2 -translate-y-1/2 text-on-surface-variant/40 hover:text-primary transition-all focus:outline-none z-10 p-2 hover:bg-white/5 rounded-lg"
        >
            <template x-if="!showPassword">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.799 8.241 7.426 6 12 6s8.201 2.241 9.964 5.678c.145.281.145.617 0 .898C20.201 15.759 16.574 18 12 18s-8.201-2.241-9.964-5.678z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </template>
            <template x-if="showPassword">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                </svg>
            </template>
        </button>
    @endif
</div>
