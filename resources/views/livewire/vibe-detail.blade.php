<div class="max-w-6xl mx-auto px-6 py-12" x-data="{ activeLine: @entangle('activeLine') }">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        
        <!-- Code Column (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold font-display text-on-surface">{{ $post->title }}</h1>
                    <p class="text-on-surface-variant text-sm mt-2">{{ $post->description }}</p>
                    
                    @if($post->goal || $post->context)
                        <div class="mt-6 p-4 bg-primary/5 border border-primary/10 rounded-xl">
                            @if($post->goal)
                                <div class="mb-2">
                                    <span class="text-[10px] uppercase tracking-widest font-bold text-primary">{{ __('Goal') }}</span>
                                    <p class="text-sm text-on-surface">{{ $post->goal }}</p>
                                </div>
                            @endif
                            @if($post->context)
                                <div>
                                    <span class="text-[10px] uppercase tracking-widest font-bold text-primary">{{ __('Context') }}</span>
                                    <p class="text-xs text-on-surface-variant">{{ $post->context }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="flex gap-2">
                    <select wire:model.live="selectedVersion" class="bg-surface-container border-none text-on-surface rounded-xl text-sm px-4">
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
                                <tr class="bg-surface-container/30">
                                    <td></td>
                                    <td class="py-3 px-6 text-sm border-l-2 border-outline-variant/30">
                                        <div class="flex gap-3">
                                            <img src="{{ $review->user->avatar }}" class="w-6 h-6 rounded-full">
                                            <div>
                                                <span class="font-bold text-xs">{{ $review->user->name }}</span>
                                                <p class="text-on-surface-variant mt-1">{{ $review->content }}</p>
                                            </div>
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
                    <button wire:click="react('clean')" class="flex flex-col items-center p-4 rounded-xl border {{ $post->reactions->where('user_id', auth()->id())->where('type', 'clean')->count() ? 'bg-primary/20 border-primary' : 'bg-surface-container-high border-transparent' }} hover:border-primary transition-all">
                        <span class="text-2xl mb-1">✨</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider">{{ __('Clean Code') }}</span>
                    </button>
                    <button wire:click="react('optimisable')" class="flex flex-col items-center p-4 rounded-xl border {{ $post->reactions->where('user_id', auth()->id())->where('type', 'optimisable')->count() ? 'bg-primary/20 border-primary' : 'bg-surface-container-high border-transparent' }} hover:border-primary transition-all">
                        <span class="text-2xl mb-1">🚀</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider">{{ __('Optimisable') }}</span>
                    </button>
                    <button wire:click="react('mindblown')" class="flex flex-col items-center p-4 rounded-xl border {{ $post->reactions->where('user_id', auth()->id())->where('type', 'mindblown')->count() ? 'bg-primary/20 border-primary' : 'bg-surface-container-high border-transparent' }} hover:border-primary transition-all">
                        <span class="text-2xl mb-1">🤯</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider">{{ __('Mindblown') }}</span>
                    </button>
                    <button wire:click="react('security')" class="flex flex-col items-center p-4 rounded-xl border {{ $post->reactions->where('user_id', auth()->id())->where('type', 'security')->count() ? 'bg-primary/20 border-primary' : 'bg-surface-container-high border-transparent' }} hover:border-primary transition-all">
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
