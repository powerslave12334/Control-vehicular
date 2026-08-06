<div class="bg-white rounded-3xl shadow-xs border border-slate-200 p-6 md:p-8 space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-base font-bold text-gray-800">Operadores ({{ $operators->total() }})</h3>
        <span class="text-[10px] text-gray-400">Usuarios con roles Operador, Chofer e Instalador</span>
    </div>

    {{-- FILTERS --}}
    <div class="flex flex-wrap items-center gap-2">
        <input wire:model.live.debounce.300ms="filterSearch" type="text"
            placeholder="Buscar (nombre, email, teléfono...)"
            class="px-3 py-2 rounded-lg border border-slate-200 text-xs font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] w-56">
        <select wire:model.live="filterRole"
            class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
            <option value="">Rol: Todos</option>
            @foreach($operatorRoles as $role)
            <option value="{{ $role }}">{{ $role }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterStatus"
            class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
            <option value="">Estatus: Todos</option>
            @foreach($userStatuses as $us)
            <option value="{{ $us->value }}">{{ $us->label }}</option>
            @endforeach
        </select>
        @if($filterSearch || $filterRole || $filterStatus)
        <button wire:click="clearFilters"
            class="px-3 py-2 rounded-lg text-[11px] font-bold text-slate-500 hover:bg-slate-100 transition">Limpiar</button>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-gray-600">
            <thead class="bg-slate-50 text-gray-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="py-2.5 px-3 rounded-l-lg">Nombre</th>
                    <th class="py-2.5 px-3">Rol</th>
                    <th class="py-2.5 px-3">Teléfono</th>
                    <th class="py-2.5 px-3">Email</th>
                    <th class="py-2.5 px-3 rounded-r-lg">Estatus</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($operators as $o)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="py-3 px-3 font-bold text-gray-800">{{ $o->name }}</td>
                    <td class="py-3 px-3 text-gray-600">{{ $o->role }}</td>
                    <td class="py-3 px-3 text-gray-600">{{ $o->phone ?? '—' }}</td>
                    <td class="py-3 px-3 text-gray-600">{{ $o->email ?? '—' }}</td>
                    <td class="py-3 px-3">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                            @switch($o->status?->value ?? $o->status)
                                @case('Activo') bg-green-100 text-green-700 @break
                                @case('Inactivo') bg-red-100 text-red-700 @break
                                @default bg-gray-100 text-gray-700
                            @endswitch">
                            {{ $o->status?->value ?? $o->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-12 text-gray-400 text-sm font-bold">No hay usuarios con esos
                        roles</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($operators->hasPages())
    <div class="pt-2">
        {{ $operators->links() }}
    </div>
    @endif
</div>
