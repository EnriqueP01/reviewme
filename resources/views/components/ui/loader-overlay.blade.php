@props(['target' => null])

<div 
    @if($target) wire:target="{{ $target }}" @endif
    wire:loading.flex
    class="absolute inset-0 z-50 flex items-center justify-center backdrop-blur-md bg-surface/40 rounded-[inherit]"
>
    <div class="flex flex-col items-center gap-4 py-12">
        <x-ui.loader>{{ __('Loading...') }}</x-ui.loader>
    </div>
</div>
