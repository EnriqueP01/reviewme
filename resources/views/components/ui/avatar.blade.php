@props([
    'model',
    'size' => 'md',
    'class' => '',
    'link' => true
])

@php
    $isUser = $model instanceof \App\Models\User;
    $isGroup = $model instanceof \App\Models\Group;

    $imagePath = null;
    $name = $model->name ?? 'User';
    $href = null;
    
    if ($isUser) {
        $imagePath = $model->profile_photo_path;
        $href = route('profile.show', $model->handle);
        if (!$imagePath && !empty($model->avatar)) {
            if (filter_var($model->avatar, FILTER_VALIDATE_URL)) {
                $imagePath = $model->avatar;
            }
        }
    } elseif ($isGroup) {
        $imagePath = $model->logo_path;
        $href = route('groups.show', $model->slug);
    }

    $initials = strtoupper(substr($name, 0, 1));
    
    $sizes = [
        'xs' => 'w-6 h-6 text-[10px]',
        'sm' => 'w-8 h-8 text-xs',
        'md' => 'w-10 h-10 text-sm',
        'lg' => 'w-12 h-12 text-base',
        'xl' => 'w-16 h-16 text-xl',
        '2xl' => 'w-24 h-24 text-2xl',
    ];

    $sizeClass = $sizes[$size] ?? $sizes['md'];
    
    $hasImage = false;
    $imageUrl = null;
    
    if ($imagePath) {
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            $hasImage = true;
            $imageUrl = $imagePath;
        } else {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
                $hasImage = true;
                $imageUrl = asset('storage/' . $imagePath);
            }
        }
    }

    $tag = ($link && $href) ? 'a' : 'div';
@endphp

<{{ $tag }} 
    @if($link && $href) 
        href="{{ $href }}" 
        wire:navigate 
    @endif
    {{ $attributes->merge(['class' => "relative flex-shrink-0 $sizeClass rounded-xl border border-white/10 shadow-lg overflow-hidden $class " . (($link && $href) ? 'hover:scale-105 hover:border-primary/50 transition-all duration-300 cursor-pointer' : '')]) }}
>
    @if($hasImage)
        <img src="{{ $imageUrl }}" alt="{{ $name }}" class="w-full h-full object-cover">
    @else
        <div class="w-full h-full flex items-center justify-center font-black font-display tracking-tighter"
             style="background: linear-gradient(135deg, {{ $isGroup ? 'var(--lens-beauty)' : 'var(--primary)' }} 0%, #0d0e16 100%);">
            <span class="text-black">{{ $initials }}</span>
        </div>
    @endif
    
    @if($isUser)
        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-primary border-2 border-[#0d0e12] rounded-full shadow-lg"></div>
    @endif
</{{ $tag }}>
