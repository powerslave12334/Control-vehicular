<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <button type="button" @click="open = !open"
        class="relative p-2 rounded-xl hover:bg-slate-100 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-[#F71F96]/40"
        title="Notificaciones" aria-label="Notificaciones">
        <svg class="w-5 h-5 {{ $unreadCount > 0 ? 'text-[#F71F96]' : 'text-slate-500' }}" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($unreadCount > 0)
        <span
            class="absolute -top-0.5 -right-0.5 w-4.5 h-4.5 bg-red-500 text-white text-[10px] font-black rounded-full flex items-center justify-center shadow-sm"
            style="width: 15px; height: 15px; top: 6px; left: 6px; font-size: 12px;">
            {{ min($unreadCount, 99) }}
        </span>
        @endif
    </button>

    <div x-show="open" x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-[360px] max-w-[calc(100vw-2rem)] bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-300/40 z-50 overflow-hidden">

        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
            <h3 class="text-sm font-black text-slate-900">Notificaciones</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead"
                    class="text-[11px] text-[#F71F96] hover:underline font-bold focus:outline-none focus-visible:ring-2 focus-visible:ring-[#F71F96]/40 rounded">
                    Marcar todas leídas
                </button>
            @else
                <span class="text-[11px] text-slate-400 font-semibold">{{ $unreadCount }} no leídas</span>
            @endif
        </div>

        <div class="flex gap-1.5 px-4 py-2.5 border-b border-slate-100">
            @foreach(['all' => 'Todas', 'unread' => 'No leídas', 'archived' => 'Archivadas'] as $key => $label)
                <button wire:click="setFilter('{{ $key }}')"
                    class="px-3 py-1.5 rounded-lg text-[11px] font-bold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-[#F71F96]/40
                    {{ $filter === $key ? 'bg-[#F71F96] text-white' : 'text-slate-500 hover:bg-slate-100' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="max-h-72 overflow-y-auto divide-y divide-slate-100">
            @forelse($notifications as $n)
                <div class="px-4 py-3 flex items-start gap-3 {{ !$n->read ? 'bg-[#F71F96]/5' : '' }}">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5">
                            @if(!$n->read)
                                <span class="w-2 h-2 rounded-full bg-[#F71F96] shrink-0"></span>
                            @endif
                            <h4 class="text-xs font-bold text-slate-900 truncate">{{ $n->title }}</h4>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-2">{{ $n->message }}</p>
                        <p class="text-[10px] text-slate-400 mt-1 font-mono">{{ $n->created_at?->diffForHumans() ?? 'N/A' }}</p>
                    </div>
                    @if(!$n->read)
                        <button wire:click="markAsRead({{ $n->id }})"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition shrink-0 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500/40"
                            title="Marcar como leída">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </button>
                    @endif
                </div>
            @empty
                <div class="px-4 py-10 text-center">
                    <p class="text-xs text-slate-400 font-semibold">No hay notificaciones</p>
                </div>
            @endforelse
        </div>

        <div class="px-4 py-2.5 border-t border-slate-100 bg-slate-50/50">
            <a href="{{ route('notifications') }}"
                class="text-xs text-[#F71F96] font-bold hover:underline flex items-center gap-1 justify-center rounded focus:outline-none focus-visible:ring-2 focus-visible:ring-[#F71F96]/40">
                Ver todas las notificaciones →
            </a>
        </div>
    </div>
</div>
