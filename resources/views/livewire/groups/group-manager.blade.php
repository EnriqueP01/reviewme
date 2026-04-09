<div class="w-full max-w-none px-12 py-10">
    <!-- Groups Header -->
    <div class="flex items-end justify-between mb-16 border-b border-outline-variant/10 pb-10">
        <div class="space-y-3">
            <h2 class="font-display text-5xl font-black text-on-surface tracking-tighter">{{ __('Private Groups') }}</h2>
            <p class="text-on-surface-variant text-sm tracking-wide font-medium opacity-60">{{ __('Collaborate with your team in private spaces.') }}</p>
        </div>

        <div class="flex items-center gap-6">
            <x-ui.button variant="{{ $isCreating ? 'primary' : 'ghost' }}" size="sm" wire:click="$toggle('isCreating')" class="!px-8 !rounded-xl shadow-xl">
                {{ $isCreating ? __('Cancel') : '+ ' . __('New Group') }}
            </x-ui.button>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Sidebar: Group Navigation -->
        <div class="w-full lg:w-[400px] shrink-0 space-y-10">
            
            @if($isCreating)
                <div class="bg-primary/5 backdrop-blur-3xl border-2 border-primary/20 rounded-[2.5rem] p-8 shadow-[0_0_50px_rgba(190,194,255,0.1)] animate-in zoom-in-95 duration-300">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-primary mb-6">{{ __('Group Settings') }}</h3>
                    <form wire:submit.prevent="createGroup" class="space-y-6">
                        <div class="space-y-2">
                            <label class="px-1 text-[9px] font-black uppercase tracking-widest text-primary/40">{{ __('Group Name') }}</label>
                            <input type="text" wire:model="name" placeholder="{{ __('e.g., Core Development Team') }}" class="w-full bg-black/40 border border-white/5 rounded-2xl p-4 text-xs text-on-surface focus:border-primary transition-all outline-none font-bold tracking-tight">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="space-y-2">
                            <label class="px-1 text-[9px] font-black uppercase tracking-widest text-primary/40">{{ __('Description') }}</label>
                            <textarea wire:model="description" rows="3" placeholder="{{ __('What is this group about?') }}" class="w-full bg-black/40 border border-white/5 rounded-2xl p-4 text-xs text-on-surface focus:border-primary transition-all outline-none italic font-medium resize-none"></textarea>
                        </div>
                        <x-ui.button type="submit" variant="primary" class="w-full !rounded-2xl !py-4 shadow-2xl">{{ __('Create Group') }}</x-ui.button>
                    </form>
                </div>
            @endif

            <!-- Owned Groups -->
            <div class="space-y-6">
                <div class="flex items-center gap-4">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-on-surface-variant/30">{{ __('Owned Groups') }}</h3>
                    <div class="h-px flex-1 bg-gradient-to-r from-white/5 to-transparent"></div>
                </div>
                
                <div class="space-y-4">
                    @forelse($ownedGroups as $group)
                        <button wire:click="selectGroup({{ $group->id }})" class="w-full text-left group/btn transition-all active:scale-[0.98]">
                            <div @class([
                                'p-6 rounded-[2rem] border transition-all duration-500 relative overflow-hidden flex items-center gap-6',
                                'bg-primary/10 border-primary/30 shadow-[0_0_30px_rgba(190,194,255,0.1)]' => $selectedGroupId == $group->id,
                                'bg-surface-container-low/40 border-white/5 hover:border-white/20' => $selectedGroupId != $group->id
                            ])>
                                <div @class([
                                    'w-14 h-14 rounded-2xl flex items-center justify-center font-display font-black text-xl italic transition-all duration-500',
                                    'bg-primary text-on-primary rotate-3 shadow-2xl' => $selectedGroupId == $group->id,
                                    'bg-surface-container-highest text-primary/40 group-hover/btn:rotate-3 group-hover/btn:bg-primary/20 group-hover/btn:text-primary' => $selectedGroupId != $group->id
                                ])>
                                    {{ strtoupper(substr($group->name, 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <div @class([
                                        'text-lg font-black tracking-tight transition-colors',
                                        'text-on-surface' => $selectedGroupId == $group->id,
                                        'text-on-surface/60 group-hover/btn:text-on-surface' => $selectedGroupId != $group->id
                                    ])>{{ $group->name }}</div>
                                    <div class="text-[9px] font-mono font-bold uppercase tracking-widest text-on-surface-variant/40 mt-0.5">{{ trans_choice('{1} 1 Member|[2,*] :count Members', $group->members_count) }}</div>
                                </div>
                                @if($selectedGroupId == $group->id)
                                    <div class="absolute right-6 w-2 h-2 rounded-full bg-primary animate-pulse"></div>
                                @endif
                            </div>
                        </button>
                    @empty
                        <div class="bg-black/20 border border-white/5 border-dashed rounded-[2rem] p-8 text-center">
                            <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/20">{{ __('No groups owned.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Joined Groups -->
            <div class="space-y-6 pt-6">
                <div class="flex items-center gap-4">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-on-surface-variant/30">{{ __('Joined Groups') }}</h3>
                    <div class="h-px flex-1 bg-gradient-to-r from-white/5 to-transparent"></div>
                </div>

                <div class="space-y-4">
                    @forelse($memberGroups as $group)
                        <button wire:click="selectGroup({{ $group->id }})" class="w-full text-left group/btn transition-all active:scale-[0.98]">
                            <div @class([
                                'p-6 rounded-[2rem] border transition-all duration-500 relative overflow-hidden flex items-center gap-6',
                                'bg-secondary/10 border-secondary/30 shadow-[0_0_30px_rgba(100,200,255,0.1)]' => $selectedGroupId == $group->id,
                                'bg-surface-container-low/40 border-white/5 hover:border-white/20' => $selectedGroupId != $group->id
                            ])>
                                <div @class([
                                    'w-14 h-14 rounded-2xl flex items-center justify-center font-display font-black text-xl italic transition-all duration-500',
                                    'bg-secondary text-on-secondary -rotate-3 shadow-2xl' => $selectedGroupId == $group->id,
                                    'bg-surface-container-highest text-secondary/40 group-hover/btn:-rotate-3 group-hover/btn:bg-secondary/20 group-hover/btn:text-secondary' => $selectedGroupId != $group->id
                                ])>
                                    {{ strtoupper(substr($group->name, 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <div @class([
                                        'text-lg font-black tracking-tight transition-colors',
                                        'text-on-surface' => $selectedGroupId == $group->id,
                                        'text-on-surface/60 group-hover/btn:text-on-surface' => $selectedGroupId != $group->id
                                    ])>{{ $group->name }}</div>
                                    <div class="text-[9px] font-mono font-bold uppercase tracking-widest text-on-surface-variant/40 mt-0.5">{{ __('Owner:') }} {{ strtoupper($group->owner->name) }}</div>
                                </div>
                            </div>
                        </button>
                    @empty
                        <div class="bg-black/20 border border-white/5 border-dashed rounded-[2rem] p-8 text-center">
                            <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/20">{{ __('No joined groups.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Main Content: Group Details -->
        <div class="flex-1">
            @if($selectedGroup)
                <div class="space-y-12 animate-in fade-in slide-in-from-right-8 duration-700">
                    <!-- Group Summary Card -->
                    <div class="bg-surface-container-low/40 backdrop-blur-2xl border border-white/5 rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden group/detail">
                        <div class="absolute top-0 right-0 w-96 h-96 bg-primary/5 blur-[100px] rounded-full -translate-y-1/2 translate-x-1/2"></div>
                        
                        <div class="relative flex flex-col md:flex-row justify-between items-start gap-12">
                            <div class="space-y-6 flex-1">
                                <div class="flex items-center gap-6">
                                    <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center font-display font-black text-2xl text-primary shadow-inner border border-primary/20 italic">
                                        {{ strtoupper(substr($selectedGroup->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h1 class="font-display text-4xl font-black text-on-surface tracking-tighter">{{ $selectedGroup->name }}</h1>
                                        <div class="flex items-center gap-3 mt-1">
                                            <span class="px-3 py-0.5 rounded-full bg-primary/20 text-primary text-[9px] font-black uppercase tracking-widest border border-primary/30">
                                                {{ $selectedGroup->owner_id === auth()->id() ? __('Group Owner') : __('Group Member') }}
                                            </span>
                                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant/30 font-mono">/ ID: #{{ $selectedGroup->id }}</span>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-lg text-on-surface font-semibold leading-relaxed max-w-2xl opacity-80 italic">
                                    {{ $selectedGroup->description ?? __('No description provided.') }}
                                </p>
                            </div>
                            
                            <div class="flex flex-col items-end gap-6">
                                @if($selectedGroup->owner_id === auth()->id())
                                    <x-ui.button variant="ghost" class="!text-rose-500 !bg-rose-500/5 hover:!bg-rose-500/20 !border-rose-500/20 !rounded-xl !px-6 !py-3 text-[10px] font-black tracking-widest uppercase" onclick="confirm('{{ __('Delete this group?') }}') || event.stopImmediatePropagation()" wire:click="deleteGroup({{ $selectedGroup->id }})">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        {{ __('Delete Group') }}
                                    </x-ui.button>
                                @endif

                                <!-- Group Statistics -->
                                <div class="flex items-center gap-4 bg-black/20 rounded-2xl p-4 border border-white/5">
                                    <div class="flex flex-col items-center px-4 border-r border-white/5">
                                        <span class="text-2xl font-black text-on-surface">{{ $selectedGroup->members->count() }}</span>
                                        <span class="text-[8px] font-black uppercase tracking-widest text-on-surface-variant/40">{{ __('Members') }}</span>
                                    </div>
                                    <div class="flex flex-col items-center px-4">
                                        <span class="text-2xl font-black text-primary">0</span>
                                        <span class="text-[8px] font-black uppercase tracking-widest text-on-surface-variant/40">{{ __('Posts') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Group Navigation Tabs -->
                        <div class="mt-12 flex items-center gap-2 p-1.5 bg-black/40 rounded-2xl border border-white/10 w-fit">
                            <button wire:click="$set('activeTab', 'feed')" class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 flex items-center gap-3 {{ $activeTab === 'feed' ? 'bg-primary text-on-primary shadow-2xl' : 'text-on-surface-variant/40 hover:text-white hover:bg-white/5' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 2v4h4M7 8h10M7 12h10M7 16h6"/></svg>
                                {{ __('Activity Feed') }}
                            </button>
                            <button wire:click="$set('activeTab', 'chat')" class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 flex items-center gap-3 {{ $activeTab === 'chat' ? 'bg-[#8B5CF6] text-white shadow-2xl shadow-[#8B5CF6]/20' : 'text-on-surface-variant/40 hover:text-white hover:bg-white/5' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                {{ __('Discussion Hub') }}
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col xl:flex-row gap-12 items-start">
                        <!-- Content -->
                        <div class="flex-grow min-h-[600px] w-full">
                            @if($activeTab === 'feed')
                                <div class="animate-in fade-in slide-in-from-bottom-8 duration-700">
                                    <div class="flex items-center gap-6 mb-12">
                                        <h3 class="text-[10px] font-black uppercase tracking-[0.5em] text-on-surface-variant/20">{{ __('Group Activity') }}</h3>
                                        <div class="h-px flex-1 bg-gradient-to-r from-white/5 to-transparent"></div>
                                    </div>
                                    <livewire:groups.group-feed :group="$selectedGroup" :key="'feed-'.$selectedGroup->id" />
                                </div>
                            @else
                                <div class="animate-in fade-in slide-in-from-bottom-8 duration-700 h-full">
                                    <div class="flex items-center gap-6 mb-12">
                                        <h3 class="text-[10px] font-black uppercase tracking-[0.5em] text-[#8B5CF6]/20">{{ __('Discussion') }}</h3>
                                        <div class="h-px flex-1 bg-gradient-to-r from-[#8B5CF6]/10 to-transparent"></div>
                                    </div>
                                    <div class="h-[600px] bg-surface-container-low/40 backdrop-blur-2xl border border-white/5 rounded-[2.5rem] p-1 overflow-hidden shadow-2xl">
                                        <livewire:groups.group-chat :group="$selectedGroup" :key="'chat-'.$selectedGroup->id" />
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Member Management -->
                        <div class="w-full xl:w-[350px] shrink-0 sticky top-32 space-y-8">
                            <div class="bg-surface-container-low/60 backdrop-blur-3xl border border-white/10 rounded-[2.5rem] p-8 shadow-2xl relative overflow-hidden">
                                <div class="flex items-center justify-between mb-8 pb-4 border-b border-white/5">
                                    <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-on-surface-variant/40 flex items-center gap-3">
                                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        {{ __('Members') }}
                                    </h3>
                                    <span class="text-[10px] font-mono font-black text-primary">{{ $selectedGroup->members->count() }}</span>
                                </div>
                                
                                <div class="space-y-3 max-h-[400px] overflow-y-auto pr-3 custom-scrollbar">
                                    @foreach($selectedGroup->members as $member)
                                        <div class="flex items-center justify-between p-3 rounded-2xl bg-black/20 border border-white/5 group/member transition-all hover:border-primary/20 hover:bg-primary/5">
                                            <div class="flex items-center gap-4">
                                                <div class="relative">
                                                    <img src="{{ $member->profile_photo_url }}" class="w-10 h-10 rounded-xl border border-white/10 shadow-lg object-cover">
                                                    <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-emerald-500 border-[3px] border-[#0d0e12] rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-black text-on-surface tracking-tight">{{ $member->name }}</span>
                                                    <span class="text-[9px] font-mono font-bold text-primary/40 uppercase tracking-tighter">{{ $selectedGroup->owner_id === $member->id ? 'OWNER' : 'MEMBER' }}</span>
                                                </div>
                                            </div>
                                            
                                            @if($selectedGroup->owner_id === auth()->id() && $member->id !== auth()->id())
                                                <button wire:click="removeMember({{ $member->id }})" class="opacity-0 group-hover/member:opacity-100 p-2 text-on-surface-variant/20 hover:text-rose-500 transition-all rounded-xl hover:bg-rose-500/10">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                @if($selectedGroup->owner_id === auth()->id())
                                    <div class="pt-8 mt-8 border-t border-white/10 space-y-4">
                                        <div class="relative group/invite">
                                            <label class="px-1 text-[9px] font-black uppercase tracking-widest text-on-surface-variant/20 mb-2 block">{{ __('Invite Members') }}</label>
                                            <div class="relative">
                                                <input type="text" wire:model.live="userSearch" placeholder="{{ __('Search for users...') }}" class="w-full bg-black/40 border border-white/5 text-[11px] py-4 pl-12 pr-4 focus:ring-1 focus:ring-primary/40 rounded-[1.5rem] text-on-surface placeholder:text-on-surface-variant/10 font-bold transition-all group-hover/invite:border-white/20">
                                                <svg class="absolute top-4 left-4 h-4 w-4 text-on-surface-variant/20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                            </div>

                                            @if(!empty($searchResults))
                                                <div class="absolute z-50 bottom-full left-0 w-full mb-4 bg-surface-container-highest rounded-3xl shadow-[0_30px_100px_rgba(0,0,0,0.8)] border border-white/5 overflow-hidden backdrop-blur-3xl animate-in slide-in-from-bottom-4 duration-300">
                                                    @foreach($searchResults as $res)
                                                        <button wire:click="addMember({{ $res['id'] }})" class="w-full px-6 py-4 flex items-center gap-4 hover:bg-primary/20 transition-all text-left text-xs font-black text-on-surface">
                                                            <img src="{{ $res['profile_photo_url'] ?? '' }}" class="w-8 h-8 rounded-xl shadow-lg">
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
                <!-- No Selection State -->
                <div class="h-full min-h-[500px] flex flex-col items-center justify-center text-center space-y-8 animate-fade-in">
                    <div class="w-40 h-40 rounded-[3rem] bg-surface-container-low/40 border border-white/5 shadow-2xl flex items-center justify-center relative overflow-hidden group/empty">
                        <div class="absolute inset-0 bg-primary/5 opacity-0 group-hover/empty:opacity-100 transition-opacity blur-2xl"></div>
                        <svg class="w-20 h-20 text-on-surface-variant/10 group-hover/empty:text-primary/20 transition-all duration-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.673.337a4 4 0 01-1.909.508H8a2 2 0 00-2 2v3a1 1 0 001 1h10a1 1 0 001-1v-3.5a1 1 0 01.357-.762l.071-.057zM8 12a4 4 0 100-8 4 4 0 000 8z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-display text-4xl font-black text-on-surface/40 tracking-tighter">{{ __('Select a Group') }}</h3>
                        <p class="text-[10px] font-black uppercase tracking-[0.5em] text-on-surface-variant/20 mt-4">{{ __('Join or select a workspace to begin.') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
