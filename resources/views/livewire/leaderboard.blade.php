<div class="max-w-7xl mx-auto px-6 py-12">
    <!-- Back Button -->
    <div class="mb-8 hover-trigger">
        <x-ui.back-button fallback="{{ route('dashboard') }}" />
    </div>

    <!-- Header Section -->
    <div class="mb-16 space-y-4 animate-in fade-in slide-in-from-top-8 duration-1000">
        <div class="flex items-center gap-4 text-primary">
            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center border border-primary/20 shadow-[0_0_20px_rgba(190,194,255,0.1)]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <h1 class="text-4xl lg:text-5xl font-black tracking-tighter uppercase">{{ __('Top Experts') }}</h1>
        </div>
        <p class="text-on-surface-variant italic max-w-2xl leading-relaxed">
            {{ __('Recognizing the most helpful contributors who consistently elevate the architectural quality of the platform.') }}
        </p>
    </div>

    <!-- Leaderboard Table -->
    <div class="glass-panel rounded-round-4 overflow-hidden border border-white/5 shadow-2xl animate-in fade-in slide-in-from-bottom-8 duration-1000 delay-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/[0.02] border-b border-white/5">
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/40">{{ __('Rank') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/40">{{ __('Expert') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/40">{{ __('Reputation') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.3em] text-on-surface-variant/40">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($topUsers as $index => $user)
                        <tr class="group hover:bg-white/[0.01] transition-colors">
                            <td class="px-8 py-6">
                                <div @class([
                                    "w-10 h-10 rounded-full flex items-center justify-center font-mono text-sm font-bold border",
                                    "bg-amber-400/10 border-amber-400/20 text-amber-400 shadow-[0_0_20px_rgba(251,191,36,0.2)]" => $index === 0,
                                    "bg-slate-300/10 border-slate-300/20 text-slate-300" => $index === 1,
                                    "bg-amber-700/10 border-amber-700/20 text-amber-700" => $index === 2,
                                    "bg-white/5 border-white/10 text-on-surface-variant/40" => $index > 2,
                                ])>
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" class="w-12 h-12 rounded-xl object-cover border border-white/10 group-hover:border-primary/40 transition-colors">
                                        @if($index < 3)
                                            <div class="absolute -top-2 -right-2 transform translate-x-1 -translate-y-1">
                                                <svg class="w-5 h-5 @if($index === 0) text-amber-400 @elseif($index === 1) text-slate-300 @else text-amber-700 @endif" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-on-surface group-hover:text-primary transition-colors">{{ $user->name }}</div>
                                        <div class="text-[10px] font-mono text-on-surface-variant/40 uppercase tracking-widest">{{ $user->bio ?? __('Elite Contributor') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-xl font-black text-on-surface tracking-tighter">{{ number_format($user->reputation_score) }}</span>
                                    <span class="text-[9px] font-black uppercase tracking-widest text-primary/60">{{ __('Karma Points') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-400/5 border border-emerald-400/10 text-[9px] font-black uppercase tracking-widest text-emerald-400">
                                    <span class="w-1 h-1 rounded-full bg-emerald-400 animate-pulse"></span>
                                    {{ __('Active') }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-24 text-center">
                                <p class="text-on-surface-variant italic">{{ __('No experts detected in the current cycle.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
