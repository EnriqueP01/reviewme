<div class="flex flex-col h-full bg-black/40 border border-white/5 rounded-3xl overflow-hidden backdrop-blur-md shadow-2xl relative"
     x-data="{ 
        scrollToBottom() { 
            const container = this.$refs.messages;
            container.scrollTop = container.scrollHeight;
        } 
     }" 
     x-init="scrollToBottom(); $watch('scrollToBottom', () => scrollToBottom())"
     @message-sent.window="scrollToBottom()">
    
    <!-- Messages Area -->
    <div x-ref="messages" 
         class="flex-grow overflow-y-auto p-6 space-y-4 scrollbar-thin scrollbar-thumb-white/10"
         style="max-height: 500px;">
        
        @forelse($messages as $message)
            <div class="flex items-start gap-4 {{ $message->user_id === auth()->id() ? 'flex-row-reverse' : '' }} animate-in fade-in slide-in-from-bottom-2 duration-300">
                <img src="{{ $message->user->profile_photo_url }}" class="w-8 h-8 rounded-xl border border-white/10 shadow-lg">
                
                <div class="flex flex-col {{ $message->user_id === auth()->id() ? 'items-end' : '' }} max-w-[80%]">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-bold text-white/40 uppercase tracking-tighter">{{ $message->user->name }}</span>
                        <span class="text-[9px] text-white/20">{{ $message->created_at->format('H:i') }}</span>
                    </div>
                    
                    <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed shadow-sm border {{ $message->user_id === auth()->id() ? 'bg-primary/20 border-primary/20 text-white rounded-tr-none' : 'bg-white/[0.03] border-white/5 text-white/90 rounded-tl-none' }}">
                        {{ $message->content }}
                    </div>

                    @if($message->user_id === auth()->id())
                        <button wire:click="deleteMessage({{ $message->id }})" class="mt-1 text-[9px] text-white/10 hover:text-red-500 transition-colors uppercase font-bold tracking-widest">
                            {{ __('Purge') }}
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center h-full opacity-20 pointer-events-none space-y-4">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <p class="text-xs uppercase tracking-[0.3em]">{{ __('No intelligence data found') }}</p>
            </div>
        @endforelse
    </div>

    <!-- Input Area -->
    <div class="p-4 bg-white/[0.02] border-t border-white/5">
        <form wire:submit.prevent="sendMessage" class="relative flex items-center gap-3">
            <div class="relative flex-grow">
                <input type="text" 
                       wire:model="newMessage" 
                       placeholder="{{ __('Transmit encrypted message...') }}" 
                       class="w-full bg-black/40 border-white/10 text-sm py-3.5 px-5 rounded-2xl text-white placeholder:text-white/20 focus:ring-1 focus:ring-primary focus:border-primary transition-all pr-12">
                
                <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-2">
                    <span class="text-[9px] text-white/10 font-bold uppercase tracking-widest hidden sm:block">{{ __('Secured') }}</span>
                </div>
            </div>
            
            <button type="submit" 
                    class="p-3.5 bg-primary hover:bg-primary-hover text-white rounded-2xl shadow-lg shadow-primary/20 transition-all active:scale-95 disabled:opacity-50"
                    wire:loading.attr="disabled">
                <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                <div wire:loading class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
            </button>
        </form>
    </div>

    <!-- Live Status Indicator -->
    <div class="absolute top-4 right-6 flex items-center gap-2 pointer-events-none">
        <div class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(34,197,94,0.5)]"></div>
        <span class="text-[8px] font-bold text-green-500/50 uppercase tracking-[0.2em]">{{ __('Uplink Active') }}</span>
    </div>
</div>
