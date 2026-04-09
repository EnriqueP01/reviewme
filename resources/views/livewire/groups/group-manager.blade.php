<div class="max-w-6xl mx-auto px-6 py-12">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar: Group list -->
        <div class="w-full md:w-1/3 space-y-8">
            <div class="flex items-center justify-between">
                <h2 class="font-display text-2xl font-bold text-on-surface tracking-tight">{{ __('Private Groups') }}</h2>
                <x-ui.button variant="ghost" size="sm" wire:click="$toggle('isCreating')">
                    {{ $isCreating ? __('Cancel') : '+ ' . __('New Group') }}
                </x-ui.button>
            </div>

            @if($isCreating)
                <x-ui.card tonal="container" padding="p-6" class="animate-in fade-in slide-in-from-top-4 duration-300">
                    <form wire:submit.prevent="createGroup" class="space-y-4">
                        <div>
                            <x-input-label :value="__('Group Name')" />
                            <x-text-input wire:model="name" placeholder="{{ __('e.g., Core Intelligence Unit') }}" />
                            <x-input-error :messages="$errors->get('name')" />
                        </div>
                        <div>
                            <x-input-label :value="__('Description')" />
                            <textarea wire:model="description" rows="3" class="bg-surface-high border-none text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/50 rounded-round-4 transition-all w-full resize-none p-3 text-sm"></textarea>
                        </div>
                        <x-ui.button type="submit" variant="primary" class="w-full">{{ __('Create Group') }}</x-ui.button>
                    </form>
                </x-ui.card>
            @endif

            <div class="space-y-4">
                <h3 class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">{{ __('Owned Groups') }}</h3>
                @forelse($ownedGroups as $group)
                    <button wire:click="selectGroup({{ $group->id }})" class="w-full text-left group">
                        <x-ui.card :tonal="$selectedGroupId == $group->id ? 'primary' : 'low'" padding="p-4" class="transition-all hover:bg-surface-highest">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-round-2 bg-primary/20 flex items-center justify-center text-primary font-bold">
                                    {{ substr($group->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-on-surface">{{ $group->name }}</div>
                                    <div class="text-[10px] text-on-surface-variant uppercase">{{ trans_choice('{1} 1 Member|[2,*] :count Members', $group->members_count) }}</div>
                                </div>
                            </div>
                        </x-ui.card>
                    </button>
                @empty
                    <p class="text-sm text-on-surface-variant italic">{{ __('No groups owned.') }}</p>
                @endforelse

                <h3 class="text-[10px] uppercase tracking-widest font-bold text-on-surface-variant pt-4">{{ __('Joined Groups') }}</h3>
                @forelse($memberGroups as $group)
                    <button wire:click="selectGroup({{ $group->id }})" class="w-full text-left group">
                        <x-ui.card :tonal="$selectedGroupId == $group->id ? 'secondary' : 'low'" padding="p-4" class="transition-all hover:bg-surface-highest">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-round-2 bg-secondary/20 flex items-center justify-center text-secondary font-bold">
                                    {{ substr($group->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-on-surface">{{ $group->name }}</div>
                                    <div class="text-[10px] text-on-surface-variant uppercase">{{ $group->owner->name }}</div>
                                </div>
                            </div>
                        </x-ui.card>
                    </button>
                @empty
                    <p class="text-sm text-on-surface-variant italic">{{ __('No groups joined.') }}</p>
                @endforelse
            </div>
        </div>

        <!-- Main Content: Group Details -->
        <div class="flex-1">
            @if($selectedGroup)
                <div class="space-y-8 animate-in fade-in slide-in-from-right-4 duration-500">
                    <div class="flex justify-between items-start">
                        <div class="space-y-2">
                            <div class="flex items-center gap-3">
                                <h1 class="font-display text-4xl font-bold text-on-surface tracking-tight">{{ $selectedGroup->name }}</h1>
                                <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-widest">
                                    {{ $selectedGroup->owner_id === auth()->id() ? __('Owner') : __('Member') }}
                                </span>
                            </div>
                            <p class="text-on-surface-variant italic">{{ $selectedGroup->description ?? __('No description provided.') }}</p>
                        </div>
                        
                        @if($selectedGroup->owner_id === auth()->id())
                            <x-ui.button variant="ghost" class="text-secondary" onclick="confirm('{{ __('Delete Group?') }}') || event.stopImmediatePropagation()" wire:click="deleteGroup({{ $selectedGroup->id }})">
                                {{ __('Delete') }}
                            </x-ui.button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Members List -->
                        <div class="space-y-6">
                            <h3 class="font-display text-xl font-bold text-on-surface">{{ __('Members') }}</h3>
                            
                            <div class="space-y-3">
                                @foreach($selectedGroup->members as $member)
                                    <div class="flex items-center justify-between p-3 rounded-round-4 bg-surface-low border border-outline-variant/10">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $member->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($member->name) }}" class="w-8 h-8 rounded-full">
                                            <div>
                                                <div class="text-sm font-bold text-on-surface">{{ $member->name }}</div>
                                                <div class="text-[10px] text-on-surface-variant uppercase tracking-tighter">{{ $member->pivot->role }}</div>
                                            </div>
                                        </div>
                                        
                                        @if($selectedGroup->owner_id === auth()->id() && $member->id !== auth()->id())
                                            <button wire:click="removeMember({{ $member->id }})" class="text-on-surface-variant hover:text-secondary transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @if($selectedGroup->owner_id === auth()->id())
                                <div class="pt-6 border-t border-outline-variant/10 space-y-4">
                                    <h4 class="text-sm font-bold text-on-surface">{{ __('Add Members') }}</h4>
                                    <div class="relative">
                                        <x-text-input wire:model.live="userSearch" placeholder="{{ __('Search for a member...') }}" />
                                        @if(!empty($searchResults))
                                            <div class="absolute z-30 top-full left-0 w-full mt-2 bg-surface-highest rounded-round-4 shadow-xl border border-outline-variant/10 overflow-hidden">
                                                @foreach($searchResults as $res)
                                                    <button wire:click="addMember({{ $res['id'] }})" class="w-full px-4 py-3 flex items-center gap-3 hover:bg-primary/10 transition-colors text-left">
                                                        <img src="{{ $res['avatar'] ?? 'https://ui-avatars.com/api/?name='.urlencode($res['name']) }}" class="w-6 h-6 rounded-full">
                                                        <span class="text-sm text-on-surface">{{ $res['name'] }}</span>
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Recent Activity / Stats Placeholder -->
                        <div class="space-y-6">
                                    <div class="text-[10px] text-on-surface-variant uppercase tracking-widest">{{ __('Posts Shared') }}</div>
                                </div>
                                <p class="text-xs text-on-surface-variant italic">{{ __('Group active.') }}</p>
                            </x-ui.card>
                        </div>
                    </div>
                </div>
            @else
                <div class="h-full flex flex-col items-center justify-center text-center space-y-6 opacity-50">
                    <div class="w-24 h-24 rounded-full bg-surface-high flex items-center justify-center text-on-surface-variant">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.673.337a4 4 0 01-1.909.508H8a2 2 0 00-2 2v3a1 1 0 001 1h10a1 1 0 001-1v-3.5a1 1 0 01.357-.762l.071-.057zM8 12a4 4 0 100-8 4 4 0 000 8z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-display text-xl font-bold text-on-surface">{{ __('Select a Group') }}</h3>
                        <p class="text-sm text-on-surface-variant">{{ __('Select a group to start collaborating.') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
