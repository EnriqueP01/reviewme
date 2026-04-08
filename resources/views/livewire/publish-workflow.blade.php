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
                            <x-input-error for="title" />
                        </div>
                        <div>
                            <x-input-label :value="__('Short Summary') . ' *'" />
                            <x-text-input wire:model="short_description" placeholder="{{ __('Briefly hook the curators...') }}" />
                            <x-input-error for="short_description" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label :value="__('Target Review Goals') . ' *'" />
                            <textarea wire:model="review_goals" rows="3" class="bg-surface-high border-none text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/50 rounded-round-4 transition-all duration-300 w-full resize-none p-4" placeholder="{{ __('What logic should be audited?') }}"></textarea>
                            <x-input-error for="review_goals" />
                        </div>
                        <div>
                            <x-input-label :value="__('Improvement Desires') . ' *'" />
                            <textarea wire:model="improvement_goals" rows="3" class="bg-surface-high border-none text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/50 rounded-round-4 transition-all duration-300 w-full resize-none p-4" placeholder="{{ __('What do you want to elevate?') }}"></textarea>
                            <x-input-error for="improvement_goals" />
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
                        <p class="text-on-surface-variant italic">{{ __('Input the files you wish to have curated. Drag & drop files directly into code blocks.') }}</p>
                    </div>
                    <x-ui.button type="button" variant="ghost" wire:click="addFile" size="sm">+ {{ __('Add Fragment') }}</x-ui.button>
                </div>

                <div class="space-y-6">
                    @foreach($files as $index => $file)
                        <x-ui.card tonal="container" padding="p-6" class="space-y-4 relative group overflow-hidden">
                            <button type="button" wire:click="removeFile({{ $index }})" class="absolute top-4 right-4 text-on-surface-variant hover:text-secondary opacity-0 group-hover:opacity-100 transition-all z-20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="col-span-1 md:col-span-2">
                                    <x-input-label :value="__('Filename') . ' *'" />
                                    <x-text-input wire:model.live="files.{{ $index }}.name" placeholder="core_logic.php" />
                                </div>
                                <div>
                                    <x-input-label :value="__('Detected Engine')" />
                                    <div class="flex items-center h-[46px] px-4 bg-surface-high rounded-round-4 text-on-surface-variant font-mono text-sm uppercase tracking-widest border border-outline-variant/10">
                                        {{ $file['language'] }}
                                    </div>
                                </div>
                            </div>

                            <div 
                                x-data="{ dragging: false }"
                                @dragover.prevent="dragging = true"
                                @dragleave.prevent="dragging = false"
                                @drop.prevent="
                                    dragging = false;
                                    const file = $event.dataTransfer.files[0];
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            @this.set('files.{{ $index }}.content', e.target.result);
                                            @this.set('files.{{ $index }}.name', file.name);
                                        };
                                        reader.readAsText(file);
                                    }
                                "
                                class="relative"
                            >
                                <x-input-label :value="__('Logic Body (Paste or Drag & Drop)') . ' *'" />
                                <textarea 
                                    wire:model="files.{{ $index }}.content" 
                                    rows="8" 
                                    class="font-mono bg-surface-lowest border-none text-primary placeholder:text-primary/30 focus:ring-1 focus:ring-primary/50 rounded-round-4 transition-all duration-300 w-full resize-none p-4" 
                                    placeholder="{{ __('Paste or drop code fragment...') }}"
                                    :class="{ 'ring-2 ring-secondary/50': dragging }"
                                ></textarea>
                                
                                <div x-show="dragging" class="absolute inset-0 bg-secondary/10 border-2 border-dashed border-secondary rounded-round-4 flex items-center justify-center pointer-events-none transition-all duration-300">
                                    <span class="text-secondary font-bold">{{ __('Drop to Import Content') }}</span>
                                </div>
                            </div>

                            <div>
                                <x-input-label :value="__('Contextual Note for this Fragment (Optional)')" />
                                <textarea wire:model="files.{{ $index }}.description" rows="2" class="bg-surface-low border-none text-on-surface-variant placeholder:text-on-surface-variant/30 focus:ring-2 focus:ring-primary/30 rounded-round-4 transition-all duration-300 w-full resize-none p-3 text-sm" placeholder="{{ __('What is unique about this file?') }}"></textarea>
                            </div>
                        </x-ui.card>
                    @endforeach
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
                                <x-input-error for="groupId" />
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

