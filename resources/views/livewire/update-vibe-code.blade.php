<div class="max-w-4xl mx-auto px-6 py-12">
    <div class="mb-8 space-y-2">
        <h1 class="text-3xl font-black font-display text-on-surface">{{ __('Update Artifact Logic') }}</h1>
        <p class="text-on-surface-variant italic">{{ __('Inject new code fragments to create a new version of your artifact: ') }} <span class="font-bold text-primary">{{ $post->title }}</span></p>
    </div>

    <form wire:submit.prevent="submit">
        <div class="space-y-12 animate-in fade-in slide-in-from-bottom-8 duration-700">
            <div class="sticky top-20 z-40 flex flex-col md:flex-row justify-between items-start md:items-end gap-6 bg-surface-lowest/95 backdrop-blur-2xl p-6 -mx-6 rounded-b-round-4 border-b border-outline-variant/20 shadow-[0_20px_50px_-10px_rgba(0,0,0,0.5)] transition-all duration-500">
                <div class="space-y-4 max-w-2xl">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-round-2 bg-primary/20 flex items-center justify-center text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <h2 class="font-display text-2xl font-bold text-on-surface tracking-tight">{{ __('The Digital Artifacts') }}</h2>
                    </div>
                </div>
                
                <x-ui.button type="button" variant="primary" wire:click="addFile" class="shrink-0 group shadow-lg shadow-primary/20">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        {{ __('Add Logic Fragment') }}
                    </span>
                </x-ui.button>
            </div>

            <!-- Fragments Stack -->
            <div class="space-y-12 mt-12">
                @forelse($files as $index => $file)
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

                        <div class="glass-panel rounded-round-4 overflow-hidden border border-outline-variant/10 hover:border-primary/40 transition-all duration-700 shadow-2xl">
                            <!-- HUD Header -->
                            <div @click="focused = !focused" class="cursor-pointer px-6 py-4 bg-surface-container-low/80 flex items-center justify-between border-b border-outline-variant/10 gap-6 hover:bg-surface-high transition-colors">
                                <div class="flex items-center gap-6 flex-1">
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
                                        </div>
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
                                                @for($i = 1; $i <= 14; $i++)
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
                                        <div class="space-y-3">
                                            <label class="text-[10px] text-on-surface-variant uppercase tracking-widest font-black px-1">{{ __('File Language') }}</label>
                                            <select 
                                                wire:model.live="files.{{ $index }}.language"
                                                class="w-full bg-surface-high rounded-round-4 h-12 px-5 text-on-surface font-mono text-xs uppercase tracking-[0.2em] border border-outline-variant/5 focus:ring-1 focus:ring-primary/50 appearance-none cursor-pointer hover:bg-surface-highest transition-all"
                                            >
                                                <option value="none">none</option>
                                                <option value="php">php</option>
                                                <option value="javascript">javascript</option>
                                                <option value="css">css</option>
                                                <option value="html">html</option>
                                                <option value="vue">vue</option>
                                                <option value="blade">blade</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('files.' . $index . '.language')" />
                                        </div>

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
                        <div class="space-y-1">
                            <h4 class="text-on-surface font-display text-xl font-bold uppercase tracking-widest">{{ __('Zero Artefacts Detected') }}</h4>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-8 pt-8 border-t border-outline-variant/10 flex justify-end">
            <x-ui.button type="submit" variant="secondary" wire:loading.attr="disabled" wire:target="submit" class="px-10 py-4 shadow-[0_0_30px_rgba(78,222,163,0.2)] hover:scale-105 transition-all">
                <span class="flex items-center gap-3">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                     {{ __('Deploy New Version') }}
                </span>
            </x-ui.button>
        </div>
    </form>
</div>
