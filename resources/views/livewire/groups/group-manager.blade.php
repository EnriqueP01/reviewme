<div class="w-full max-w-none px-12 py-10">
    <!-- Back Button -->
    <div class="mb-8 hover-trigger">
        <x-ui.back-button fallback="{{ route('dashboard') }}" />
    </div>

    <!-- Groups Header -->
    <div class="flex items-end justify-between mb-24 relative">
        <div class="space-y-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-1 bg-primary rounded-full"></div>
                <span class="text-[10px] font-black uppercase tracking-[0.5em] text-primary/60 italic">{{ __('FORGE BETTER CODE') }}</span>
            </div>
            <h1 class="font-display text-7xl font-black text-on-surface tracking-tighter leading-none">
                {{ __('Groups') }}<span class="text-primary">.</span>
            </h1>
        </div>

        <div class="flex items-center gap-6" x-data="{ hasPermission: {{ Auth::user()->hasKarmaPermission('group.create') ? 'true' : 'false' }} }">
            <x-ui.button 
                variant="{{ $isCreating ? 'primary' : 'ghost' }}" 
                x-on:click="hasPermission ? $wire.toggleCreating() : $dispatch('vibe-notif', { type: 'error', message: '{{ __('Niveau de karma insuffisant pour créer un groupe.') }}' })" 
                class="!px-10 !py-5 !rounded-2xl shadow-2xl {{ !$isCreating ? '!bg-white/5 !border-white/10 hover:!bg-white/10' : '' }}"
            >
                <div class="flex items-center gap-3">
                    @if($isCreating)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>{{ __('Cancel') }}</span>
                    @else
                        @if(!Auth::user()->hasKarmaPermission('group.create'))
                            <svg class="w-4 h-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M12 4v16m8-8H4"/></svg>
                        @endif
                        <span>{{ __('New Group') }}</span>
                    @endif
                </div>
            </x-ui.button>
        </div>
    </div>

    <div class="flex flex-col gap-12" x-data="{ showMembers: false }">
        <!-- Horizontal Group Selector -->
        <div class="space-y-6">
            <div class="flex items-center gap-4 mb-4">
                <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-on-surface-variant/30 italic">{{ __('Workspaces') }}</h3>
                <div class="h-px flex-1 bg-gradient-to-r from-white/5 to-transparent"></div>
            </div>
            
            <div class="relative group/scroll">
                <div class="flex gap-4 overflow-x-auto pb-4 custom-scrollbar snap-x">
                    @foreach($ownedGroups as $group)
                        <button wire:click="selectGroup({{ $group->id }})" class="shrink-0 snap-start text-left group/btn transition-all active:scale-[0.98]">
                            <div @class([
                                'w-[280px] p-5 rounded-[2rem] border transition-all duration-500 relative overflow-hidden flex items-center gap-5',
                                'bg-primary/10 border-primary/30 shadow-[0_20px_40px_rgba(190,194,255,0.05)]' => $selectedGroupId == $group->id,
                                'bg-surface-container-low/40 border-white/5 hover:border-white/20' => $selectedGroupId != $group->id
                            ])>
                                <x-ui.avatar :model="$group" size="lg" class="{{ $selectedGroupId == $group->id ? 'rotate-2 shadow-xl' : 'group-hover/btn:rotate-2 transition-all duration-500 shadow-lg' }} !rounded-[1.2rem]" />
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-black tracking-tight text-on-surface truncate">{{ $group->name }}</div>
                                    <div class="text-[8px] font-mono font-bold uppercase tracking-widest text-on-surface-variant/40 mt-1">{{ trans_choice('{1} 1 Member|[2,*] :count Members', $group->members_count) }}</div>
                                </div>
                                @if($selectedGroupId == $group->id)
                                    <div class="w-1.5 h-1.5 rounded-full bg-primary shadow-[0_0_10px_rgba(190,194,255,0.8)]"></div>
                                @endif
                            </div>
                        </button>
                    @endforeach

                    @foreach($memberGroups as $group)
                        <button wire:click="selectGroup({{ $group->id }})" class="shrink-0 snap-start text-left group/btn transition-all active:scale-[0.98]">
                            <div @class([
                                'w-[280px] p-5 rounded-[2rem] border transition-all duration-500 relative overflow-hidden flex items-center gap-5',
                                'bg-secondary/10 border-secondary/30 shadow-[0_20px_40px_rgba(100,200,255,0.05)]' => $selectedGroupId == $group->id,
                                'bg-surface-container-low/40 border-white/5 hover:border-white/20' => $selectedGroupId != $group->id
                            ])>
                                <x-ui.avatar :model="$group" size="lg" class="{{ $selectedGroupId == $group->id ? '-rotate-2 shadow-xl' : 'group-hover/btn:-rotate-2 transition-all duration-500 shadow-lg' }} !rounded-[1.2rem]" />
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-black tracking-tight text-on-surface truncate">{{ $group->name }}</div>
                                    <div class="text-[8px] font-mono font-bold uppercase tracking-widest text-on-surface-variant/40 mt-1">{{ __('Contributor') }}</div>
                                </div>
                                @if($selectedGroupId == $group->id)
                                    <div class="w-1.5 h-1.5 rounded-full bg-secondary shadow-[0_0_10px_rgba(100,200,255,0.8)]"></div>
                                @endif
                            </div>
                        </button>
                    @endforeach

                    @if($ownedGroups->isEmpty() && $memberGroups->isEmpty())
                        <div class="flex-1 bg-black/20 border border-white/5 border-dashed rounded-[2rem] p-6 text-center">
                            <p class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant/20 italic">{{ __('No active workspaces found. Forge one to begin.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content Area: Groups and Selection -->
        <div class="w-full">
            @if($selectedGroup)
                <div class="space-y-12 animate-in fade-in slide-in-from-bottom-8 duration-700 relative">
                    <!-- Group Selection Header & Actions Overlay -->
                    <div class="bg-surface-container-low/40 backdrop-blur-2xl border border-white/5 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/5 blur-[120px] rounded-full -translate-y-1/2 translate-x-1/2"></div>
                        
                        <div class="relative flex flex-col xl:flex-row justify-between items-start xl:items-center gap-10">                            <div class="flex items-center gap-8 flex-1">
                                <!-- Rebuilt Professional Profile Photo -->
                                <div class="relative group/logo shrink-0 w-32 h-32 flex items-center justify-center">
                                    <div class="absolute inset-0 bg-primary/20 blur-3xl rounded-[2.5rem] opacity-0 group-hover/logo:opacity-100 transition-opacity duration-700"></div>
                                    
                                    <div class="relative w-full h-full">
                                        <x-ui.avatar :model="$selectedGroup" size="2xl" class="!w-full !h-full !rounded-[2.5rem] shadow-2xl relative z-10 !border-none transition-all duration-500 !font-display !font-black !text-5xl" />
                                        
                                        @if($selectedGroup->owner_id === auth()->id())
                                            <label class="absolute inset-0 z-20 flex items-center justify-center bg-black/60 opacity-0 group-hover/logo:opacity-100 transition-all duration-300 cursor-pointer rounded-[2.5rem] backdrop-blur-[2px]">
                                                <div class="relative flex flex-col items-center justify-center w-full h-full">
                                                    <x-ui.loader wire:loading wire:target="logo" class="!h-10 !w-10 !text-white" />
                                                    <div wire:loading.remove wire:target="logo" class="flex flex-col items-center gap-2">
                                                        <svg class="w-10 h-10 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        <span class="text-[8px] font-black uppercase tracking-[0.3em] text-white/70">{{ __('Update') }}</span>
                                                    </div>
                                                </div>
                                                <input type="file" wire:model="logo" class="hidden" accept="image/*">
                                            </label>
                                        @endif
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="flex items-center gap-6">
                                        <h1 class="font-display text-7xl font-black text-on-surface tracking-tighter leading-none">{{ $selectedGroup->name }}</h1>
                                        <div class="h-6 w-px bg-white/10"></div>
                                        <span class="text-[14px] font-mono font-bold text-on-surface-variant/20 tracking-tighter">/{{ $selectedGroup->id }}</span>
                                    </div>
                                    <div class="flex items-center gap-6">
                                        <div class="flex items-center gap-2.5 px-3 py-1 bg-white/5 rounded-full border border-white/5">
                                            <div class="w-2 h-2 rounded-full {{ $selectedGroup->owner_id === auth()->id() ? 'bg-primary shadow-[0_0_10px_rgba(190,194,255,0.8)]' : 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.8)]' }}"></div>
                                            <span class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant/80">
                                                {{ $selectedGroup->owner_id === auth()->id() ? __('Owner Privileges') : __('Member Access') }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-on-surface-variant/50 font-medium italic line-clamp-1 max-w-md">
                                            {{ $selectedGroup->description ?? __('Secure environment for code collaboration.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>>
                            
                            <div class="flex items-center gap-6">
                                <!-- Members Toggle -->
                                <button x-on:click="showMembers = !showMembers" class="flex flex-col items-center gap-2 group/member-toggle">
                                    <div class="flex-shrink-0 flex -space-x-3 overflow-hidden p-1">
                                        @foreach($selectedGroup->members->take(3) as $m)
                                            <x-ui.avatar :model="$m" size="sm" class="!w-10 !h-10 !rounded-xl ring-4 ring-surface-container-low border-none" :link="false" />
                                        @endforeach
                                        @if($selectedGroup->members_count > 3)
                                            <div class="w-10 h-10 rounded-xl bg-black/40 border border-white/5 flex items-center justify-center text-[10px] font-black text-primary backdrop-blur-md ring-4 ring-surface-container-low">+{{ $selectedGroup->members_count - 3 }}</div>
                                        @endif
                                    </div>
                                    <span class="text-[8px] font-black uppercase tracking-widest text-on-surface-variant/40 group-hover/member-toggle:text-primary transition-colors cursor-pointer">{{ trans_choice('{1} 1 Active Member|[2,*] :count Active Members', $selectedGroup->members_count) }}</span>
                                </button>

                                <div class="h-12 w-px bg-white/5"></div>

                                <div class="flex items-center gap-4">
                                    @if($selectedGroup->owner_id === auth()->id())
                                        <x-ui.button variant="danger" size="sm" x-on:click="$dispatch('open-modal', 'delete-group-modal')" class="shadow-xl">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span>{{ __('Delete Group') }}</span>
                                            </div>
                                        </x-ui.button>
                                    @else
                                        <x-ui.button variant="ghost" size="sm" wire:click="leaveGroup({{ $selectedGroup->id }})" class="!bg-white/5 hover:!bg-white/10 border-white/10 text-on-surface-variant shadow-xl">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-4 h-4 text-on-surface-variant/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                                <span>{{ __('Leave Group') }}</span>
                                            </div>
                                        </x-ui.button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Full Width Tabs Integration -->
                        <div class="mt-12 flex items-center justify-between gap-10">
                            <div class="flex items-center gap-3 p-1.5 bg-black/40 rounded-2xl border border-white/10 w-fit backdrop-blur-xl">
                                <button wire:click="$set('activeTab', 'feed')" class="px-10 py-3.5 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all duration-500 flex items-center gap-4 {{ $activeTab === 'feed' ? 'bg-primary text-on-primary shadow-[0_10px_30px_rgba(190,194,255,0.2)]' : 'text-on-surface-variant/40 hover:text-white hover:bg-white/5' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 2v4h4M7 8h10M7 12h10M7 16h6"/></svg>
                                    {{ __('Intelligence Feed') }}
                                </button>
                                <button wire:click="$set('activeTab', 'chat')" class="px-10 py-3.5 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all duration-500 flex items-center gap-4 {{ $activeTab === 'chat' ? 'bg-primary text-on-primary shadow-[0_10px_30px_rgba(190,194,255,0.2)]' : 'text-on-surface-variant/40 hover:text-white hover:bg-white/5' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    {{ __('Communication Hub') }}
                                </button>
                            </div>

                            <div class="flex items-center gap-6">
                                <div class="flex flex-col items-center">
                                    <span class="text-3xl font-display font-black text-on-surface tracking-tighter">{{ $selectedGroup->posts_count }}</span>
                                    <span class="text-[8px] font-black uppercase tracking-[0.2em] text-on-surface-variant/20">{{ __('Posts') }}</span>
                                </div>
                                <div class="w-1 h-1 rounded-full bg-white/10"></div>
                                <div class="flex flex-col items-center">
                                    <span class="text-3xl font-display font-black text-primary tracking-tighter">{{ $selectedGroup->members_count }}</span>
                                    <span class="text-[8px] font-black uppercase tracking-[0.2em] text-on-surface-variant/20">{{ __('Group Members') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Full Width Feed / Content Section -->
                    <div class="w-full">
                        <!-- Content -->
                        <div class="w-full min-h-[600px]">
                            @if($activeTab === 'feed')
                                <div class="animate-in fade-in slide-in-from-bottom-12 duration-1000">
                                    <div class="flex items-center gap-6 mb-16">
                                        <div class="w-8 h-px bg-primary/20"></div>
                                        <h3 class="text-[10px] font-black uppercase tracking-[0.6em] text-on-surface-variant/20 italic">{{ __('Operational Stream') }}</h3>
                                        <div class="h-px flex-1 bg-gradient-to-r from-white/5 to-transparent"></div>
                                    </div>
                                    <!-- Full Width Feed Component -->
                                    <div class="w-full">
                                        <livewire:groups.group-feed :group="$selectedGroup" :key="'feed-'.$selectedGroup->id" />
                                    </div>
                                </div>
                            @else
                                <div class="animate-in fade-in slide-in-from-bottom-12 duration-1000 h-full">
                                    <div class="flex items-center gap-6 mb-16">
                                        <div class="w-8 h-px bg-primary/20"></div>
                                        <h3 class="text-[10px] font-black uppercase tracking-[0.6em] text-primary/30 italic">{{ __('Real-time Communication') }}</h3>
                                        <div class="h-px flex-1 bg-gradient-to-r from-primary/10 to-transparent"></div>
                                    </div>
                                    <div class="h-[700px] bg-surface-container-low/40 backdrop-blur-3xl border border-white/5 rounded-[3.5rem] p-1 overflow-hidden shadow-2xl relative">
                                        <livewire:groups.group-chat :group="$selectedGroup" :key="'chat-'.$selectedGroup->id" />
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right-Side Retractable Members Panel -->
                    <div 
                        x-show="showMembers" 
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="translate-x-full opacity-0"
                        x-transition:enter-end="translate-x-0 opacity-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="translate-x-0 opacity-100"
                        x-transition:leave-end="translate-x-full opacity-0"
                        class="fixed top-0 right-0 h-full w-[450px] z-[100] bg-surface-container-highest/95 backdrop-blur-3xl border-l border-white/10 shadow-[(-40px)_0_100px_rgba(0,0,0,0.8)] p-12 overflow-y-auto"
                        @click.away="showMembers = false"
                    >
                        <div class="flex items-center justify-between mb-16">
                            <div class="space-y-1">
                                <h3 class="text-[11px] font-black uppercase tracking-[0.4em] text-primary">{{ __('Verification Node') }}</h3>
                                <p class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant/40 italic">{{ __('Active Group Members') }}</p>
                            </div>
                            <button @click="showMembers = false" class="p-3 bg-white/5 hover:bg-white/10 rounded-2xl transition-all">
                                <svg class="w-6 h-6 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="space-y-6">
                            @foreach($selectedGroup->members as $member)
                                <div class="flex items-center justify-between p-5 rounded-[2rem] bg-black/40 border border-white/5 group/member transition-all hover:border-primary/40 hover:bg-primary/5">
                                    <a href="{{ route('profile.show', $member->handle) }}" wire:navigate class="flex items-center gap-5 flex-1 min-w-0">
                                        <div class="relative shrink-0">
                                            <x-ui.avatar :model="$member" size="md" :link="false" class="!rounded-[1.2rem] border-2 border-white/10 shadow-xl" />
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-[3.5px] border-[#0d0e12] rounded-full shadow-[0_0_15px_rgba(16,185,129,0.4)]"></div>
                                        </div>

                                        <div class="flex flex-col min-w-0">
                                            <span class="text-sm font-black text-on-surface tracking-tight truncate group-hover/member:text-primary transition-colors">{{ $member->name }}</span>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-mono font-bold text-on-surface-variant/40 lowercase tracking-tighter truncate">{{ '@'.$member->handle }}</span>
                                                <div class="w-1 h-1 rounded-full bg-white/10"></div>
                                                <span @class([
                                                    'text-[8px] font-black uppercase tracking-[0.2em]',
                                                    'text-primary' => $selectedGroup->owner_id === $member->id,
                                                    'text-on-surface-variant/20' => $selectedGroup->owner_id !== $member->id
                                                ])>{{ $selectedGroup->owner_id === $member->id ? 'MASTER' : 'PEER' }}</span>
                                            </div>
                                        </div>
                                    </a>
                                    
                                    @if($selectedGroup->owner_id === auth()->id() && $member->id !== auth()->id())
                                        <button wire:click="removeMember({{ $member->id }})" class="opacity-0 group-hover/member:opacity-100 p-2.5 text-rose-500/40 hover:text-rose-500 transition-all rounded-xl hover:bg-rose-500/10 shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if($selectedGroup->owner_id === auth()->id())
                            <div class="pt-12 mt-12 border-t border-white/10">
                                <div class="relative group/invite">
                                    <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-primary mb-6">{{ __('System Expansion') }}</h4>
                                    <x-ui.search-input 
                                        model="userSearch" 
                                        placeholder="{{ __('Search by name or @handle...') }}"
                                    />

                                    @if(!empty($searchResults))
                                        <div class="absolute z-[110] bottom-full left-0 w-full mb-6 bg-surface-container-highest rounded-3xl shadow-[0_40px_100px_rgba(0,0,0,1)] border border-white/10 overflow-hidden backdrop-blur-3xl animate-in fade-in slide-in-from-bottom-6 duration-500">
                                            @foreach($searchResults as $res)
                                                <button wire:click="addMember({{ $res['id'] }})" class="w-full px-8 py-5 flex items-center gap-5 hover:bg-primary/20 transition-all text-left group/search-item border-b border-white/5 last:border-0">
                                                    @php $u = \App\Models\User::find($res['id']); @endphp
                                                    <x-ui.avatar :model="$u" size="sm" class="rounded-xl shadow-lg ring-2 ring-white/5" />
                                                    <div class="flex flex-col">
                                                        <span class="text-xs font-black text-on-surface group-hover/search-item:text-primary transition-colors">{{ $res['name'] }}</span>
                                                        <span class="text-[9px] font-mono font-bold text-on-surface-variant/40 tracking-tighter">{{ '@'.$u->handle }}</span>
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- No Selection State: Positioned High -->
                <div class="flex flex-col items-center justify-start text-center space-y-12 animate-in fade-in slide-in-from-top-12 duration-1000 pt-20">
                    <div class="relative">
                        <div class="absolute inset-0 bg-primary/20 blur-[100px] rounded-full animate-pulse"></div>
                        <div class="w-56 h-56 rounded-[4rem] bg-surface-container-low/40 border border-white/10 shadow-[0_40px_100px_rgba(0,0,0,0.5)] flex items-center justify-center relative overflow-hidden group/empty transition-all hover:scale-105 duration-1000 backdrop-blur-3xl">
                            <div class="absolute inset-0 bg-gradient-to-tr from-primary/10 to-transparent opacity-0 group-hover/empty:opacity-100 transition-opacity duration-700"></div>
                            <svg class="w-24 h-24 text-on-surface-variant/10 group-hover/empty:text-primary/20 transition-all duration-1000" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="0.5"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.673.337a4 4 0 01-1.909.508H8a2 2 0 00-2 2v3a1 1 0 001 1h10a1 1 0 001-1v-3.5a1 1 0 01.357-.762l.071-.057zM8 12a4 4 0 100-8 4 4 0 000 8z"/></svg>
                        </div>
                    </div>
                    <div class="space-y-6 max-w-sm">
                        <h3 class="font-display text-5xl font-black text-on-surface/40 tracking-tighter leading-none">{{ __('Select A Workspace') }}</h3>
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-12 h-0.5 bg-gradient-to-r from-transparent via-primary/40 to-transparent"></div>
                            <p class="text-[11px] font-black uppercase tracking-[0.5em] text-on-surface-variant/20 italic">{{ __('Operational Interface Ready') }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($selectedGroup)
        <x-ui.confirm-modal 
            name="delete-group-modal" 
            title="{{ __('Critical Action: Delete Group') }}" 
            content="{{ __('This action is irreversible. All messages, activity feed history, and member links associated with this group will be permanently purged from the system.') }}"
            confirmText="{{ __('Purge Group') }}"
            variant="danger"
            wire:click="deleteGroup({{ $selectedGroup->id }})"
            x-on:click="show = false"
        />
    @endif
</div>
