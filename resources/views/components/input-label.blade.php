@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-display font-bold text-xs uppercase tracking-widest text-on-surface-variant mb-2']) }}>
    {{ $value ?? $slot }}
</label>
