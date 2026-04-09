<div class="max-w-4xl mx-auto px-6 py-12">
    <!-- Stepper Navigation -->
    <div class="mb-16 relative mx-auto max-w-2xl">
        <div class="absolute top-5 left-[20px] right-[20px] h-0.5 bg-surface-highest z-0">
             <div class="h-full bg-primary transition-all duration-1000" style="width: {{ ($step - 1) * 50 }}%"></div>
        </div>
        <div class="flex items-center justify-between relative z-10 px-0">

            @foreach([1, 2, 3] as $s)
                <div class="flex flex-col items-center gap-3">
                    <div @class([
                        "w-10 h-10 rounded-full flex items-center justify-center font-display font-black transition-all duration-500",
                        "bg-primary text-black ring-4 ring-primary/20" => $step >= $s,
                        "bg-surface-high text-on-surface-variant ring-4 ring-white/5" => $step < $s
                    ])>
                        {{ $s }}
                    </div>
                    <span @class([
                        "text-[10px] uppercase tracking-widest font-black",
                        "text-primary" => $step >= $s,
                        "text-on-surface-variant opacity-50" => $step < $s
                    ])>
                        {{ $s == 1 ? __('Introspection') : ($s == 2 ? __("The Artifacts") : __('Distribution')) }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>



    <form wire:submit.prevent="submit">
        @if($step == 1)
            <!-- Step 1: Introspection -->
            <div class="space-y-4 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="space-y-2">
                    <h2 class="font-display text-3xl font-bold text-on-surface tracking-tight">{{ __('Contextual Audit') }} <span class="text-red-500">*</span></h2>
                    <p class="text-on-surface-variant italic">{{ __('Define the core purpose and technical scope of this curation artifact.') }}</p>
                </div>


                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label :value="__('Artifact Title') . ' *'" />
                            <x-text-input wire:model="title" placeholder="{{ __('e.g., Memory Optimizer Engine') }}" />
                            <x-input-error :messages="$errors->get('title')" />
                        </div>
                        <div>
                            <x-input-label :value="__('Short Summary')" />
                            <x-text-input wire:model="short_description" placeholder="{{ __('Briefly hook the curators...') }}" />
                            <x-input-error :messages="$errors->get('short_description')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label :value="__('Target Review Goals')" />
                            <textarea wire:model="review_goals" rows="3" class="bg-surface-high border-none text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/50 rounded-round-4 transition-all duration-300 w-full resize-none p-4" placeholder="{{ __('What logic should be audited?') }}"></textarea>
                            <x-input-error :messages="$errors->get('review_goals')" />
                        </div>
                        <div>
                            <x-input-label :value="__('Improvement Desires')" />
                            <textarea wire:model="improvement_goals" rows="3" class="bg-surface-high border-none text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/50 rounded-round-4 transition-all duration-300 w-full resize-none p-4" placeholder="{{ __('What do you want to elevate?') }}"></textarea>
                            <x-input-error :messages="$errors->get('improvement_goals')" />
                        </div>
                    </div>


                    <div>
                        <x-input-label :value="__('Full Context & Technical Background')" />
                        <textarea wire:model="description" rows="5" class="bg-surface-high border-none text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/50 rounded-round-4 transition-all duration-300 w-full resize-none p-4" placeholder="{{ __('Detailed documentation for the curators...') }}"></textarea>
                    </div>
                </div>
            </div>
        @elseif($step == 2)
            <!-- Step 2: Artifacts (The Digital Logic) -->
            <div class="space-y-12 animate-in fade-in slide-in-from-bottom-8 duration-700">
                <div class="sticky top-20 z-40 flex flex-col md:flex-row justify-between items-start md:items-end gap-6 bg-surface-lowest/95 backdrop-blur-2xl p-6 -mx-6 rounded-b-round-4 border-b border-outline-variant/20 shadow-[0_20px_50px_-10px_rgba(0,0,0,0.5)] transition-all duration-500">


                    <div class="space-y-4 max-w-2xl">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-round-2 bg-primary/20 flex items-center justify-center text-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <h2 class="font-display text-2xl font-bold text-on-surface tracking-tight">{{ __('The Digital Artifacts') }}</h2>
                        </div>
                        <p class="text-[10px] text-on-surface-variant font-mono uppercase tracking-widest opacity-60">
                            {{ __('Inject logic fragments into the engine for architectural audit.') }}
                        </p>
                    </div>
                    
                    <x-ui.button type="button" variant="primary" wire:click="addFile" class="shrink-0 group shadow-lg shadow-primary/20">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            {{ __('Add Logic Fragment') }}
                        </span>
                    </x-ui.button>
                </div>


                <!-- Master Portal Link-up -->
                <div 
                    x-data="{ 
                        dragging: false,
                        processFiles(files) {
                            let filesData = [];
                            let processed = 0;
                            files.forEach(file => {
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    filesData.push({ name: file.name, content: e.target.result });
                                    processed++;
                                    if (processed === files.length) {
                                        $wire.importMultipleFiles(filesData);
                                    }
                                };
                                reader.readAsText(file);
                            });
                        }
                    }"
                    @dragover.prevent="dragging = true"
                    @dragleave.prevent="dragging = false"
                    @drop.prevent="dragging = false; const files = [...$event.dataTransfer.files]; if(files.length > 0) processFiles(files)"
                    @click="$refs.fileInput.click()"
                    :class="dragging ? 'border-primary bg-primary/5 scale-[1.01]' : 'border-outline-variant/10 bg-surface-low hover:border-primary/20'"
                    class="relative border-2 border-dashed rounded-round-4 p-12 transition-all duration-700 group/drop cursor-pointer"
                >
                    <input type="file" x-ref="fileInput" class="hidden" multiple @change="const files = [...$event.target.files]; if(files.length > 0) processFiles(files)">
                    
                    <!-- Background Pulser -->
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-transparent to-transparent opacity-50"></div>
                    
                    <div class="relative z-10 flex flex-col items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-surface-high flex items-center justify-center border border-outline-variant/10 group-hover/drop:scale-110 group-hover/drop:rotate-12 transition-all duration-500">
                             <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        </div>
                        <div class="space-y-1">
                            <span class="font-display font-bold text-lg text-on-surface uppercase tracking-wider">{{ __('Logic Portal') }}</span>
                            <p class="text-xs text-on-surface-variant font-mono uppercase tracking-widest opacity-60">{{ __('Drop multiple files to generate fragments instantly.') }}</p>
                        </div>
                    </div>
                </div>



                <!-- Fragments Stack -->
                <div class="space-y-12 mt-12">
                    @forelse($files as $index => $file)
                        @php $stats = $this->getFileStats($index); @endphp
                        <div 
                            data-file-fragment 
                            wire:key="frag-{{ $file['id'] }}"
                            x-data="{ focused: {{ $index === 0 ? 'true' : 'false' }} }"
                            class="relative group animate-in fade-in slide-in-from-bottom-8 duration-700"
                            style="animation-delay: {{ $loop->index * 100 }}ms"
                        >
                            <!-- Vertical Index Marker -->
                            <div class="absolute -left-12 top-0 bottom-0 w-8 hidden xl:flex flex-col items-center py-6 gap-2">
                                <div class="w-px flex-1 bg-gradient-to-b from-transparent via-outline-variant/20 to-transparent"></div>
                                <span class="font-mono text-[10px] text-on-surface-variant/40 font-bold rotate-180 [writing-mode:vertical-lr] tracking-[0.2em]">{{ __('FRAGMENT') }} {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <div class="w-px flex-1 bg-gradient-to-b from-transparent via-outline-variant/20 to-transparent"></div>
                            </div>

                            <div class="glass-panel rounded-round-4 overflow-hidden border border-outline-variant/10 hover:border-primary/40 transition-all duration-700 shadow-2xl {{ ($file['is_duplicate'] ?? false) || ($file['is_content_duplicate'] ?? false) ? 'ring-1 ring-secondary/40 border-secondary/20' : '' }}">
                                <!-- HUD Header -->
                                <div @click="focused = !focused" class="cursor-pointer px-6 py-4 bg-surface-container-low/80 flex items-center justify-between border-b border-outline-variant/10 gap-6 hover:bg-surface-high transition-colors">

                                    <div class="flex items-center gap-6 flex-1">
                                        <!-- Order Controls -->
                                        <div class="flex flex-col gap-1" @click.stop>

                                            <button type="button" wire:click="moveUp({{ $index }}); if(window.fx) window.fx.play('click')" 
                                                class="p-1.5 rounded-round-1 hover:bg-primary/20 hover:text-primary transition-all text-on-surface-variant/40 disabled:opacity-0"
                                                @if($index == 0) disabled @endif
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"/></svg>
                                            </button>
                                            <button type="button" wire:click="moveDown({{ $index }}); if(window.fx) window.fx.play('click')" 
                                                class="p-1.5 rounded-round-1 hover:bg-primary/20 hover:text-primary transition-all text-on-surface-variant/40 disabled:opacity-0"
                                                @if($index == count($files) - 1) disabled @endif
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                                            </button>
                                        </div>

                                        <!-- Title Identity -->
                                        <div class="flex-1 min-w-0 group/identity" @click.stop>
                                            <input 
                                                type="text" 
                                                wire:model.live="files.{{ $index }}.name" 
                                                placeholder="{{ __('Untitled_Source.rme') }}"
                                                class="bg-transparent border-none p-0 w-full font-display text-xl font-bold text-on-surface placeholder:text-on-surface-variant/30 focus:ring-0 truncate transition-all group-hover/identity:text-primary"
                                            >


                                            <div class="flex items-center gap-3 mt-1">
                                                <span class="text-[9px] font-mono text-on-surface-variant uppercase tracking-widest font-bold opacity-60">{{ __('Detected Engine') }}: <span class="text-primary">{{ $file['language'] }}</span></span>
                                                @if($file['is_duplicate'] ?? false)
                                                    <span class="text-[8px] bg-secondary/10 text-secondary border border-secondary/20 px-2 py-0.5 rounded-full font-bold uppercase tracking-tighter">{{ __('Name Collision') }}</span>
                                                @endif
                                                @if($file['is_content_duplicate'] ?? false)
                                                    <span class="text-[8px] bg-secondary/10 text-secondary border border-secondary/20 px-2 py-0.5 rounded-full font-bold uppercase tracking-tighter">{{ __('Logic Clone') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Fragment Stats HUD -->
                                    <div class="hidden md:flex items-center gap-8 self-stretch border-x border-outline-variant/10 px-8">
                                        <div class="flex flex-col">
                                            <span class="text-[8px] text-on-surface-variant uppercase font-bold tracking-widest opacity-40">{{ __('Volume') }}</span>
                                            <span class="font-mono text-sm text-on-surface group-hover:text-primary transition-colors">{{ $stats['lines'] }} <span class="text-[10px] opacity-40">lns</span></span>
                                        </div>
                                        <div class="flex flex-col border-l border-outline-variant/10 pl-6">
                                            <span class="text-[8px] text-on-surface-variant uppercase font-bold tracking-widest opacity-40">{{ __('Payload') }}</span>
                                            <span class="font-mono text-sm text-on-surface">{{ $stats['size'] }}</span>
                                        </div>
                                    </div>


                                    <div class="flex items-center gap-2" @click.stop>
                                        <button type="button" @click="focused = !focused" class="w-10 h-10 rounded-full hover:bg-white/5 flex items-center justify-center text-on-surface-variant hover:text-on-surface transition-all">
                                            <svg class="w-5 h-5 transition-transform duration-500" :class="{ 'rotate-180': focused }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <button type="button" wire:click="removeFile({{ $index }})" class="w-10 h-10 rounded-full hover:bg-secondary/10 flex items-center justify-center text-on-surface-variant hover:text-secondary transition-all">
                                            <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>

                                </div>

                                <!-- Editor Interface -->
                                <div x-show="focused" x-collapse x-cloak class="p-8 space-y-8 animate-in fade-in zoom-in-95 duration-500">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                                        <div class="md:col-span-8 space-y-4">
                                            <div class="flex justify-between items-center px-1">
                                                <label class="text-[10px] font-mono text-primary uppercase tracking-widest font-black flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                                                    {{ __('File Content') }}
                                                </label>

                                            </div>
                                            <div class="relative group/editor rounded-round-4 overflow-hidden border border-outline-variant/10 focus-within:border-primary/50 transition-all bg-surface-lowest flex">
                                                <!-- Line Numbers Side Gutter -->
                                                <div class="w-12 bg-surface-highest/20 border-r border-outline-variant/10 py-6 flex flex-col items-center font-mono text-[10px] text-on-surface-variant/20 select-none">
                                                    @for($i = 1; $i <= max(14, $stats['lines']); $i++)
                                                       <div class="h-6 leading-6">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</div>
                                                    @endfor
                                                </div>

                                                <textarea 
                                                    wire:model.blur="files.{{ $index }}.content" 
                                                    rows="14" 
                                                    @class([
                                                        "block w-full bg-transparent p-6 font-mono text-sm leading-6 placeholder:text-primary/5 border-none focus:ring-0 resize-none selection:bg-primary selection:text-on-primary",
                                                        "text-amber-400" => in_array($file['language'], ['php', 'python', 'ruby', 'java', 'csharp']),
                                                        "text-blue-400" => in_array($file['language'], ['javascript', 'typescript', 'vue', 'react']),
                                                        "text-emerald-400" => in_array($file['language'], ['go', 'rust', 'c', 'cpp']),
                                                        "text-primary" => !in_array($file['language'], ['php', 'python', 'ruby', 'java', 'csharp', 'javascript', 'typescript', 'vue', 'react', 'go', 'rust', 'c', 'cpp'])
                                                    ])
                                                    placeholder="{{ __('Inject source logic here...') }}"
                                                ></textarea>
                                            </div>


                                        </div>

                                        <div class="md:col-span-4 space-y-8">
                                            <!-- Engine Selector -->
                                            <div class="space-y-3">
                                                <label class="text-[10px] text-on-surface-variant uppercase tracking-widest font-black px-1">{{ __('File Language') }}</label>

                                                <select 
                                                    wire:model.live="files.{{ $index }}.language"
                                                    wire:key="lang-{{ $index }}-{{ $file['language'] }}"
                                                    class="w-full bg-surface-high rounded-round-4 h-12 px-5 text-on-surface font-mono text-xs uppercase tracking-[0.2em] border border-outline-variant/5 focus:ring-1 focus:ring-primary/50 appearance-none cursor-pointer hover:bg-surface-highest transition-all"
                                                >
                                                    @foreach($this->getSupportedLanguages() as $lang)
                                                        <option value="{{ $lang }}">{{ $lang }}</option>
                                                    @endforeach
                                                </select>
                                                <x-input-error :messages="$errors->get('files.' . $index . '.language')" />
                                            </div>

                                            <!-- Metadata Note -->
                                            <div class="space-y-3">
                                                <label class="text-[10px] text-on-surface-variant uppercase tracking-widest font-black px-1">{{ __('File Description') }}</label>

                                                <textarea 
                                                    wire:model="files.{{ $index }}.description" 
                                                    rows="6" 
                                                    class="w-full bg-surface-high border border-outline-variant/5 text-on-surface-variant placeholder:text-on-surface-variant/20 rounded-round-3 p-5 text-sm font-editorial leading-relaxed resize-none focus:ring-1 focus:ring-primary/40" 
                                                    placeholder="{{ __('Describe the specific challenges or architectural nuances of this fragment...') }}"
                                                ></textarea>
                                            </div>


                                        </div>
                                    </div>
                                    
                                    @error('files.'.$index.'.name') <span class="text-xs text-secondary font-bold font-mono uppercase">{{ $message }}</span> @enderror
                                    @error('files.'.$index.'.content') <span class="text-xs text-secondary font-bold font-mono uppercase">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-24 flex flex-col items-center justify-center text-center space-y-6">
                            <div class="w-12 h-12 rounded-full border border-dashed border-outline-variant/40 flex items-center justify-center">
                                <div class="w-2 h-2 rounded-full bg-outline-variant/40 animate-ping"></div>
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-on-surface font-display text-xl font-bold uppercase tracking-widest">{{ __('Zero Artefacts Detected') }}</h4>
                                <p class="text-on-surface-variant italic">{{ __('The curation engine awaits your initial logic injection.') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        @elseif($step == 3)
            <!-- Step 3: Distribution & Focus -->
            <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="space-y-2">
                    <h2 class="font-display text-3xl font-bold text-on-surface tracking-tight">{{ __('Curation Distribution') }}</h2>
                    <p class="text-on-surface-variant italic">{{ __('Select multiple focus areas for the specialized curation engine.') }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-in fade-in slide-in-from-bottom-12 duration-1000 fill-mode-both">
                    @foreach([
                        'logic' => ['label' => __('Logic & Core'), 'color' => 'logic'], 
                        'beauty' => ['label' => __('Visual Beauty'), 'color' => 'beauty'], 
                        'opti' => ['label' => __('Optimization'), 'color' => 'opti']
                    ] as $key => $meta)
                        <div 
                            wire:click="toggleLens('{{ $key }}')"
                            @class([
                                "cursor-pointer group flex flex-col h-full relative border-2 p-8 rounded-round-4 transition-all duration-500 overflow-hidden",
                                "bg-surface-low border-outline-variant/10 hover:bg-surface-high" => !in_array($key, $selectedLens)
                            ])
                            style="{{ in_array($key, $selectedLens) ? 'background-color: rgba(var(--lens-'.$meta['color'].'-rgb), 0.1); border-color: var(--lens-'.$meta['color'].'); box-shadow: 0 0 30px rgba(var(--lens-'.$meta['color'].'-rgb), 0.1);' : '' }}"
                        >
                            <!-- Checkmark Overlay -->
                            <div 
                                @class([
                                    "absolute top-4 right-4 w-6 h-6 rounded-full border flex items-center justify-center transition-all duration-500",
                                    "border-transparent scale-110" => in_array($key, $selectedLens),
                                    "border-outline-variant/20" => !in_array($key, $selectedLens)
                                ])
                                style="{{ in_array($key, $selectedLens) ? 'background-color: var(--lens-'.$meta['color'].');' : '' }}"
                            >
                                <svg @class(["w-4 h-4 text-black transition-opacity", "opacity-100" => in_array($key, $selectedLens), "opacity-0" => !in_array($key, $selectedLens)]) fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                            </div>

                            <div 
                                @class([
                                    "w-12 h-12 rounded-round-4 flex items-center justify-center mb-6 font-display font-black text-xl transition-all duration-500",
                                    "bg-surface-highest/20 text-on-surface-variant group-hover:bg-primary/20 group-hover:text-primary" => !in_array($key, $selectedLens)
                                ])
                                style="{{ in_array($key, $selectedLens) ? 'background-color: var(--lens-'.$meta['color'].'); color: black;' : '' }}"
                            >
                                {{ strtoupper(substr($key, 0, 1)) }}
                            </div>
                            
                            <h4 @class([
                                "font-display font-black text-lg mb-2 transition-colors",
                                "text-on-surface" => !in_array($key, $selectedLens)
                            ])
                            style="{{ in_array($key, $selectedLens) ? 'color: var(--lens-'.$meta['color'].');' : '' }}"
                            >{{ $meta['label'] }}</h4>
                            <p class="text-on-surface-variant text-sm opacity-60 flex-grow leading-relaxed">{{ __('Activate the specialized lenses for this artifact.') }}</p>
                        </div>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('selectedLens')" class="mt-4" />




                <div class="pt-8 border-t border-outline-variant/10 space-y-6">
                    <div>
                        <x-input-label :value="__('Global Distribution Scope')" />
                        <div class="flex gap-8 mt-4">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" wire:model.live="is_public" class="w-5 h-5 rounded bg-surface-high border-outline-variant/20 text-primary focus:ring-primary/50">
                                <span @class(['text-sm font-medium transition-colors', 'text-primary' => $is_public, 'text-on-surface-variant group-hover:text-on-surface' => !$is_public])>
                                    {{ __('Public Network') }}
                                </span>
                            </label>

                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" wire:model.live="is_private" class="w-5 h-5 rounded bg-surface-high border-outline-variant/20 text-primary focus:ring-primary/50">
                                <span @class(['text-sm font-medium transition-colors', 'text-primary' => $is_private, 'text-on-surface-variant group-hover:text-on-surface' => !$is_private])>
                                    {{ __('Private Lab (Private Group)') }}
                                </span>
                            </label>
                        </div>
                    </div>


                    @if($is_private)

                        <div class="space-y-4 animate-in fade-in slide-in-from-top-4 duration-500">
                            <x-input-label :value="__('Select Target Lab Member')" />
                            <div class="relative">
                                <x-text-input wire:model.live="groupSearch" placeholder="{{ __('Search for a Lab group...') }}" class="pl-12" />
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                                @forelse($this->groups as $group)
                                    <label class="cursor-pointer group">
                                        <input type="radio" wire:model="groupId" value="{{ $group->id }}" class="hidden peer">
                                        <div class="flex items-center gap-4 p-4 rounded-round-4 bg-surface-high border border-outline-variant/10 transition-all peer-checked:bg-primary/10 peer-checked:border-primary hover:bg-surface-highest">
                                            <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold">
                                                {{ substr($group->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-on-surface">{{ $group->name }}</div>
                                                <div class="text-[10px] text-on-surface-variant uppercase tracking-tighter">{{ trans_choice('{1} 1 Member|[2,*] :count Members', $group->members_count ?? $group->members()->count()) }}</div>
                                            </div>
                                        </div>
                                    </label>
                                @empty
                                    <div class="col-span-2 py-4 text-center text-on-surface-variant italic text-sm">
                                        {{ __('No Labs found for this identity.') }}
                                    </div>
                                @endforelse
                            @error('groupId') <span class="text-xs text-secondary font-bold font-mono">{{ $message }}</span> @enderror
                        </div>
                    @endif

                </div>
            </div>
        @endif

        <!-- Footer Actions -->
        <div class="mt-16 pt-8 border-t border-outline-variant/10 flex justify-between items-center">
            @if($step > 1)
                <x-ui.button type="button" variant="ghost" wire:click="prevStep" class="group">
                    <span class="flex items-center gap-2 group-hover:-translate-x-1 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                        {{ __('Regress Phase') }}
                    </span>
                </x-ui.button>
            @else
                <div></div>
            @endif

            <div class="flex items-center gap-4 text-on-surface-variant text-[10px] uppercase tracking-widest font-bold">
                {{ __('Phase') }} {{ $step }} / 3
            </div>

            @if($step < 3)
                <x-ui.button type="button" variant="primary" wire:click="nextStep" wire:loading.attr="disabled" wire:target="nextStep" class="group px-8">
                    <span class="flex items-center gap-2 group-hover:translate-x-1 transition-transform">
                        {{ __('Next Phase') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                    </span>
                </x-ui.button>
            @else
                <x-ui.button type="submit" variant="secondary" wire:loading.attr="disabled" wire:target="submit" class="px-10 py-4 shadow-[0_0_30px_rgba(78,222,163,0.2)] hover:scale-105 transition-all">
                    <span class="flex items-center gap-3">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                         {{ __('Deploy Curation Artifact') }}
                    </span>
                </x-ui.button>
            @endif

        </div>
    </form>
</div>

