<div 
    class="max-w-4xl mx-auto px-6 py-12"
    x-data="{ 
        step: @entangle('step'),
        init() {
            this.$watch('step', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    }"
>
    <!-- Back Button -->
    <div class="mb-4">
        <x-ui.back-button fallback="{{ route('dashboard') }}" />
    </div>

    <style>[x-cloak] { display: none !important; }</style>
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
                        {{ $s == 1 ? __('Details') : ($s == 2 ? __("Files & Code") : __('Publication')) }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>



    <form wire:submit.prevent="submit" class="relative">
        <!-- Step 1: Introspection -->
        <div 
            x-show="step == 1"
            x-transition:enter="transition ease-out duration-500 delay-200"
            x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300 absolute inset-x-0"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-8"
        >
            <div class="space-y-4 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="space-y-2">
                    <h2 class="font-display text-3xl font-bold text-on-surface tracking-tight">{{ __('Post Details') }} <span class="text-red-500">*</span></h2>
                    <p class="text-on-surface-variant italic">{{ __('Define the goal and scope of this code review.') }}</p>
                </div>


                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label :value="__('Post Title') . ' *'" />
                            <x-text-input wire:model="title" placeholder="{{ __('e.g., Memory Optimizer v2.0 with Lock-Free Algorithms') }}" />
                            <x-input-error :messages="$errors->get('title')" />
                        </div>
                        <div>
                            <x-input-label :value="__('Short Summary')" />
                            <x-text-input wire:model="short_description" placeholder="{{ __('Hook the reviewers... e.g., \'Refactoring the cache invalidation logic for 40% less CPU cycles.\'') }}" />
                            <x-input-error :messages="$errors->get('short_description')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label :value="__('Target Review Goals')" />
                            <textarea wire:model="review_goals" rows="3" class="bg-surface-high border-none text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/50 rounded-round-4 transition-all duration-300 w-full resize-none p-4" placeholder="{{ __('Specific algorithms or patterns to audit... E.g., \'Verify the thread-safety of the singleton implementation.\'') }}"></textarea>
                            <x-input-error :messages="$errors->get('review_goals')" />
                        </div>
                        <div>
                            <x-input-label :value="__('Improvement Desires')" />
                            <textarea wire:model="improvement_goals" rows="3" class="bg-surface-high border-none text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/50 rounded-round-4 transition-all duration-300 w-full resize-none p-4" placeholder="{{ __('Desired outcomes... E.g., \'I want to transition from a monolithic handler to a modular strategy pattern.\'') }}"></textarea>
                            <x-input-error :messages="$errors->get('improvement_goals')" />
                        </div>
                    </div>


                    <div>
                        <x-input-label :value="__('Technical Description')" />
                        <textarea wire:model="description" rows="5" class="bg-surface-high border-none text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/50 rounded-round-4 transition-all duration-300 w-full resize-none p-4" placeholder="{{ __('Architectural deep dive... Describe the context, dependencies, and complex interactions for the reviewers.') }}"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Artifacts (The Digital Logic) -->
        <div 
            x-show="step == 2"
            x-transition:enter="transition ease-out duration-500 delay-200"
            x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300 absolute inset-x-0"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-8"
            x-cloak
        >
            <div class="space-y-12 animate-in fade-in slide-in-from-bottom-8 duration-700">
                <div class="sticky top-24 z-40 flex flex-col md:flex-row justify-between items-start md:items-end gap-6 bg-surface-lowest/95 backdrop-blur-2xl p-6 -mx-6 rounded-b-round-4 border-b border-outline-variant/20 shadow-[0_20px_50px_-10px_rgba(0,0,0,0.5)] transition-all duration-500">


                    <div class="space-y-4 max-w-2xl">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-round-2 bg-primary/20 flex items-center justify-center text-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <h2 class="font-display text-2xl font-bold text-on-surface tracking-tight">{{ __('Source Files') }}</h2>
                        </div>
                        <p class="text-[10px] text-on-surface-variant font-mono uppercase tracking-widest opacity-60">
                            {{ __('Add your code files to be reviewed.') }}
                        </p>
                    </div>
                    
                    <x-ui.button type="button" variant="primary" wire:click="addFile" class="shrink-0 group shadow-lg shadow-primary/20">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            {{ __('Add File') }}
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
                            <span class="font-display font-bold text-lg text-on-surface uppercase tracking-wider">{{ __('File Upload') }}</span>
                            <p class="text-xs text-on-surface-variant font-mono uppercase tracking-widest opacity-60">{{ __('Drop multiple files to import them instantly.') }}</p>
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
                                <span class="font-mono text-[10px] text-on-surface-variant/40 font-bold rotate-180 [writing-mode:vertical-lr] tracking-[0.2em]">{{ __('FILE') }} {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
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
                                                placeholder="{{ __('untitled_source.rme') }}"
                                                class="bg-transparent border-none p-0 w-full font-display text-xl font-bold text-on-surface placeholder:text-on-surface-variant/30 focus:ring-0 truncate transition-all group-hover/identity:text-primary"
                                            >


                                            <div class="flex items-center gap-3 mt-1">
                                                <span class="text-[9px] font-mono text-on-surface-variant uppercase tracking-widest font-bold opacity-60">{{ __('Detected Language') }}: <span class="text-primary">{{ $file['language'] }}</span></span>
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
                                            <span class="font-mono text-sm text-on-surface group-hover:text-primary transition-colors">{{ $stats['lines'] }} <span class="text-[10px] opacity-40">{{ __('lns') }}</span></span>
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
                                                    placeholder="{{ __('Paste your code here...') }}"
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
                                <h4 class="text-on-surface font-display text-xl font-bold uppercase tracking-widest">{{ __('No Files Found') }}</h4>
                                <p class="text-on-surface-variant italic">{{ __('The editor awaits your code.') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Step 3: Distribution & Focus -->
        <div 
            x-show="step == 3"
            x-transition:enter="transition ease-out duration-500 delay-200"
            x-transition:enter-start="opacity-0 translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300 absolute inset-x-0"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-8"
            x-cloak
        >
            <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="space-y-2">
                            <h2 class="font-display text-3xl font-bold text-on-surface tracking-tight">{{ __('Focus Areas') }}</h2>
                            <p class="text-on-surface-variant italic">{{ __('Select the main areas you want the community to focus on.') }}</p>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-primary">{{ __('Limit: 3 Maximum') }}</span>
                            <div class="w-12 h-0.5 bg-primary/20 mt-1"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 animate-in fade-in slide-in-from-bottom-12 duration-1000 fill-mode-both">
                        @foreach([
                            'logic' => ['label' => __('Logic & Core'), 'color' => 'logic', 'desc' => __('Deep dive into algorithms and program flow.')], 
                            'beauty' => ['label' => __('Visual Beauty'), 'color' => 'beauty', 'desc' => __('Refine the UI/UX and stylistic precision.')], 
                            'opti' => ['label' => __('Performance'), 'color' => 'opti', 'desc' => __('Hunt for O(n^2) and memory leaks.')],
                            'security' => ['label' => __('Security Audit'), 'color' => 'security', 'desc' => __('Scan for CVEs, XSS and injection risks.')],
                            'architecture' => ['label' => __('Architecture'), 'color' => 'architecture', 'desc' => __('Patterns, modularity and scalability.')],
                            'infrastructure' => ['label' => __('Infrastructure'), 'color' => 'infrastructure', 'desc' => __('DevOps, Docker and Deployment logic.')]
                        ] as $key => $meta)
                            <div 
                                wire:click="toggleLens('{{ $key }}')"
                                @class([
                                    "cursor-pointer group flex flex-col h-full relative border-2 p-8 rounded-round-4 transition-all duration-500 overflow-hidden",
                                    "bg-surface-low border-outline-variant/10 hover:bg-surface-high hover:scale-105" => !in_array($key, $selectedLens)
                                ])
                                style="{{ in_array($key, $selectedLens) ? 'background-color: rgba(var(--lens-'.$meta['color'].'-rgb), 0.1); border-color: var(--lens-'.$meta['color'].'); box-shadow: 0 0 30px rgba(var(--lens-'.$meta['color'].'-rgb), 0.1); transform: scale(1.05);' : '' }}"
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
                                <p class="text-on-surface-variant text-sm opacity-60 flex-grow leading-relaxed">{{ $meta['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <x-input-error :messages="$errors->get('selectedLens')" class="mt-4" />



                <div class="pt-12 border-t border-outline-variant/10 space-y-16">
                    <!-- Streamlined Visibility Slider (Feed Style) -->
                    <div class="flex flex-col items-center gap-6">
                        <span class="text-[10px] font-black uppercase tracking-[0.4em] text-on-surface-variant/40">{{ __('Distribution Mode') }}</span>
                        
                        <div class="flex items-center gap-2 bg-black/20 backdrop-blur-3xl rounded-[1.5rem] p-1.5 border border-white/5 shadow-2xl">
                            <button 
                                type="button"
                                wire:click="setVisibility('public')"
                                @class([
                                    'px-8 py-3 rounded-2xl text-[9px] font-black uppercase tracking-[0.2em] transition-all duration-500', 
                                    'bg-primary text-on-primary shadow-[0_0_30px_rgba(190,194,255,0.3)] scale-105' => $is_public, 
                                    'text-on-surface-variant/40 hover:text-white hover:bg-white/5' => !$is_public
                                ])
                            >
                                {{ __('Public Feed') }}
                            </button>
                            <button 
                                type="button"
                                wire:click="setVisibility('group')"
                                @class([
                                    'px-8 py-3 rounded-2xl text-[9px] font-black uppercase tracking-[0.2em] transition-all duration-500', 
                                    'bg-primary text-on-primary shadow-[0_0_30px_rgba(190,194,255,0.3)] scale-105' => $is_private, 
                                    'text-on-surface-variant/40 hover:text-white hover:bg-white/5' => !$is_private
                                ])
                            >
                                {{ __('Private Group') }}
                            </button>
                        </div>
                    </div>


                    @if($is_private)
                        <div class="space-y-10 animate-in fade-in slide-in-from-bottom-8 duration-700">
                            <div class="flex items-end justify-between border-b border-outline-variant/10 pb-6">
                                <div class="space-y-2">
                                    <h3 class="font-display text-3xl font-black text-on-surface tracking-tight">{{ __('Select Target Group') }}</h3>
                                    <p class="text-on-surface-variant text-sm italic">{{ __('Your review will be exclusively visible to the selected cluster.') }}</p>
                                </div>
                                <x-ui.search-input 
                                    model="groupSearch" 
                                    placeholder="{{ __('Lookup technical modules...') }}" 
                                    class="!w-[280px]"
                                />
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                @forelse($this->groups as $group)
                                    <div 
                                        wire:click="$set('groupId', {{ $group->id }})"
                                        @class([
                                            "cursor-pointer group flex flex-col h-full relative border-2 p-8 rounded-round-4 transition-all duration-500 overflow-hidden",
                                            "bg-surface-low border-outline-variant/10 hover:bg-surface-high hover:scale-105" => $groupId != $group->id
                                        ])
                                        style="{{ $groupId == $group->id ? 'background-color: rgba(var(--primary-rgb), 0.1); border-color: var(--primary); box-shadow: 0 0 30px rgba(var(--primary-rgb), 0.1); transform: scale(1.05);' : '' }}"
                                    >
                                        <!-- Selection Indicator -->
                                        <div 
                                            @class([
                                                "absolute top-4 right-4 w-6 h-6 rounded-full border flex items-center justify-center transition-all duration-500",
                                                "bg-primary border-transparent scale-110" => $groupId == $group->id,
                                                "border-outline-variant/20" => $groupId != $group->id
                                            ])
                                        >
                                            <svg @class(["w-4 h-4 text-black transition-opacity", "opacity-100" => $groupId == $group->id, "opacity-0" => $groupId != $group->id]) fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                                        </div>

                                        <div 
                                            @class([
                                                "w-14 h-14 rounded-2xl flex items-center justify-center mb-6 font-display font-black text-2xl transition-all duration-500 shadow-inner overflow-hidden",
                                                "bg-white/5 text-on-surface-variant group-hover:bg-primary/20 group-hover:text-primary" => $groupId != $group->id,
                                                "bg-primary text-black border-2 border-primary" => $groupId == $group->id
                                            ])
                                        >
                                            @if($group->logo_path)
                                                <img src="{{ Storage::url($group->logo_path) }}" class="w-full h-full object-cover" alt="{{ $group->name }}">
                                            @else
                                                {{ strtoupper(substr($group->name, 0, 1)) }}
                                            @endif
                                        </div>
                                        
                                        <h4 @class([
                                            "font-display font-black text-xl mb-1 transition-colors uppercase tracking-tight",
                                            "text-on-surface" => $groupId != $group->id,
                                            "text-primary" => $groupId == $group->id
                                        ])>{{ $group->name }}</h4>
                                        <div class="text-[10px] text-on-surface-variant font-mono uppercase tracking-widest opacity-60">
                                            {{ trans_choice('{1} 1 Member|[2,*] :count Members', $group->members_count ?? $group->members()->count()) }}
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full py-16 text-center rounded-round-4 border border-dashed border-white/5 bg-white/[0.01]">
                                        <div class="text-on-surface-variant italic text-sm font-editorial opacity-60">{{ __('No technical clusters found for this identity.') }}</div>
                                    </div>
                                @endforelse
                            </div>
                            @error('groupId') <span class="text-xs text-secondary font-black font-mono uppercase tracking-widest text-center block mt-6">{{ $message }}</span> @enderror
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="mt-16 pt-8 border-t border-outline-variant/10 flex justify-between items-center">
            @if($step > 1)
                <x-ui.button type="button" variant="ghost" wire:click="prevStep" class="group">
                    <span class="flex items-center gap-2 group-hover:-translate-x-1 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                        {{ __('Previous') }}
                    </span>
                </x-ui.button>
            @else
                <div></div>
            @endif

            <div class="flex items-center gap-4 text-on-surface-variant text-[10px] uppercase tracking-widest font-bold">
                {{ __('Step') }} {{ $step }} / 3
            </div>

            @if($step < 3)
                <x-ui.button type="button" variant="primary" wire:click="nextStep" wire:loading.attr="disabled" wire:target="nextStep" class="group px-8">
                    <span class="flex items-center gap-2 group-hover:translate-x-1 transition-transform">
                        {{ __('Next') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                    </span>
                </x-ui.button>
            @else
                <x-ui.button type="submit" variant="secondary" wire:loading.attr="disabled" wire:target="submit" class="px-10 py-4 shadow-[0_0_30px_rgba(78,222,163,0.2)] hover:scale-105 transition-all">
                    <span class="flex items-center gap-3">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                         {{ __('Publish Artifact') }}
                    </span>
                </x-ui.button>
            @endif

        </div>
    </form>
</div>

