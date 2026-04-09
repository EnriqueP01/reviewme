<div class="flex flex-col h-full bg-black/40 backdrop-blur-3xl overflow-hidden relative group/chat"
     x-data="{ 
        scrollToBottom() { 
            const container = this.$refs.messages;
            container.scrollTop = container.scrollHeight;
        } 
     }" 
     x-init="scrollToBottom(); $watch('scrollToBottom', () => scrollToBottom())"
     @message-sent.window="scrollToBottom()">
    
    <!-- Status Bar -->
    <div class="absolute top-6 left-1/2 -translate-x-1/2 flex items-center gap-3 px-4 py-1.5 bg-emerald-500/10 border border-emerald-500/20 rounded-full backdrop-blur-xl z-20 pointer-events-none opacity-60 group-hover/chat:opacity-100 transition-opacity">
        <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
        <span class="text-[8px] font-black text-emerald-500 uppercase tracking-[0.3em]">{{ __('Connected') }}</span>
    </div>

    <!-- Messages Area -->
    <div x-ref="messages" 
         class="flex-grow overflow-y-auto p-10 space-y-8 scroll-smooth custom-scrollbar"
         style="max-height: 520px;">
        
        @forelse($messages as $message)
            <div @class([
                'flex items-start gap-5 group/msg transition-all duration-500 animate-in fade-in slide-in-from-bottom-4',
                'flex-row-reverse' => $message->user_id === auth()->id()
            ])>
                <div class="relative shrink-0">
                    <img src="{{ $message->user->profile_photo_url }}" class="w-10 h-10 rounded-xl border border-white/10 shadow-2xl object-cover hover:scale-110 transition-transform">
                    @if($message->user_id === auth()->id())
                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-primary border-2 border-[#0d0e12] rounded-full shadow-lg"></div>
                    @endif
                </div>
                
                <div @class([
                    'flex flex-col space-y-2 max-w-[75%]',
                    'items-end font-mono' => $message->user_id === auth()->id()
                ])>
                    <div class="flex items-center gap-3 opacity-30 group-hover/msg:opacity-60 transition-opacity">
                        <span class="text-[9px] font-black uppercase tracking-widest text-on-surface">{{ $message->user->name }}</span>
                        <span class="text-[8px] font-mono font-bold">{{ $message->created_at->format('H:i') }}</span>
                    </div>
                    
                    <div @class([
                        'px-5 py-4 rounded-3xl text-sm leading-relaxed border shadow-2xl relative transition-all duration-300',
                        'bg-primary border-primary/20 text-on-primary rounded-tr-none hover:shadow-primary/20' => $message->user_id === auth()->id(),
                        'bg-white/[0.03] border-white/5 text-on-surface rounded-tl-none hover:bg-white/5 hover:border-white/20' => $message->user_id !== auth()->id()
                    ])>
                        {{ $message->content }}
                    </div>

                    @if($message->user_id === auth()->id())
                        <button wire:click="deleteMessage({{ $message->id }})" class="opacity-0 group-hover/msg:opacity-100 text-[8px] font-black text-rose-500 hover:text-rose-400 transition-all uppercase tracking-[0.2em] pt-1">
                            {{ __('Delete') }}
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center h-full opacity-10 pointer-events-none space-y-8 py-20">
                <div class="w-24 h-24 rounded-[2rem] border-2 border-dashed border-white/10 flex items-center justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <p class="text-[10px] uppercase font-black tracking-[0.6em] text-center max-w-[200px] leading-relaxed">{{ __('No messages yet') }}</p>
            </div>
        @endforelse
    </div>

    <!-- Message Input -->
    <div class="p-8 bg-black/40 border-t border-white/5 relative z-10 backdrop-blur-4xl shadow-[0_-20px_50px_rgba(0,0,0,0.5)]">
        <form wire:submit.prevent="sendMessage" class="flex items-center gap-4 group/form">
            <div class="relative flex-grow">
                <input type="text" 
                       wire:model="newMessage" 
                       placeholder="{{ __('Type a message...') }}" 
                       class="w-full bg-white/[0.02] border border-white/5 text-xs py-4.5 px-6 rounded-[1.5rem] text-on-surface placeholder:text-on-surface-variant/10 focus:ring-1 focus:ring-primary/40 focus:border-primary/40 transition-all shadow-inner font-bold tracking-tight">
            </div>
            
            <button type="submit" 
                    class="w-14 h-14 bg-primary hover:bg-primary-hover text-on-primary rounded-[1.2rem] shadow-2xl shadow-primary/30 flex items-center justify-center transition-all active:scale-90 disabled:opacity-20 group-hover/form:rotate-2"
                    wire:loading.attr="disabled">
                <svg wire:loading.remove class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                <div wire:loading class="w-5 h-5 border-2 border-on-primary/30 border-t-on-primary rounded-full animate-spin"></div>
            </button>
        </form>
    </div>
</div>
