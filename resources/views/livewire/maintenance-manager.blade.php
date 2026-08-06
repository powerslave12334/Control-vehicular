
@php
$statusStyles = [
    'programado'  => ['bg-amber-100 text-amber-700', 'Programado'],
    'en_progreso' => ['bg-blue-100 text-blue-700', 'En Progreso'],
    'completado'  => ['bg-emerald-100 text-emerald-700', 'Completado'],
    'cancelado'   => ['bg-red-100 text-red-600', 'Cancelado'],
];
@endphp

<div x-data="{ lightbox: false, lightboxSrc: '' }" class="bg-white rounded-3xl shadow-xs border border-slate-200 p-6 md:p-8 space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-black text-slate-800">Servicios de Mantenimiento Vehicular</h2>
        <button wire:click="create" class="text-xs font-bold text-[#008FD3] bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Registrar Mantenimiento
        </button>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-slate-50 rounded-xl border border-slate-200 p-3">
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Total</p>
            <p class="text-lg font-black text-slate-800 mt-0.5">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-amber-50 rounded-xl border border-amber-200 p-3">
            <p class="text-[10px] font-extrabold text-amber-600 uppercase tracking-wider">Programados</p>
            <p class="text-lg font-black text-amber-800 mt-0.5">{{ $stats['programado'] }}</p>
        </div>
        <div class="bg-blue-50 rounded-xl border border-blue-200 p-3">
            <p class="text-[10px] font-extrabold text-blue-600 uppercase tracking-wider">En Progreso</p>
            <p class="text-lg font-black text-blue-800 mt-0.5">{{ $stats['en_progreso'] }}</p>
        </div>
        <div class="bg-emerald-50 rounded-xl border border-emerald-200 p-3">
            <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider">Completados</p>
            <p class="text-lg font-black text-emerald-800 mt-0.5">{{ $stats['completado'] }}</p>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="flex flex-col lg:flex-row gap-3 items-start lg:items-center justify-between">
        <div class="flex flex-wrap gap-2 items-center">
            <input wire:model.live.debounce.300ms="filterSearch" type="text" placeholder="Buscar (placa, taller, descripción...)"
                   class="px-3 py-2 rounded-lg border border-slate-200 text-xs font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] w-56">
            <select wire:model.live="filterType" class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                <option value="">Tipo: Todos</option>
                @foreach($maintenanceTypes as $mt)
                <option value="{{ $mt->value }}">{{ $mt->label }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterStatus" class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                <option value="">Status: Todos</option>
                @foreach($maintenanceStatuses as $ms)
                <option value="{{ $ms->value }}">{{ $ms->label }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterVehicleId" class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                <option value="">Vehículo: Todos</option>
                @foreach($vehicles as $v)
                <option value="{{ $v->id }}">{{ $v->plate }}</option>
                @endforeach
            </select>
            @if($filterSearch || $filterType || $filterStatus || $filterVehicleId)
            <button wire:click="clearFilters" class="px-3 py-2 rounded-lg text-[11px] font-bold text-slate-500 hover:bg-slate-100 transition">Limpiar</button>
            @endif
        </div>
        @if($records->total() > 0)
        <p class="text-[10px] text-slate-400 font-bold">
            Mostrando {{ $records->firstItem() }}-{{ $records->lastItem() }} de {{ $records->total() }}
        </p>
        @endif
    </div>

    {{-- LIGHTBOX --}}
    <div x-show="lightbox" x-cloak x-transition.opacity.duration.200ms @keydown.escape.window="lightbox = false"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm" style="display: none;">
        <button type="button" @click="lightbox = false" class="absolute top-4 right-4 text-white/70 hover:text-white transition p-2">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <img :src="lightboxSrc" class="max-w-full max-h-[85vh] rounded-xl shadow-2xl object-contain">
    </div>

    {{-- FORM MODAL --}}
    <div x-data="{ show: @entangle('showForm') }"
         x-show="show"
         x-cloak
         x-transition.opacity.duration.200ms
         @keydown.escape.window="show = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="show = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200 w-[800px] max-h-[90vh] overflow-y-auto p-6"
             @click.outside="show = false">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-slate-800">{{ $editId ? 'Editar' : 'Nuevo' }} Mantenimiento</h3>
                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-600 transition p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Vehículo</label>
                    <select wire:model="vehicle_id" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        <option value="">Seleccionar...</option>
                        @foreach($vehicles as $v)
                            <option value="{{ $v->id }}">{{ $v->plate }} · {{ $v->brand }} {{ $v->model }}</option>
                        @endforeach
                    </select>
                    @error('vehicle_id') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Tipo</label>
                    <select wire:model="type" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        @foreach($maintenanceTypes as $mt)
                        <option value="{{ $mt->value }}">{{ $mt->label }}</option>
                        @endforeach
                    </select>
                    @error('type') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Fecha</label>
                    <input wire:model="date" type="date" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('date') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Categoría</label>
                    <input wire:model="category" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('category') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Descripción</label>
                    <textarea wire:model="description" rows="2" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none"></textarea>
                    @error('description') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Costo ($)</label>
                    <input wire:model="cost" type="number" step="0.01" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('cost') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Taller</label>
                    <input wire:model="workshop" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('workshop') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Odómetro (km)</label>
                    <input wire:model="odometer" type="number" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('odometer') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Fecha Programada</label>
                    <input wire:model="scheduled_date" type="date" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('scheduled_date') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Fecha Inicio</label>
                    <input wire:model="start_date" type="date" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('start_date') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Fecha Fin</label>
                    <input wire:model="end_date" type="date" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('end_date') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Status</label>
                    <select wire:model="status" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        @foreach($maintenanceStatuses as $ms)
                        <option value="{{ $ms->value }}">{{ $ms->label }}</option>
                        @endforeach
                    </select>
                    @error('status') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Evidencia (foto)</label>
                    <input wire:model="evidence" type="file" accept="image/*" class="w-full text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-pink-50 file:text-[#E72085] hover:file:bg-pink-100">
                    @error('evidence') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                    @if($evidence)
                        <img src="{{ $evidence->temporaryUrl() }}" class="h-24 rounded-lg object-cover mt-2 shadow-sm">
                    @elseif($existing_evidence)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($existing_evidence) }}" class="h-24 rounded-lg object-cover mt-2 shadow-sm">
                    @endif
                </div>
                <div class="md:col-span-2 flex gap-2 items-end">
                    <button type="submit" class="px-6 py-2 rounded-xl bg-[#E72085] text-white text-xs font-black uppercase tracking-wider hover:bg-[#d01c73] transition shadow-md shadow-[#E72085]/15">{{ $editId ? 'Actualizar' : 'Guardar' }}</button>
                    <button type="button" @click="show = false" class="px-6 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs font-black hover:bg-slate-50 transition">Cancelar</button>
                </div>
            </form>
        </div>
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
        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200 w-[600px] max-h-[90vh] overflow-y-auto p-6"
             @click.outside="show = false">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-slate-800">
                    Mantenimiento · {{ $detailRecord?->vehicle?->plate ?? '—' }}
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
                        <div><span class="text-gray-400">Vehículo</span><p class="font-bold text-gray-800">{{ $detailRecord->vehicle?->plate }} · {{ $detailRecord->vehicle?->brand }} {{ $detailRecord->vehicle?->model }}</p></div>
                        <div><span class="text-gray-400">Tipo</span><p class="font-bold text-gray-800">{{ $detailRecord->type?->value ?? $detailRecord->type }}</p></div>
                        <div><span class="text-gray-400">Categoría</span><p class="font-bold text-gray-800">{{ $detailRecord->category ?? '—' }}</p></div>
                        <div><span class="text-gray-400">Status</span>
                            <p class="font-bold {{ $statusStyles[$detailRecord->status][0] ?? 'bg-slate-100 text-slate-600' }} px-2 py-0.5 rounded inline-block mt-0.5">{{ $statusStyles[$detailRecord->status][1] ?? $detailRecord->status }}</p>
                        </div>
                        <div><span class="text-gray-400">Fecha</span><p class="font-bold text-gray-800">{{ $detailRecord->date?->format('d/m/Y') ?? '—' }}</p></div>
                        <div><span class="text-gray-400">Fecha Programada</span><p class="font-bold text-gray-800">{{ $detailRecord->scheduled_date?->format('d/m/Y') ?? '—' }}</p></div>
                        <div><span class="text-gray-400">Fecha Inicio</span><p class="font-bold text-gray-800">{{ $detailRecord->start_date?->format('d/m/Y') ?? '—' }}</p></div>
                        <div><span class="text-gray-400">Fecha Fin</span><p class="font-bold text-gray-800">{{ $detailRecord->end_date?->format('d/m/Y') ?? '—' }}</p></div>
                    </div>
                </div>

                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Descripción</h4>
                    <p class="text-xs text-gray-600 italic">&ldquo;{{ $detailRecord->description }}&rdquo;</p>
                </div>

                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Costos y Taller</h4>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div><span class="text-gray-400">Costo</span><p class="font-bold text-[#E72085]">${{ number_format($detailRecord->cost, 2) }}</p></div>
                        <div><span class="text-gray-400">Taller</span><p class="font-bold text-gray-800">{{ $detailRecord->workshop ?? '—' }}</p></div>
                        <div><span class="text-gray-400">Odómetro</span><p class="font-bold text-gray-800">{{ $detailRecord->odometer ? number_format($detailRecord->odometer).' km' : '—' }}</p></div>
                    </div>
                </div>

                @if($detailRecord->maintenanceWorkshop)
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Taller Registrado</h4>
                    <p class="text-xs font-bold text-gray-800">{{ $detailRecord->maintenanceWorkshop->name }}</p>
                </div>
                @endif

                @if($detailRecord->evidence)
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Evidencia</h4>
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($detailRecord->evidence) }}" alt="Evidencia"
                         class="w-full max-h-56 object-cover rounded-lg cursor-pointer"
                         @click="lightbox = true; lightboxSrc = $event.currentTarget.src">
                </div>
                @endif
            </div>
            @endif

            <div class="mt-6 pt-4 border-t border-gray-100">
                <button type="button" @click="show = false" class="w-full px-6 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs font-black hover:bg-slate-50 transition">Cerrar</button>
            </div>
        </div>
    </div>

    {{-- CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($records as $m)
        <div class="bg-slate-50/50 rounded-xl p-4 border border-slate-100 flex gap-4">
            @if($m->evidence)
            <div class="shrink-0">
                <img src="{{ \Illuminate\Support\Facades\Storage::url($m->evidence) }}" alt="Evidencia"
                     class="w-16 h-16 rounded-lg object-cover cursor-pointer shadow-xs"
                     @click="lightbox = true; lightboxSrc = $event.currentTarget.src">
            </div>
            @endif
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between mb-1.5">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="bg-pink-100 text-[#E72085] px-2 py-0.5 rounded text-[10px] font-bold uppercase">{{ $m->type?->value ?? $m->type }}</span>
                        <span class="{{ $statusStyles[$m->status][0] ?? 'bg-slate-100 text-slate-600' }} px-2 py-0.5 rounded text-[10px] font-bold">{{ $statusStyles[$m->status][1] ?? $m->status }}</span>
                        <span class="text-sm font-black text-slate-900">{{ $m->vehicle?->plate ?? '—' }}</span>
                    </div>
                    <span class="text-[10px] text-slate-400 font-bold shrink-0">{{ $m->date ? $m->date->format('d/m/Y') : '—' }}</span>
                </div>
                <p class="text-xs text-slate-600 italic mb-2">&ldquo;{{ $m->description }}&rdquo;</p>
                <hr class="border-slate-200 mb-2">
                <div class="flex items-center gap-3 text-[11px] text-slate-500 font-semibold flex-wrap">
                    <span>{{ $m->workshop }}</span>
                    <span class="text-[#E72085] font-black">${{ number_format($m->cost, 2) }}</span>
                    @if($m->odometer)
                        <span class="text-gray-400">{{ number_format($m->odometer) }} km</span>
                    @endif
                </div>
                <div class="flex items-center gap-3 text-[10px] text-slate-400 font-bold mt-1.5 flex-wrap">
                    @if($m->category)
                        <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded">{{ $m->category }}</span>
                    @endif
                    @if($m->scheduled_date)
                        <span>Programado: {{ $m->scheduled_date->format('d/m/Y') }}</span>
                    @endif
                    @if($m->start_date)
                        <span>Inicio: {{ $m->start_date->format('d/m/Y') }}</span>
                    @endif
                    @if($m->end_date)
                        <span>Fin: {{ $m->end_date->format('d/m/Y') }}</span>
                    @endif
                </div>
            </div>
            <div class="flex flex-col items-end justify-between shrink-0">
                <div class="flex gap-1">
                    <button wire:click="showExpediente({{ $m->id }})" class="p-1 text-emerald-600 hover:bg-emerald-50 rounded transition" title="Ver detalle">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                    <button wire:click="edit({{ $m->id }})" class="p-1 text-[#008FD3] hover:bg-blue-50 rounded transition" title="Editar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button wire:click="confirmDelete({{ $m->id }})" class="p-1 text-red-500 hover:bg-red-50 rounded transition" title="Eliminar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-400 text-sm font-bold">Sin registros de mantenimiento</div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($records->hasPages())
    <div class="pt-2">
        {{ $records->links() }}
    </div>
    @endif
</div>
