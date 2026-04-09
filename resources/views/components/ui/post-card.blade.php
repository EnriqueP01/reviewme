@props(['post'])

@php
    $myReaction = $post->reactions->first()?->type;
    $votedState = $myReaction === 'mindblown' ? 'up' : ($myReaction === 'optimisable' ? 'down' : '');
    $score = ($post->up_count ?? 0) - ($post->down_count ?? 0);
@endphp

<article wire:key="post-{{ $post->id }}" @class(['group relative']) x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
    <div class="flex items-start gap-12">
        <!-- Karma Lens (Vertical Sidebar) -->
        <div class="flex flex-col items-center gap-3 sticky top-32" 
             x-data="{ 
                 voted: '{{ $votedState }}',
                 score: {{ $score }},
                 isVoting: false,
                 handleVote(dir) {
                    if (this.isVoting) return;
                    this.isVoting = true;
                    if (window.haptic) window.haptic.play(dir);

                    if (dir === 'up') {
                        if (this.voted === 'up') { this.score--; this.voted = ''; }
                        else {
                            this.score += (this.voted === 'down' ? 2 : 1);
                            this.voted = 'up';
                        }
                    } else {
                        if (this.voted === 'down') { this.score++; this.voted = ''; }
                        else {
                            this.score -= (this.voted === 'up' ? 2 : 1);
                            this.voted = 'down';
                        }
                    }
                    setTimeout(() => { this.isVoting = false; }, 500);
                 }
             }"
        >
            <button 
                wire:click="vote({{ $post->id }}, 'up')"
                wire:loading.attr="disabled"
                @click="handleVote('up')"
                :class="voted === 'up' ? 'bg-emerald-500 border-emerald-400 text-on-secondary shadow-[0_0_30px_rgba(52,211,153,0.4)] scale-110' : 'bg-surface-container-low text-on-surface-variant hover:text-emerald-400 hover:border-emerald-500/40 active:scale-95 border-white/5'"
                class="w-14 h-14 rounded-2xl border flex items-center justify-center transition-all duration-300 group/vote"
            >
                <svg class="w-6 h-6 transition-transform group-hover/vote:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
            </button>
            
            <div class="py-2 flex flex-col items-center select-none">
                <span class="font-display font-black text-3xl tracking-tighter transition-all duration-300"
                      :class="voted === 'up' ? 'text-emerald-400' : (voted === 'down' ? 'text-rose-400' : 'text-on-surface/40')"
                      x-text="score"></span>
                <span class="text-[8px] font-black uppercase tracking-[0.3em] text-on-surface-variant/20 mt-1">{{ __('Score') }}</span>
            </div>
            
            <button 
                wire:click="vote({{ $post->id }}, 'down')"
                wire:loading.attr="disabled"
                @click="handleVote('down')"
                :class="voted === 'down' ? 'bg-rose-500 text-black border-rose-400 shadow-[0_0_20px_rgba(244,63,94,0.3)] scale-110' : 'bg-surface-container-low text-on-surface-variant hover:text-rose-400 hover:border-rose-500/40 active:scale-95 border-white/5'"
                class="w-14 h-14 rounded-2xl border flex items-center justify-center transition-all duration-300 group/vote"
            >
                <svg class="w-6 h-6 transition-transform group-hover/vote:translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </button>
        </div>

        <!-- Main Article Content -->
        <div class="flex-grow space-y-8">
            <!-- Metadata Row -->
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-6">
                    <a href="{{ route('profile.show', $post->user->handle) }}" wire:navigate class="group/author flex items-center gap-6">
                        <x-ui.avatar :model="$post->user" size="lg" class="group-hover/author:scale-105 transition-all duration-500 shadow-xl" />
                        
                        <div class="flex flex-col">
                            <div class="flex items-center gap-3">
                                <span class="text-lg font-black text-on-surface tracking-tight group-hover/author:text-primary transition-colors">{{ $post->user->name }}</span>
                                <span class="text-xs font-mono font-bold text-primary tracking-wider font-black opacity-40 group-hover/author:opacity-100 transition-opacity">@<span>{{ $post->user->handle }}</span></span>
                            </div>
                            <span class="text-[10px] uppercase tracking-[0.2em] text-on-surface-variant font-black opacity-40 mt-1 flex items-center gap-2">
                                {{ $post->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </a>
                </div>
                
                <div class="opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center gap-4">
                    <x-ui.button variant="ghost" size="sm" class="!bg-primary/5 border-primary/10 hover:!bg-primary/20 hover:!text-primary !rounded-xl !relative group/inspect-btn" href="{{ route('posts.detail', $post->id) }}" static="true">
                        <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <span class="pointer-events-none">{{ __('View Post') }}</span>
                    </x-ui.button>
                </div>
            </div>

            <!-- Content Headers -->
            <div class="relative group/header cursor-default">
                 <div class="flex items-start gap-6 transition-all duration-700 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:translate-y-[-4px]">
                    <div class="flex-grow min-h-[48px] flex flex-col justify-center">
                        <h3 class="text-3xl font-display font-black text-on-surface leading-tight tracking-tighter group-active:scale-[0.98] transition-all duration-500">
                            {{ $post->title }}
                        </h3>
                        
                        <div class="max-h-0 opacity-0 group-hover:max-h-32 group-hover:opacity-100 transition-all duration-700 ease-in-out overflow-hidden">
                            <div class="pt-4 space-y-3">
                                @if($post->short_description)
                                    <p class="text-lg text-on-surface font-semibold leading-relaxed max-w-4xl opacity-90">
                                        {{ $post->short_description }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                 </div>
            </div>

            <!-- The Code Lens -->
            <div class="transition-all duration-700 transform group-hover:scale-[1.002]">
                <x-ui.code-block 
                    :title="$post->title" 
                    :snippets="$post->snippets"
                    :type="$post->lens ?? 'elegant'" 
                    :goals="$post->review_goals"
                    :context="$post->context"
                />
            </div>

            <!-- Tags & Stats -->
            <div class="flex items-center gap-8 pt-6 border-t border-white/[0.03]">
                 <div class="flex gap-2">
                    @foreach(explode(',', $post->lens ?? 'Review') as $l)
                        @php $lKey = strtolower(trim($l)); @endphp
                        <span 
                            class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] transition-all hover:scale-105 cursor-default border"
                            style="color: var(--lens-{{ $lKey }}); background-color: rgba(var(--lens-{{ $lKey }}-rgb), 0.1); border-color: rgba(var(--lens-{{ $lKey }}-rgb), 0.3); box-shadow: 0 0 15px rgba(var(--lens-{{ $lKey }}-rgb), 0.1);"
                        >#{{ strtoupper(trim($l)) }}</span>
                    @endforeach
                    <span class="px-4 py-1.5 rounded-xl bg-surface-container-highest text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/60 border border-white/[0.03] hover:border-secondary/30 transition-colors cursor-default">#{{ strtoupper($post->latestSnippet->language ?? 'PHP') }}</span>
                 </div>
                 <div class="flex-grow"></div>
                  <div class="flex items-center gap-8 text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/30">
                    <a href="{{ route('posts.detail', $post->id) }}" class="flex items-center gap-3 group/stat cursor-pointer hover:text-primary transition-all duration-300">
                        <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center group-hover/stat:bg-primary/10 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <span class="{{ $post->full_reviews_count > 0 ? 'text-primary' : '' }}">{{ $post->full_reviews_count ?? 0 }} {{ __('Reviews') }}</span>
                    </a>
                  </div>
            </div>
        </div>
    </div>
</article>
