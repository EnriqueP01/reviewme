@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-surface-high border-none text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/50 rounded-round-4 transition-all duration-300 w-full']) }}>
