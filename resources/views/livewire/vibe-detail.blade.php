<div class="max-w-6xl mx-auto px-6 py-12" x-data="{ activeLine: @entangle('activeLine') }">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        
        <!-- Artefact Column (2/3) -->
        <div class="lg:col-span-2 space-y-8">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        @if($post->group)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-primary/10 text-primary border border-primary/20 uppercase tracking-tighter">
                                <svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z"/></svg>
                                {{ $post->group->name }}
                            </span>
                        @endif
                        <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest bg-surface-container px-2 py-0.5 rounded border border-outline-variant/30">{{ $post->visibility }} artifact</span>
                    </div>
                    <h1 class="text-4xl font-black font-display text-on-surface leading-tight">{{ $post->title }}</h1>
                    <p class="text-on-surface-variant text-lg mt-3 font-medium leading-relaxed italic border-l-2 border-primary/20 pl-4">{{ $post->short_description }}</p>

                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 bg-surface-container/50 p-6 rounded-3xl border border-outline-variant/30 backdrop-blur-sm">
                        @if($post->review_goals)
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-primary shadow-[0_0_8px_rgba(var(--primary-rgb),0.5)]"></div>
                                    <span class="text-[10px] uppercase tracking-[0.2em] font-black text-primary/80">{{ __('Target Review Goals') }}</span>
                                </div>
                                <p class="text-sm text-on-surface leading-normal">{{ $post->review_goals }}</p>
                            </div>
                        @endif
                        @if($post->improvement_goals)
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-secondary shadow-[0_0_8px_rgba(var(--secondary-rgb),0.5)]"></div>
                                    <span class="text-[10px] uppercase tracking-[0.2em] font-black text-secondary/80">{{ __('Improvement Desires') }}</span>
                                </div>
                                <p class="text-sm text-on-surface-variant leading-normal">{{ $post->improvement_goals }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 text-sm text-on-surface-variant leading-relaxed max-w-3xl">
                        {{ $post->description }}
                    </div>
                </div>
                <div class="flex gap-2 ml-6">
                    <select wire:model.live="selectedVersion" class="bg-surface-container border border-outline-variant/50 text-on-surface rounded-2xl text-xs font-bold px-4 py-2 hover:border-primary transition-all cursor-pointer shadow-sm">
                        @foreach($post->snippets as $s)
                            <option value="{{ $s->id }}">{{ __('Version') }} {{ $s->version_number }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- The Code Viewer -->
            <div class="bg-surface-container rounded-2xl overflow-hidden border border-outline-variant shadow-2xl relative">
                <div class="flex items-center gap-2 px-4 py-3 bg-surface-container-high border-b border-outline-variant">
                    <div class="flex gap-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-red-500/50"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500/50"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500/50"></div>
                    </div>
                    <span class="text-xs text-on-surface-variant font-mono ml-4">{{ $currentSnippet->language }}</span>
                </div>

                @if($currentSnippet->description)
                    <div class="px-6 py-4 bg-primary/5 border-b border-outline-variant/30 text-xs text-on-surface leading-normal italic">
                        <span class="font-bold text-primary not-italic uppercase tracking-tighter mr-2">Note:</span>
                        {{ $currentSnippet->description }}
                    </div>
                @endif


                <div class="p-6 font-mono text-sm leading-relaxed overflow-x-auto bg-surface-container-lowest">
                    @php
                        $lines = explode("\n", $currentSnippet->code_content);
                    @endphp
                    <table class="w-full border-collapse">
                        @foreach($lines as $index => $line)
                            @php $num = $index + 1; @endphp
                            <tr class="group hover:bg-surface-container-high transition-colors cursor-pointer" 
                                :class="{ 'bg-primary/10 border-l-2 border-primary': activeLine == {{ $num }} }"
                                wire:click="selectLine({{ $num }})">
                                <td class="w-12 text-outline text-right pr-6 select-none opacity-50 group-hover:opacity-100 italic">{{ $num }}</td>
                                <td class="whitespace-pre"><code>{{ $line }}</code></td>
                            </tr>
                            
                            <!-- Inline Comment Field -->
                            @if($activeLine == $num)
                                <tr>
                                    <td></td>
                                    <td class="py-4 pr-6">
                                        <div x-transition class="bg-surface-container-high p-4 rounded-xl border border-primary/30 shadow-lg">
                                            <textarea wire:model="commentContent" 
                                                      placeholder="{{ __('Share your insight on this line...') }}"
                                                      class="w-full bg-surface-container-lowest border-none rounded-lg text-on-surface text-sm focus:ring-1 focus:ring-primary p-3"
                                                      rows="3"></textarea>
                                            <div class="flex justify-end mt-3 gap-2">
                                                <button wire:click="$set('activeLine', null)" class="text-xs text-on-surface-variant hover:text-on-surface">{{ __('Cancel') }}</button>
                                                <button wire:click="saveComment" class="bg-primary text-on-primary text-xs font-bold px-4 py-2 rounded-lg hover:brightness-110 transition-all shadow-md">{{ __('Post Review') }}</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            <!-- Display Comments for this line -->
                            @foreach($currentSnippet->reviews->where('line_number', $num) as $review)
                                <tr class="bg-surface-container/30 group/review">
                                    <td></td>
                                    <td class="py-3 px-6 text-sm border-l-2 border-outline-variant/30">
                                        <div class="flex items-start justify-between">
                                            <div class="flex gap-3">
                                                <img src="{{ $review->user->avatar }}" class="w-6 h-6 rounded-full border border-white/10 shadow-sm">
                                                <div>
                                                    <span class="font-bold text-xs text-primary">{{ $review->user->name }}</span>
                                                    <p class="text-on-surface-variant mt-1 leading-relaxed">{{ $review->content }}</p>
                                                </div>
                                            </div>
                                            
                                            @can('delete', $review)
                                                <button 
                                                    wire:click="deleteReview({{ $review->id }})"
                                                    wire:confirm="{{ __('Delete this insight?') }}"
                                                    @mouseenter="window.fx.play('hover')"
                                                    class="opacity-0 group-hover/review:opacity-100 p-2 text-on-surface-variant/40 hover:text-error transition-all"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar (Reactions & Social) -->
        <div class="space-y-8">
            <div class="bg-surface-container rounded-2xl p-6 border border-outline-variant">
                <h3 class="font-display font-bold text-lg mb-6 text-primary">{{ __('Tech Reactions') }}</h3>
                <div class="grid grid-cols-2 gap-3">
                    <button wire:click="react('clean')" @mouseenter="window.fx.play('hover')" class="flex flex-col items-center p-4 rounded-xl border {{ $post->reactions->where('user_id', auth()->id())->where('type', 'clean')->count() ? 'bg-primary/20 border-primary' : 'bg-surface-container-high border-transparent' }} hover:border-primary transition-all">
                        <span class="text-2xl mb-1">✨</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider">{{ __('Clean Code') }}</span>
                    </button>
                    <button wire:click="react('optimisable')" @mouseenter="window.fx.play('hover')" class="flex flex-col items-center p-4 rounded-xl border {{ $post->reactions->where('user_id', auth()->id())->where('type', 'optimisable')->count() ? 'bg-primary/20 border-primary' : 'bg-surface-container-high border-transparent' }} hover:border-primary transition-all">
                        <span class="text-2xl mb-1">🚀</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider">{{ __('Optimisable') }}</span>
                    </button>
                    <button wire:click="react('mindblown')" @mouseenter="window.fx.play('hover')" class="flex flex-col items-center p-4 rounded-xl border {{ $post->reactions->where('user_id', auth()->id())->where('type', 'mindblown')->count() ? 'bg-primary/20 border-primary' : 'bg-surface-container-high border-transparent' }} hover:border-primary transition-all">
                        <span class="text-2xl mb-1">🤯</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider">{{ __('Mindblown') }}</span>
                    </button>
                    <button wire:click="react('security')" @mouseenter="window.fx.play('hover')" class="flex flex-col items-center p-4 rounded-xl border {{ $post->reactions->where('user_id', auth()->id())->where('type', 'security')->count() ? 'bg-primary/20 border-primary' : 'bg-surface-container-high border-transparent' }} hover:border-primary transition-all">
                        <span class="text-2xl mb-1">🛡️</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider">{{ __('Security') }}</span>
                    </button>
                </div>
            </div>

            <div class="bg-surface-container rounded-2xl p-6 border border-outline-variant">
                <h3 class="font-display font-bold text-lg mb-4">{{ __('Vibe Author') }}</h3>
                <div class="flex items-center gap-4">
                    <img src="{{ $post->user->avatar }}" class="w-12 h-12 rounded-full border-2 border-primary/30">
                    <div>
                        <p class="font-bold">{{ $post->user->name }}</p>
                        <p class="text-xs text-on-surface-variant italic">Karma: {{ $post->user->reputation_score }}</p>
                    </div>
                </div>
                <p class="text-sm text-on-surface-variant mt-4 leading-relaxed">
                    {{ $post->user->bio ?? 'No developer bio shared yet.' }}
                </p>
            </div>
        </div>
    </div>
</div>
