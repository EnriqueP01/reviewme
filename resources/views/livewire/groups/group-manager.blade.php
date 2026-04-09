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

                    <div class="flex items-center gap-1 p-1 bg-white/[0.03] border border-white/5 rounded-2xl w-fit">
                        <button wire:click="$set('activeTab', 'feed')" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2 {{ $activeTab === 'feed' ? 'bg-primary text-white shadow-lg' : 'text-white/40 hover:text-white/60 hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 2v4h4M7 8h10M7 12h10M7 16h6"/></svg>
                            {{ __('Activity Feed') }}
                        </button>
                        <button wire:click="$set('activeTab', 'chat')" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2 {{ $activeTab === 'chat' ? 'bg-[#8B5CF6] text-white shadow-lg' : 'text-white/40 hover:text-white/60 hover:bg-white/5' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            {{ __('Discussion Hub') }}
                        </button>
                    </div>

                    <div class="flex flex-col lg:flex-row gap-8">
                        <!-- Main Content Based on Tab -->
                        <div class="flex-grow space-y-8 min-h-[600px]">
                            @if($activeTab === 'feed')
                                <div class="animate-in fade-in slide-in-from-bottom-4 duration-500">
                                    <h3 class="font-display text-xl font-bold text-on-surface flex items-center gap-3 mb-6">
                                        {{ __('Group Activity') }}
                                        <span class="text-[10px] font-normal text-white/20 uppercase tracking-widest px-2 py-0.5 border border-white/5 rounded-md bg-white/[0.02]">{{ __('Live') }}</span>
                                    </h3>
                                    <livewire:groups.group-feed :group="$selectedGroup" :key="'feed-'.$selectedGroup->id" />
                                </div>
                            @else
                                <div class="animate-in fade-in slide-in-from-bottom-4 duration-500 h-full">
                                    <h3 class="font-display text-xl font-bold text-on-surface flex items-center gap-3 mb-6">
                                        {{ __('Shared Intelligence Channel') }}
                                        <span class="text-[10px] font-normal text-[#8B5CF6]/50 uppercase tracking-widest px-2 py-0.5 border border-[#8B5CF6]/10 rounded-md bg-[#8B5CF6]/5">{{ __('Encrypted') }}</span>
                                    </h3>
                                    <div class="h-[600px]">
                                        <livewire:groups.group-chat :group="$selectedGroup" :key="'chat-'.$selectedGroup->id" />
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Right Sidebar: Members (Always Visible but compact) -->
                        <div class="w-full lg:w-72 space-y-8">
                            <div class="bg-black/20 border border-white/5 rounded-3xl p-6 backdrop-blur-sm">
                                <h3 class="font-display text-sm uppercase tracking-widest font-bold text-white/30 flex items-center gap-3 mb-6">
                                    <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    {{ __('Nodes') }} ({{ $selectedGroup->members->count() }})
                                </h3>
                                
                                <div class="space-y-2 max-h-[300px] overflow-y-auto pr-2 scrollbar-thin">
                                    @foreach($selectedGroup->members as $member)
                                        <div class="flex items-center justify-between p-2 rounded-xl bg-white/[0.02] border border-white/5 group/member transition-all hover:bg-white/5">
                                            <div class="flex items-center gap-3">
                                                <div class="relative">
                                                    <img src="{{ $member->profile_photo_url }}" class="w-7 h-7 rounded-lg border border-white/10">
                                                    <div class="absolute -bottom-1 -right-1 w-2.5 h-2.5 bg-green-500 border-2 border-black rounded-full"></div>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="text-[11px] font-bold text-white/80">{{ $member->name }}</span>
                                                    <span class="text-[9px] text-white/30 uppercase tracking-tighter">{{ $selectedGroup->owner_id === $member->id ? 'Lead' : 'Node' }}</span>
                                                </div>
                                            </div>
                                            
                                            @if($selectedGroup->owner_id === auth()->id() && $member->id !== auth()->id())
                                                <button wire:click="removeMember({{ $member->id }})" class="opacity-0 group-hover/member:opacity-100 p-1 text-white/20 hover:text-red-500 transition-all rounded-md hover:bg-white/5">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                @if($selectedGroup->owner_id === auth()->id())
                                    <div class="pt-6 mt-6 border-t border-white/5 space-y-3">
                                        <div class="relative">
                                            <input type="text" wire:model.live="userSearch" placeholder="{{ __('Invite Node...') }}" class="w-full bg-white/[0.03] border-white/5 text-[10px] py-2 focus:ring-1 focus:ring-primary rounded-xl text-white placeholder:text-white/20">
                                            @if(!empty($searchResults))
                                                <div class="absolute z-30 bottom-full left-0 w-full mb-2 bg-[#1a1c2e] rounded-xl shadow-2xl border border-white/10 overflow-hidden backdrop-blur-3xl">
                                                    @foreach($searchResults as $res)
                                                        <button wire:click="addMember({{ $res['id'] }})" class="w-full px-4 py-3 flex items-center gap-3 hover:bg-primary/20 transition-colors text-left text-xs text-white/70">
                                                            <img src="{{ $res['profile_photo_url'] ?? '' }}" class="w-6 h-6 rounded-lg">
                                                            <span>{{ $res['name'] }}</span>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
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
