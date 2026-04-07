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
                    {{ $s == 1 ? 'Introspection' : ($s == 2 ? 'The Lens' : 'Focus') }}
                </span>
            </div>
        @endforeach
    </div>

    <form wire:submit.prevent="submit">
        @if($step == 1)
            <!-- Step 1: Introspection -->
            <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="space-y-2">
                    <h2 class="font-display text-3xl font-bold text-on-surface tracking-tight">Project Introspection</h2>
                    <p class="text-on-surface-variant italic">What is the creative north star of this code?</p>
                </div>

                <div class="space-y-6">
                    <div>
                        <x-input-label value="Primary Goal" />
                        <x-text-input wire:model="goal" placeholder="e.g., Implementing a recursive BFS for social graphs..." />
                    </div>
                    <div>
                        <x-input-label value="Atmospheric Context (Technical Debt, Constraints)" />
                        <textarea wire:model="context" rows="4" class="bg-surface-high border-none text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/50 rounded-round-4 transition-all duration-300 w-full resize-none p-4" placeholder="Explain the constraints..."></textarea>
                    </div>
                </div>
            </div>
        @elseif($step == 2)
            <!-- Step 2: The Lens (Files) -->
            <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="flex justify-between items-end">
                    <div class="space-y-2">
                        <h2 class="font-display text-3xl font-bold text-on-surface tracking-tight">The Lens</h2>
                        <p class="text-on-surface-variant italic">Input the artifacts you wish to have curated.</p>
                    </div>
                    <x-ui.button type="button" variant="ghost" wire:click="addFile" size="sm">+ Add File</x-ui.button>
                </div>

                <div class="space-y-6">
                    @foreach($files as $index => $file)
                        <x-ui.card tonal="container" padding="p-6" class="space-y-4 relative group">
                            <button type="button" wire:click="removeFile({{ $index }})" class="absolute top-4 right-4 text-on-surface-variant hover:text-secondary opacity-0 group-hover:opacity-100 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-2">
                                    <x-input-label value="Filename" />
                                    <x-text-input wire:model="files.{{ $index }}.name" placeholder="app.js" />
                                </div>
                                <div>
                                    <x-input-label value="Language" />
                                    <select wire:model="files.{{ $index }}.language" class="bg-surface-high border-none text-on-surface focus:ring-2 focus:ring-primary/50 rounded-round-4 transition-all w-full p-3 h-[46px]">
                                        <option value="javascript">JS</option>
                                        <option value="php">PHP</option>
                                        <option value="css">CSS</option>
                                        <option value="sql">SQL</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <x-input-label value="Content" />
                                <textarea wire:model="files.{{ $index }}.content" rows="6" class="font-mono bg-surface-lowest border-none text-primary placeholder:text-primary/30 focus:ring-1 focus:ring-primary/50 rounded-round-4 transition-all duration-300 w-full resize-none p-4" placeholder="// Paste your code here..."></textarea>
                            </div>
                        </x-ui.card>
                    @endforeach
                </div>
            </div>
        @elseif($step == 3)
            <!-- Step 3: Focus Area -->
            <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="space-y-2">
                    <h2 class="font-display text-3xl font-bold text-on-surface tracking-tight">Curation Focus</h2>
                    <p class="text-on-surface-variant italic">What should the curators prioritize?</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach(['elegant' => 'Elegance & Patterns', 'performance' => 'Execution Speed', 'readability' => 'Clarity & Logic'] as $key => $label)
                        <label class="cursor-pointer group">
                            <input type="radio" wire:model="lens" value="{{ $key }}" class="hidden peer">
                            <div class="h-full border border-outline-variant/10 bg-surface-low p-8 rounded-round-4 transition-all duration-500 peer-checked:bg-primary/10 peer-checked:border-primary group-hover:bg-surface-high">
                                <div class="w-12 h-12 rounded-round-4 flex items-center justify-center mb-6 {{ $key == 'elegant' ? 'bg-primary/20 text-primary' : ($key == 'performance' ? 'bg-secondary/20 text-secondary' : 'bg-tertiary/20 text-tertiary') }}">
                                    <!-- Icon placeholder -->
                                    <span class="font-display font-bold text-xl">{{ strtoupper(substr($key, 0, 1)) }}</span>
                                </div>
                                <h4 class="font-display font-bold text-lg text-on-surface mb-2">{{ $label }}</h4>
                                <p class="text-on-surface-variant text-sm">Focus the review on architectural excellence and modern patterns.</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Footer Actions -->
        <div class="mt-16 pt-8 border-t border-outline-variant/10 flex justify-between">
            @if($step > 1)
                <x-ui.button type="button" variant="ghost" wire:click="prevStep">Back</x-ui.button>
            @else
                <div></div>
            @endif

            @if($step < 3)
                <x-ui.button type="button" variant="primary" wire:click="nextStep">Next Phase</x-ui.button>
            @else
                <x-ui.button type="submit" variant="secondary">Deploy for Curation</x-ui.button>
            @endif
        </div>
    </form>
</div>
