<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <x-ui.back-button fallback="{{ route('dashboard') }}" />
    </div>

    <!-- Header Section -->
    <div class="mb-10 space-y-2">
        <div class="flex items-center gap-3 text-primary">
            <h1 class="text-3xl font-black tracking-tighter uppercase">{{ __('Experts') }}</h1>
        </div>
        <p class="text-xs text-on-surface-variant/60 italic">
            {{ __('Top contributors elevating architectural quality.') }}
        </p>
    </div>

    <!-- Leaderboard Table -->
    <div class="glass-panel rounded-xl overflow-hidden border border-white/5 shadow-xl">
        <table class="w-full text-left border-collapse table-fixed">
            <thead>
                <tr class="bg-white/[0.02] border-b border-white/5">
                    <th class="w-16 px-4 py-3 text-[8px] font-black uppercase tracking-widest text-on-surface-variant/20">{{ __('Rank') }}</th>
                    <th class="px-4 py-3 text-[8px] font-black uppercase tracking-widest text-on-surface-variant/20">{{ __('Contributor') }}</th>
                    <th class="hidden md:table-cell px-4 py-3 text-[8px] font-black uppercase tracking-widest text-on-surface-variant/20">{{ __('Role') }}</th>
                    <th class="w-24 px-4 py-3 text-[8px] font-black uppercase tracking-widest text-on-surface-variant/20 text-right">{{ __('Karma') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($topUsers as $index => $user)
                    <tr class="group hover:bg-white/[0.01] transition-colors">
                        <td class="px-4 py-2 text-center">
                            <span @class([
                                "text-xs font-mono font-bold",
                                "text-amber-400" => $index === 0,
                                "text-on-surface-variant/30" => $index > 0,
                            ])>
                                #{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            @php $expertRoute = $user->handle ? route('profile.show', $user->handle) : '#'; @endphp
                            <a href="{{ $expertRoute }}" wire:navigate class="flex items-center gap-3 group/expert overflow-hidden">
                                <div class="w-7 h-7 shrink-0 rounded-lg overflow-hidden border border-white/10">
                                    <x-ui.avatar :model="$user" size="xs" :link="false" class="w-full h-full shadow-none border-none hover:scale-100" />
                                </div>
                                <div class="truncate">
                                    <div class="text-sm font-bold text-on-surface line-clamp-1 group-hover/expert:text-primary transition-colors leading-tight">{{ $user->name }}</div>
                                    <div class="text-[9px] font-mono text-on-surface-variant/30 truncate">{{ $user->handle ? '@'.$user->handle : 'expert' }}</div>
                                </div>
                            </a>
                        </td>
                        <td class="hidden md:table-cell px-4 py-2">
                            <span class="text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded-md bg-white/5 border border-white/5 text-on-surface-variant/40">
                                {{ $user->karma_level['label'] ?? 'Expert' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-right">
                           <div class="flex flex-col items-end">
                                <span class="text-sm font-black text-on-surface tracking-tighter">{{ number_format($user->reputation_score) }}</span>
                                <span class="text-[7px] font-black uppercase tracking-widest opacity-20">Pts</span>
                           </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-12 text-center">
                            <span class="text-[10px] font-black uppercase tracking-widest opacity-20">{{ __('Searching for experts...') }}</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
