
<div>
<div class="bg-white rounded-3xl shadow-xs border border-slate-200 p-6 md:p-8 space-y-6">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-black text-slate-800">Bitácora de Portería</h2>
            <p class="text-xs text-slate-400 font-semibold mt-0.5">Todas las entradas y salidas registradas</p>
        </div>
        <button wire:click="exportCsv"
            class="text-xs font-bold text-white bg-[#E72085] hover:bg-[#C9106A] px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition shadow-sm shadow-[#E72085]/20">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Exportar CSV
        </button>
    </div>

    {{-- FILTERS --}}
    <div class="flex flex-col lg:flex-row gap-3 items-start lg:items-center justify-between">
        <div class="flex flex-wrap gap-2 items-center">
            <input wire:model.live.debounce.300ms="filterSearch" type="text" placeholder="Buscar (folio, conductor, placa, notas...)"
                class="px-3 py-2 rounded-lg border border-slate-200 text-xs font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] w-56">
            <select wire:model.live="filterType" class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                <option value="">Sentido: Todos</option>
                <option value="entry">Entrada</option>
                <option value="exit">Salida</option>
            </select>
            <select wire:model.live="filterVehicleId" class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                <option value="">Vehículo: Todos</option>
                @foreach($vehicles as $v)
                <option value="{{ $v->id }}">{{ $v->plate }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterFuel" class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                <option value="">Gasolina: Todas</option>
                @foreach($fuelLevels as $fl)
                <option value="{{ $fl->value }}">{{ $fl->label }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterSpare" class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                <option value="">Llanta refacción: Todos</option>
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
            <input wire:model.live="filterDateFrom" type="date" title="Desde"
                class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
            <input wire:model.live="filterDateTo" type="date" title="Hasta"
                class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
            @if($filterSearch || $filterType || $filterVehicleId || $filterFuel || $filterSpare || $filterDateFrom || $filterDateTo)
            <button wire:click="clearFilters" class="px-3 py-2 rounded-lg text-[11px] font-bold text-slate-500 hover:bg-slate-100 transition">Limpiar</button>
            @endif
        </div>
        @if($logs->total() > 0)
        <p class="text-[10px] text-slate-400 font-bold">
            Mostrando {{ $logs->firstItem() }}-{{ $logs->lastItem() }} de {{ $logs->total() }}
        </p>
        @endif
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto rounded-xl border border-slate-200">
        <table class="w-full min-w-[980px] text-left">
            <thead>
                <tr class="bg-slate-50 text-[9px] font-black text-slate-400 uppercase tracking-wider">
                    <th class="px-3 py-2.5 border-b border-slate-200 whitespace-nowrap">Fecha</th>
                    <th class="px-2 py-2.5 border-b border-slate-200 whitespace-nowrap">Hora</th>
                    <th class="px-2 py-2.5 border-b border-slate-200 whitespace-nowrap">Sentido</th>
                    <th class="px-2 py-2.5 border-b border-slate-200 whitespace-nowrap">Folio</th>
                    <th class="px-2 py-2.5 border-b border-slate-200 whitespace-nowrap">Placa</th>
                    <th class="px-2 py-2.5 border-b border-slate-200 whitespace-nowrap">Conductor</th>
                    <th class="px-2 py-2.5 border-b border-slate-200 text-right whitespace-nowrap">Km</th>
                    <th class="px-2 py-2.5 border-b border-slate-200 text-right whitespace-nowrap">Gas</th>
                    <th class="px-2 py-2.5 border-b border-slate-200 whitespace-nowrap">Llanta</th>
                    <th class="px-2 py-2.5 border-b border-slate-200 whitespace-nowrap">Estado</th>
                    <th class="px-2 py-2.5 border-b border-slate-200 whitespace-nowrap">Confirmado</th>
                    <th class="px-3 py-2.5 border-b border-slate-200 text-right whitespace-nowrap">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr data-log="{{ $log->id }}" wire:key="log-{{ $log->id }}"
                    class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="px-3 py-3 font-mono text-xs font-bold text-slate-600 whitespace-nowrap">
                        {{ $log->logged_at?->format('d/m/Y') ?? '—' }}</td>
                    <td class="px-2 py-3 font-mono text-xs font-bold tabular-nums text-slate-700 whitespace-nowrap">
                        {{ $log->logged_at?->format('H:i') ?? '—' }}</td>
                    <td class="px-2 py-3 whitespace-nowrap">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-mono text-[9px] font-black uppercase tracking-wider {{ $log->type->value === 'entry' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            {{ $log->type->value === 'entry' ? 'Entrada' : 'Salida' }}
                        </span>
                    </td>
                    <td class="px-2 py-3 font-mono text-xs font-bold text-slate-700 whitespace-nowrap">{{ $log->route_folio ?? '—' }}</td>
                    <td class="px-2 py-3 font-mono text-xs font-black text-slate-800 whitespace-nowrap">{{ $log->vehicle?->plate ?? '—' }}</td>
                    <td class="px-2 py-3 text-xs text-slate-600 whitespace-nowrap">{{ $log->driver_name ?? '—' }}</td>
                    <td class="px-2 py-3 text-right font-mono text-xs tabular-nums text-slate-700 whitespace-nowrap">{{ $log->initial_odometer !== null ? number_format($log->initial_odometer, 0) : '—' }}</td>
                    <td class="px-2 py-3 text-right font-mono text-xs tabular-nums text-slate-700 whitespace-nowrap">{{ $log->fuel_level !== null ? $log->fuel_level.'%' : '—' }}</td>
                    <td class="px-2 py-3 whitespace-nowrap">
                        @if($log->has_spare_tire)
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-700">Sí</span>
                        @else
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-slate-100 text-slate-500">No</span>
                        @endif
                    </td>
                    <td class="px-2 py-3 text-xs text-slate-600 whitespace-nowrap">{{ $log->vehicle_condition ?? '—' }}</td>
                    <td class="px-2 py-3 whitespace-nowrap">
                        @if($log->confirmed)
                        <svg class="w-4 h-4 text-emerald-500 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                        <span class="text-slate-300">—</span>
                        @endif
                    </td>
                    <td class="px-3 py-3 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-0.5">
                            <button wire:click="openDetail({{ $log->id }})" title="Ver detalle"
                                class="p-1.5 text-slate-400 hover:text-[#E72085] rounded-lg hover:bg-pink-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                            <button wire:click="confirmDelete({{ $log->id }})" title="Eliminar"
                                class="p-1.5 text-slate-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12">
                        <div class="py-14 px-6 text-center">
                            <div class="mx-auto w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                            <p class="mt-3 text-sm font-black text-slate-700">No hay registros</p>
                            <p class="mt-1 text-xs text-slate-400">Ajusta los filtros o registra un pase desde Portería.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="px-1 py-1">
        {{ $logs->links() }}
    </div>
    @endif
</div>

{{-- DETAIL MODAL --}}
<div x-data="{ show: @entangle('showDetail') }"
    x-show="show"
    x-cloak
    x-transition.opacity.duration.200ms
    @keydown.escape.window="show = false"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display: none;">
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="show = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200 w-[640px] max-h-[90vh] overflow-y-auto p-6"
        @click.outside="show = false">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-black text-slate-800">
                Pase · {{ $detailRecord?->route_folio ?? '—' }}
            </h3>
            <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-600 transition p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        @if($detailRecord)
        <div class="space-y-5">
            <div>
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Información General</h4>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div><span class="text-gray-400">Sentido</span>
                        <p class="font-bold {{ $detailRecord->type->value === 'entry' ? 'text-emerald-600' : 'text-red-600' }}">{{ $detailRecord->type->value === 'entry' ? 'Entrada' : 'Salida' }}</p>
                    </div>
                    <div><span class="text-gray-400">Fecha / Hora</span><p class="font-bold text-gray-800">{{ $detailRecord->logged_at?->format('d/m/Y H:i') ?? '—' }}</p></div>
                    <div><span class="text-gray-400">Vehículo</span><p class="font-bold text-gray-800">{{ $detailRecord->vehicle?->plate ?? '—' }} {{ $detailRecord->vehicle ? '· '.$detailRecord->vehicle->brand.' '.$detailRecord->vehicle->model : '' }}</p></div>
                    <div><span class="text-gray-400">Tipo de vehículo</span><p class="font-bold text-gray-800">{{ $detailRecord->vehicle?->vehicle_type ?? '—' }}</p></div>
                    <div><span class="text-gray-400">Conductor</span><p class="font-bold text-gray-800">{{ $detailRecord->driver_name ?? '—' }}</p></div>
                    <div><span class="text-gray-400">Folio</span><p class="font-bold text-gray-800">{{ $detailRecord->route_folio ?? '—' }}</p></div>
                    <div><span class="text-gray-400">Registrado por</span><p class="font-bold text-gray-800">{{ $detailRecord->creator?->name ?? '—' }}</p></div>
                </div>
            </div>

            <div>
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Vehículo</h4>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div><span class="text-gray-400">Kilometraje</span><p class="font-bold text-gray-800">{{ $detailRecord->initial_odometer !== null ? number_format($detailRecord->initial_odometer, 0).' km' : '—' }}</p></div>
                    <div><span class="text-gray-400">Gasolina</span><p class="font-bold text-gray-800">{{ $detailRecord->fuel_level !== null ? $detailRecord->fuel_level.'%' : '—' }}</p></div>
                    <div><span class="text-gray-400">Llanta de refacción</span>
                        <p class="font-bold {{ $detailRecord->has_spare_tire ? 'text-emerald-600' : 'text-slate-500' }}">{{ $detailRecord->has_spare_tire ? 'Sí' : 'No' }}</p>
                    </div>
                    <div><span class="text-gray-400">Estado</span><p class="font-bold text-gray-800">{{ $detailRecord->vehicle_condition ?? '—' }}</p></div>
                    <div><span class="text-gray-400">Confirmado</span>
                        <p class="font-bold {{ $detailRecord->confirmed ? 'text-emerald-600' : 'text-red-500' }}">{{ $detailRecord->confirmed ? 'Sí' : 'No' }}</p>
                    </div>
                </div>
            </div>

            @if(! empty($detailRecord->checklist))
            <div>
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Check list de salida</h4>
                <div class="flex flex-wrap gap-1.5">
                    @foreach($detailRecord->checklist as $ck)
                    <span class="px-2 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-wider">{{ $checklistLabels[$ck] ?? $ck }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            @if($detailRecord->notes)
            <div>
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Observaciones</h4>
                <p class="text-xs text-gray-600 italic">&ldquo;{{ $detailRecord->notes }}&rdquo;</p>
            </div>
            @endif

            <div>
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Evidencias</h4>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold mb-1">Foto del vehículo</p>
                        @if($detailRecord->photo)
                        <a href="{{ \Illuminate\Support\Facades\Storage::url($detailRecord->photo) }}" target="_blank">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($detailRecord->photo) }}" alt="Foto vehículo" class="w-full h-28 object-cover rounded-lg border border-slate-200">
                        </a>
                        @else
                        <div class="w-full h-28 rounded-lg border border-dashed border-slate-200 bg-slate-50 flex items-center justify-center text-[10px] text-slate-300 font-bold">Sin foto</div>
                        @endif
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold mb-1">Foto del conductor</p>
                        @if($detailRecord->driver_photo)
                        <a href="{{ \Illuminate\Support\Facades\Storage::url($detailRecord->driver_photo) }}" target="_blank">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($detailRecord->driver_photo) }}" alt="Foto conductor" class="w-full h-28 object-cover rounded-lg border border-slate-200">
                        </a>
                        @else
                        <div class="w-full h-28 rounded-lg border border-dashed border-slate-200 bg-slate-50 flex items-center justify-center text-[10px] text-slate-300 font-bold">Sin foto</div>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Firma</h4>
                @if($detailRecord->signature)
                <img src="{{ $detailRecord->signature }}" alt="Firma" class="max-h-28 bg-slate-50 rounded-lg border border-slate-200 p-2 mx-auto">
                @else
                <p class="text-xs text-slate-300 font-bold">Sin firma</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
</div>
