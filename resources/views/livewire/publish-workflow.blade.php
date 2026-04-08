<div class="max-w-4xl mx-auto px-6 py-12">
    <!-- Stepper Navigation -->
    <div class="flex items-center justify-between mb-16 relative">
        <div class="absolute top-1/2 left-0 w-full h-0.5 bg-surface-highest -translate-y-1/2 z-0"></div>
        @foreach([1, 2, 3] as $s)
            <div class="relative z-10 flex flex-col items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-display font-bold transition-all duration-500 {{ $step >= $s ? 'bg-primary text-on-primary ring-4 ring-primary/20' : 'bg-surface-high text-on-surface-variant' }}">
                    {{ $s }}
                </div>
                <span class="text-[10px] uppercase tracking-widest font-bold {{ $step >= $s ? 'text-on-surface' : 'text-on-surface-variant' }}">
                    {{ $s == 1 ? __('Introspection') : ($s == 2 ? __("The Artifacts") : __('Distribution')) }}
                </span>
            </div>
        @endforeach
    </div>

    <form wire:submit.prevent="submit">
        @if($step == 1)
            <!-- Step 1: Introspection -->
            <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="space-y-2">
                    <h2 class="font-display text-3xl font-bold text-on-surface tracking-tight">{{ __('Contextual Audit') }} <span class="text-secondary">*</span></h2>
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
                            <x-input-label :value="__('Short Summary') . ' *'" />
                            <x-text-input wire:model="short_description" placeholder="{{ __('Briefly hook the curators...') }}" />
                            <x-input-error :messages="$errors->get('short_description')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label :value="__('Target Review Goals') . ' *'" />
                            <textarea wire:model="review_goals" rows="3" class="bg-surface-high border-none text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/50 rounded-round-4 transition-all duration-300 w-full resize-none p-4" placeholder="{{ __('What logic should be audited?') }}"></textarea>
                            <x-input-error :messages="$errors->get('review_goals')" />
                        </div>
                        <div>
                            <x-input-label :value="__('Improvement Desires') . ' *'" />
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
            <!-- Step 2: Artifacts (Files) -->
            <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="flex justify-between items-end">
                    <div class="space-y-2">
                        <h2 class="font-display text-3xl font-bold text-on-surface tracking-tight">{{ __('The Artifacts') }}</h2>
                        <p class="text-on-surface-variant italic">{{ __('Input the files you wish to have curated. Drag & drop files directly into code blocks or use the master zone.') }}</p>
                    </div>
                    <div class="flex gap-4">
                        <x-ui.button type="button" variant="ghost" wire:click="addFile" size="sm">+ {{ __('Add Fragment') }}</x-ui.button>
                    </div>
                </div>

                <!-- Master Drop Zone -->
                <div 
                    x-data="{ active: false }"
                    @dragover.prevent="active = true"
                    @dragleave.prevent="active = false"
                    @drop.prevent="
                        active = false;
                        const files = Array.from($event.dataTransfer.files);
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
                    "
                    class="relative h-24 border-2 border-dashed border-outline-variant/20 rounded-round-4 flex flex-col items-center justify-center transition-all duration-300 group hover:border-primary/40 hover:bg-primary/5"
                    :class="{ 'border-primary bg-primary/10 scale-[1.01]': active }"
                >
                    <div class="flex items-center gap-3 text-on-surface-variant group-hover:text-primary transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        <span class="font-display font-bold uppercase tracking-widest text-xs">{{ __('Master Import Zone') }}</span>
                    </div>
                    <span class="text-[10px] text-on-surface-variant/50 mt-1">{{ __('Drop all files here to automate fragment generation') }}</span>
                </div>

                <div class="space-y-6">
                <div 
                    class="space-y-4"
                    x-data="{ 
                        draggingIndex: null,
                        dragOverGap: null,
                        autoScrollInterval: null,
                        handleDrop(fromIndex, toIndex) {
                            if (fromIndex === null) return;
                            // toIndex est ici la position cible
                            const order = Array.from({length: document.querySelectorAll('[wire\\:id]').length ? {{ count($files) }} : 0}, (_, i) => i);
                            const [movedItem] = order.splice(fromIndex, 1);
                            
                            // Si on déplace vers le bas, l'indice cible doit être ajusté car le retrait a décalé les suivants
                            let target = toIndex;
                            if (fromIndex < toIndex) target--;
                            
                            order.splice(target, 0, movedItem);
                            $wire.reorderFiles(order);
                            this.draggingIndex = null;
                            this.dragOverGap = null;
                        },
                        startAutoScroll(e) {
                            if (this.autoScrollInterval) return;
                            this.autoScrollInterval = setInterval(() => {
                                if (this.draggingIndex === null) {
                                    clearInterval(this.autoScrollInterval);
                                    this.autoScrollInterval = null;
                                    return;
                                }
                                const threshold = 100;
                                if (window.lastY < threshold) window.scrollBy(0, -10);
                                if (window.lastY > window.innerHeight - threshold) window.scrollBy(0, 10);
                            }, 50);
                        }
                    }"
                    @dragover="window.lastY = $event.clientY; startAutoScroll($event)"
                    @dragend="draggingIndex = null; clearInterval(autoScrollInterval); autoScrollInterval = null"
                >
                    @foreach($files as $index => $file)
                        @php $stats = $this->getFileStats($index); @endphp
                        <!-- Drop Gap (Before) -->
                        <div 
                            @dragover.prevent="dragOverGap = {{ $index }}"
                            @dragleave="dragOverGap = null"
                            @drop.prevent="handleDrop(draggingIndex, {{ $index }})"
                            class="h-1 transition-all duration-500 rounded-round-4"
                            :class="{ 'h-16 bg-primary/10 border-2 border-dashed border-primary/40 my-4 scale-[1.02]': dragOverGap === {{ $index }} }"
                        >
                            <div x-show="dragOverGap === {{ $index }}" class="h-full flex items-center justify-center text-primary font-display font-bold text-xs tracking-widest animate-pulse">
                                {{ __('INSERT ARCHIVE CLIP HERE') }}
                            </div>
                        </div>
                        <div 
                            wire:key="file-fragment-{{ $index }}"
                            draggable="true"
                            x-data="{ collapsed: {{ $index > 0 ? 'true' : 'false' }} }"
                            @dragstart="draggingIndex = {{ $index }}; $el.style.opacity = '0.4'"
                            @dragend="draggingIndex = null; $el.style.opacity = '1'"
                            @dragover.prevent
                            @drop.prevent="draggingIndex !== null ? handleDrop(draggingIndex, {{ $index }}) : null"
                            class="transition-all duration-500"
                            :class="{ 'opacity-30 scale-95 blur-sm': draggingIndex !== null && draggingIndex !== {{ $index }} }"
                        >
                            <x-ui.card tonal="container" padding="p-0" class="relative group overflow-hidden border border-outline-variant/10 hover:border-primary/30 transition-all duration-500 {{ ($file['is_duplicate'] ?? false) ? 'border-secondary/50 ring-1 ring-secondary/20' : '' }}">
                                <!-- Header / Drag Handle -->
                                <div class="p-4 bg-surface-high/50 flex items-center justify-between cursor-grab active:cursor-grabbing border-b border-outline-variant/5">
                                    <div class="flex items-center gap-4">
                                        <div class="text-on-surface-variant group-hover:text-primary transition-colors">
                                            <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 8h16M4 16h16"/></svg>
                                        </div>
                                        <div class="flex flex-col">
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">{{ __('Fragment') }} #{{ $index + 1 }}</span>
                                                @if($file['is_duplicate'] ?? false)
                                                    <span class="text-[9px] bg-secondary/20 text-secondary px-2 py-0.5 rounded-full font-bold uppercase tracking-tight">{{ __('Duplicate') }}</span>
                                                @endif
                                            </div>
                                            <span class="text-sm font-medium text-on-surface">{{ $file['name'] ?: __('Untitled_Module') }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <!-- Inline Stats HUD -->
                                        <div class="hidden md:flex items-center gap-4 px-4 border-r border-outline-variant/10">
                                            <div class="flex flex-col items-end">
                                                <span class="text-[9px] text-on-surface-variant/50 uppercase font-bold">{{ __('Lines') }}</span>
                                                <span class="text-xs font-mono text-primary">{{ $stats['lines'] }}</span>
                                            </div>
                                            <div class="flex flex-col items-end">
                                                <span class="text-[9px] text-on-surface-variant/50 uppercase font-bold">{{ __('Weight') }}</span>
                                                <span class="text-xs font-mono text-on-surface">{{ $stats['size'] }}</span>
                                            </div>
                                        </div>

                                    <div class="flex items-center gap-2">
                                        <button type="button" @click="collapsed = !collapsed" class="p-2 text-on-surface-variant hover:text-primary transition-all">
                                            <svg class="w-5 h-5 transition-transform duration-300" :class="{ 'rotate-180': !collapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <button type="button" wire:click="removeFile({{ $index }})" class="p-2 text-on-surface-variant hover:text-secondary transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Content (Collapsible) -->
                                <div x-show="!collapsed && draggingIndex === null" x-collapse x-cloak class="p-6 space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div class="col-span-1 md:col-span-2">
                                            <x-input-label :value="__('Filename') . ' *'" />
                                            <x-text-input wire:model.live="files.{{ $index }}.name" placeholder="core_logic.php" />
                                        </div>
                                        <div>
                                            <x-input-label :value="__('Detected Engine')" />
                                            <select 
                                                wire:model.live="files.{{ $index }}.language"
                                                class="w-full h-[46px] px-4 bg-surface-high rounded-round-4 text-on-surface-variant font-mono text-xs uppercase tracking-widest border border-outline-variant/10 focus:ring-1 focus:ring-primary/50 appearance-none cursor-pointer hover:bg-surface-highest transition-colors"
                                            >
                                                @foreach($this->getSupportedLanguages() as $lang)
                                                    <option value="{{ $lang }}">{{ $lang }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div 
                                        x-data="{ draggingFile: false }"
                                        @dragover.prevent.stop="draggingFile = true"
                                        @dragleave.prevent.stop="draggingFile = false"
                                        @drop.prevent.stop="
                                            draggingFile = false;
                                            const file = $event.dataTransfer.files[0];
                                            if (file) {
                                                const reader = new FileReader();
                                                reader.onload = (e) => {
                                                    $wire.importFile({{ $index }}, file.name, e.target.result);
                                                };
                                                reader.readAsText(file);
                                            }
                                        "
                                        class="relative"
                                    >
                                        <x-input-label :value="__('Logic Body') . ' *'" />
                                        <textarea 
                                            wire:model="files.{{ $index }}.content" 
                                            rows="8" 
                                            class="font-mono bg-surface-lowest border-none text-primary placeholder:text-primary/30 focus:ring-1 focus:ring-primary/50 rounded-round-4 transition-all duration-300 w-full resize-none p-4" 
                                            placeholder="{{ __('Paste or drop code implementation...') }}"
                                            :class="{ 'ring-2 ring-secondary/50': draggingFile }"
                                        ></textarea>
                                        
                                        <div x-show="draggingFile" class="absolute inset-0 bg-secondary/10 border-2 border-dashed border-secondary rounded-round-4 flex items-center justify-center pointer-events-none transition-all duration-300">
                                            <span class="text-secondary font-bold">{{ __('Drop to Import Content') }}</span>
                                        </div>
                                    </div>

                                    <div>
                                        <x-input-label :value="__('Contextual Note (Optional)')" />
                                        <textarea wire:model="files.{{ $index }}.description" rows="2" class="bg-surface-low border-none text-on-surface-variant placeholder:text-on-surface-variant/30 focus:ring-2 focus:ring-primary/30 rounded-round-4 transition-all duration-300 w-full resize-none p-3 text-sm" placeholder="{{ __('What is unique about this file?') }}"></textarea>
                                    </div>
                                </div>
                            </x-ui.card>
                        </div>
                    @endforeach

                    <!-- Final Drop Gap (After Last) -->
                    <div 
                        @dragover.prevent="dragOverGap = {{ count($files) }}"
                        @dragleave="dragOverGap = null"
                        @drop.prevent="handleDrop(draggingIndex, {{ count($files) }})"
                        class="h-1 transition-all duration-300 rounded-round-4"
                        :class="{ 'h-12 bg-primary/10 border-2 border-dashed border-primary/40 my-2': dragOverGap === {{ count($files) }} }"
                    ></div>
                </div>
                </div>
            </div>
        @elseif($step == 3)
            <!-- Step 3: Distribution & Focus -->
            <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="space-y-2">
                    <h2 class="font-display text-3xl font-bold text-on-surface tracking-tight">{{ __('Curation Distribution') }}</h2>
                    <p class="text-on-surface-variant italic">{{ __('Select multiple focus areas for the specialized curation engine.') }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach([
                        'clarity' => ['label' => __('Clarity & Logic'), 'color' => 'primary'], 
                        'performance' => ['label' => __('Optimization'), 'color' => 'secondary'], 
                        'security' => ['label' => __('Threat Audit'), 'color' => 'tertiary']
                    ] as $key => $meta)
                        <label class="cursor-pointer group">
                            <input type="checkbox" wire:model="selectedLens" value="{{ $key }}" class="hidden peer">
                            <div class="h-full border border-outline-variant/10 bg-surface-low p-8 rounded-round-4 transition-all duration-500 peer-checked:bg-{{ $meta['color'] }}/10 peer-checked:border-{{ $meta['color'] }} group-hover:bg-surface-high">
                                <div class="w-12 h-12 rounded-round-4 flex items-center justify-center mb-6 bg-{{ $meta['color'] }}/20 text-{{ $meta['color'] }}">
                                    <span class="font-display font-bold text-xl">{{ strtoupper(substr($key, 0, 1)) }}</span>
                                </div>
                                <h4 class="font-display font-bold text-lg text-on-surface mb-2">{{ $meta['label'] }}</h4>
                                <p class="text-on-surface-variant text-sm">{{ __('Activate the specialized lenses for this artifact.') }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="pt-8 border-t border-outline-variant/10 space-y-6">
                    <div>
                        <x-input-label :value="__('Global Distribution Scope')" />
                        <div class="flex gap-6 mt-4">
                            @foreach(['public' => __('Public Network'), 'private' => __('Private Lab (Private Group)')] as $key => $val)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" wire:model.live="visibility" value="{{ $key }}" class="w-5 h-5 text-primary focus:ring-primary/50 bg-surface-high border-none cursor-pointer">
                                    <span class="text-sm font-medium transition-colors {{ $visibility === $key ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface' }}">{{ $val }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    @if($visibility === 'private')
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
                                <x-input-error :messages="$errors->get('groupId')" />
                            </div>
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
                <x-ui.button type="button" variant="primary" wire:click="nextStep" class="group">
                    <span class="flex items-center gap-2 group-hover:translate-x-1 transition-transform">
                        {{ __('Next Phase') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                    </span>
                </x-ui.button>
            @else
                <x-ui.button type="submit" variant="secondary" class="px-8 shadow-lg shadow-secondary/20">
                    {{ __('Deploy Curation Artifact') }}
                </x-ui.button>
            @endif
        </div>
    </form>
</div>

