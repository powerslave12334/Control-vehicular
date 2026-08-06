<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-black text-slate-900">Centro de Notificaciones</h2>
            <p class="text-sm text-slate-500 mt-1">
                {{ $unreadCount }} no leídas
                @if($unreadCount > 0)
                    <button wire:click="markAllAsRead" class="ml-3 text-xs text-[#F71F96] hover:underline font-bold">Marcar todas como leídas</button>
                @endif
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="togglePreferences"
                class="px-4 py-2 rounded-xl text-xs font-bold transition
                {{ $showPreferences ? 'bg-[#F71F96] text-white' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300' }}">
                Preferencias
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex gap-2 mb-6">
        @foreach(['all' => 'Todas', 'unread' => 'No leídas', 'archived' => 'Archivadas'] as $key => $label)
            <button wire:click="setFilter('{{ $key }}')"
                class="px-4 py-2 rounded-xl text-xs font-bold transition
                {{ $filter === $key ? 'bg-[#F71F96] text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-300' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Preferences Panel --}}
    @if($showPreferences)
        <div class="mb-6 bg-white rounded-2xl border border-slate-200 p-6">
            <h3 class="text-sm font-black text-slate-900 mb-4">Preferencias de Notificación</h3>

            @if(empty($preferences))
                <p class="text-sm text-slate-500 mb-4">Activa o desactiva tipos de evento. Por defecto están activadas.</p>
            @endif

            <div class="space-y-3 max-h-80 overflow-y-auto">
                @foreach($preferences as $pref)
                    <div class="flex items-center justify-between py-2 px-3 rounded-xl bg-slate-50">
                        <span class="text-sm font-semibold text-slate-700">{{ $pref['event_type'] }}</span>
                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-1.5 text-xs text-slate-500">
                                <input type="checkbox" wire:model="preferences.{{ $loop->index }}.in_app" class="rounded border-slate-300 text-[#F71F96] focus:ring-[#F71F96]">
                                In-app
                            </label>
                            <label class="flex items-center gap-1.5 text-xs text-slate-500">
                                <input type="checkbox" wire:model="preferences.{{ $loop->index }}.email" class="rounded border-slate-300 text-[#F71F96] focus:ring-[#F71F96]">
                                Email
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 flex gap-2">
                <button wire:click="savePreferences"
                    class="px-4 py-2 bg-[#F71F96] text-white rounded-xl text-xs font-bold hover:bg-[#F71F96]/90 transition">
                    Guardar preferencias
                </button>
                <button wire:click="$set('showPreferences', false)"
                    class="px-4 py-2 bg-white text-slate-600 border border-slate-200 rounded-xl text-xs font-bold hover:border-slate-300 transition">
                    Cancelar
                </button>
            </div>
        </div>
    @endif

    {{-- Notification List --}}
    <div class="space-y-2">
        @forelse($notifications as $notification)
            <div class="bg-white rounded-2xl border border-slate-200 p-4 transition hover:shadow-sm
                {{ !$notification->read ? 'border-l-4 border-l-[#F71F96] bg-[#F71F96]/5' : '' }}
                {{ $notification->priority === 'critical' ? 'border-l-red-500' : '' }}
                {{ $notification->priority === 'high' ? 'border-l-amber-500' : '' }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-extrabold uppercase tracking-wider
                                {{ $notification->type === 'critical' ? 'text-red-600' : '' }}
                                {{ $notification->type === 'warning' ? 'text-amber-600' : '' }}
                                {{ $notification->type === 'info' ? 'text-blue-600' : '' }}">
                                {{ match($notification->type) { 'critical' => 'Crítica', 'warning' => 'Advertencia', 'info' => 'Info', default => $notification->type } }}
                            </span>
                            @if($notification->priority === 'critical')
                                <span class="px-1.5 py-0.5 bg-red-100 text-red-700 rounded text-[10px] font-extrabold uppercase">Crítica</span>
                            @elseif($notification->priority === 'high')
                                <span class="px-1.5 py-0.5 bg-amber-100 text-amber-700 rounded text-[10px] font-extrabold uppercase">Alta</span>
                            @endif
                            @if(!$notification->read)
                                <span class="w-2 h-2 rounded-full bg-[#F71F96]"></span>
                            @endif
                        </div>
                        <h4 class="text-sm font-bold text-slate-900">{{ $notification->title }}</h4>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $notification->message }}</p>
                        <p class="text-[10px] text-slate-400 mt-1.5 font-mono">{{ $notification->created_at?->diffForHumans() ?? 'N/A' }}</p>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        @if(!$notification->read)
                            <button wire:click="markAsRead({{ $notification->id }})"
                                class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition"
                                title="Marcar como leída">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </button>
                        @else
                            <button wire:click="markAsUnread({{ $notification->id }})"
                                class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition"
                                title="Marcar como no leída">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5"/></svg>
                            </button>
                        @endif
                        @if(!$notification->archived)
                            <button wire:click="archive({{ $notification->id }})"
                                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition"
                                title="Archivar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                            </button>
                        @else
                            <button wire:click="unarchive({{ $notification->id }})"
                                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition"
                                title="Restaurar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            </button>
                        @endif
                        <button wire:click="delete({{ $notification->id }})"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition"
                            title="Eliminar"
                            onclick="confirm('¿Eliminar esta notificación?') || event.stopImmediatePropagation()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <p class="text-sm text-slate-400 font-semibold">No hay notificaciones</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</div>
