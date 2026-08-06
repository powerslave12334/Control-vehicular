
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-black text-slate-800">Historial de Incidencias en Ruta</h2>
            <p class="text-xs text-slate-400 font-semibold mt-0.5">Atención y resolución para la logística y seguridad.</p>
        </div>
        <button wire:click="create" class="flex items-center gap-1 text-xs font-bold text-[#E72085] bg-pink-50 hover:bg-pink-100 px-3 py-1.5 rounded-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            {{ $editId ? 'Editar Incidencia' : 'Reportar Incidencia' }}
        </button>
    </div>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        <div class="bg-white rounded-xl border border-slate-200 p-3">
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Totales</p>
            <p class="text-lg font-black text-slate-800 mt-0.5">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-amber-50 rounded-xl border border-amber-200 p-3">
            <p class="text-[10px] font-extrabold text-amber-600 uppercase tracking-wider">Reportadas</p>
            <p class="text-lg font-black text-amber-800 mt-0.5">{{ $stats['reportadas'] }}</p>
        </div>
        <div class="bg-blue-50 rounded-xl border border-blue-200 p-3">
            <p class="text-[10px] font-extrabold text-blue-600 uppercase tracking-wider">Atendidas</p>
            <p class="text-lg font-black text-blue-800 mt-0.5">{{ $stats['atendidas'] }}</p>
        </div>
        <div class="bg-emerald-50 rounded-xl border border-emerald-200 p-3">
            <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider">Resueltas</p>
            <p class="text-lg font-black text-emerald-800 mt-0.5">{{ $stats['resueltas'] }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl border border-gray-200 p-3">
            <p class="text-[10px] font-extrabold text-gray-500 uppercase tracking-wider">Cerradas</p>
            <p class="text-lg font-black text-gray-700 mt-0.5">{{ $stats['cerradas'] }}</p>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="bg-white rounded-xl border border-slate-200 p-3">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
            <select wire:model.live="filterStatus" class="px-2 py-1.5 rounded-lg border border-slate-200 text-[10px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085]">
                <option value="">Todos los estados</option>
                @foreach($incidentStatuses as $is)
                <option value="{{ $is->value }}">{{ $is->label }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterSeverity" class="px-2 py-1.5 rounded-lg border border-slate-200 text-[10px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085]">
                <option value="">Todas las severidades</option>
                @foreach($incidentSeverities as $is)
                <option value="{{ $is->value }}">{{ $is->label }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterVehicleId" class="px-2 py-1.5 rounded-lg border border-slate-200 text-[10px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085]">
                <option value="">Todos los vehículos</option>
                @foreach($vehicles as $v)
                    <option value="{{ $v->id }}">{{ $v->plate }}</option>
                @endforeach
            </select>
            <input wire:model.live="filterDateFrom" type="date" class="px-2 py-1.5 rounded-lg border border-slate-200 text-[10px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085]">
            <input wire:model.live="filterDateTo" type="date" class="px-2 py-1.5 rounded-lg border border-slate-200 text-[10px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085]">
        </div>
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
        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200 w-[550px] max-h-[90vh] overflow-y-auto p-6"
             @click.outside="show = false">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-slate-800">{{ $editId ? 'Editar' : 'Reportar' }} Incidencia</h3>
                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-600 transition p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Vehículo</label>
                    <select wire:model="vehicle_id" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        <option value="">Seleccionar...</option>
                        @foreach($vehicles as $v)
                            <option value="{{ $v->id }}">{{ $v->plate }} · {{ $v->brand }} {{ $v->model }}</option>
                        @endforeach
                    </select>
                    @error('vehicle_id') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Conductor</label>
                    <select wire:model="driver_id" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        <option value="">Seleccionar...</option>
                        @foreach($operators as $o)
                            <option value="{{ $o->id }}">{{ $o->name }}</option>
                        @endforeach
                    </select>
                    @error('driver_id') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fecha</label>
                    <input wire:model="date" type="date" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('date') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Hora</label>
                    <input wire:model="time" type="time" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('time') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo</label>
                    <select wire:model="type" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        <option value="">Seleccionar...</option>
                        @foreach($incidentTypes as $it)
                        <option value="{{ $it->value }}">{{ $it->label }}</option>
                        @endforeach
                    </select>
                    @error('type') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Ubicación</label>
                    <input wire:model="location" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('location') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Descripción</label>
                    <textarea wire:model="description" rows="3" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none"></textarea>
                    @error('description') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Severidad</label>
                    <select wire:model="severity" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        @foreach($incidentSeverities as $is)
                        <option value="{{ $is->value }}">{{ $is->label }}</option>
                        @endforeach
                    </select>
                    @error('severity') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Costo Estimado ($)</label>
                    <input wire:model="cost" type="number" step="0.01" min="0" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('cost') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer">
                        <input wire:model="involves_third_party" type="checkbox" class="rounded border-gray-300 text-[#E72085] focus:ring-[#E72085]/20">
                        Involucra a terceros
                    </label>
                </div>
                @if($involves_third_party)
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Datos del Tercero</label>
                    <textarea wire:model="third_party_info" rows="2" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none"></textarea>
                    @error('third_party_info') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                @endif
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Foto / Evidencia</label>
                    <input wire:model="photo" type="file" accept="image/*" class="w-full text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-pink-50 file:text-[#E72085] hover:file:bg-pink-100">
                    @error('photo') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                    @if($photo)
                    <div class="mt-2">
                        <img src="{{ $photo->temporaryUrl() }}" class="h-24 rounded-lg object-cover shadow-xs">
                    </div>
                    @endif
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="px-6 py-2 rounded-xl bg-[#E72085] text-white text-xs font-black uppercase tracking-wider hover:bg-[#d01c73] transition shadow-md shadow-[#E72085]/15">{{ $editId ? 'Actualizar' : 'Reportar' }}</button>
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
                <h3 class="text-sm font-black text-slate-800">Incidencia #{{ $detailRecord?->id }}</h3>
                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-600 transition p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if($detailRecord)
            <div class="space-y-5">
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Información General</h4>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div><span class="text-gray-400">Fecha / Hora</span><p class="font-bold text-gray-800">{{ $detailRecord->date?->format('d/m/Y') ?? '—' }} {{ $detailRecord->time }}</p></div>
                        <div><span class="text-gray-400">Tipo</span><p class="font-bold text-gray-800">{{ $detailRecord->type ?? '—' }}</p></div>
                        <div><span class="text-gray-400">Vehículo</span><p class="font-bold text-gray-800">{{ $detailRecord->vehicle?->plate ?? '—' }}</p></div>
                        <div><span class="text-gray-400">Conductor</span><p class="font-bold text-gray-800">{{ $detailRecord->driver_name }}</p></div>
                        <div><span class="text-gray-400">Ubicación</span><p class="font-bold text-gray-800">{{ $detailRecord->location ?? '—' }}</p></div>
                        <div>
                            <span class="text-gray-400">Severidad</span>
                            <p class="font-bold
                                @switch($detailRecord->severity?->value ?? $detailRecord->severity)
                                    @case('Alta') text-red-600 @break
                                    @case('Media') text-amber-600 @break
                                    @case('Baja') text-blue-600 @break
                                    @case('Crítica') text-purple-600 @break
                                    @default text-gray-600
                                @endswitch">
                                {{ $detailRecord->severity?->value ?? $detailRecord->severity }}
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-400">Status</span>
                            <p class="font-bold
                                @switch($detailRecord->status?->value ?? $detailRecord->status)
                                    @case('Resuelta') text-emerald-600 @break
                                    @case('Cerrada') text-gray-600 @break
                                    @case('Atendida') text-amber-600 @break
                                    @default text-yellow-600
                                @endswitch">
                                {{ $detailRecord->status?->value ?? $detailRecord->status }}
                            </p>
                        </div>
                        <div><span class="text-gray-400">Costo</span><p class="font-bold text-gray-800">{{ $detailRecord->cost ? '$'.number_format($detailRecord->cost, 2) : '—' }}</p></div>
                        <div><span class="text-gray-400">Terceros</span><p class="font-bold text-gray-800">{{ $detailRecord->involves_third_party ? 'Sí' : 'No' }}</p></div>
                    </div>
                </div>

                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Descripción</h4>
                    <p class="text-xs text-gray-600 leading-relaxed bg-slate-50 p-3 rounded-lg">{{ $detailRecord->description }}</p>
                </div>

                @if($detailRecord->photo)
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Evidencia Fotográfica</h4>
                    <img src="{{ Storage::url($detailRecord->photo) }}" class="w-full max-h-48 object-cover rounded-lg">
                </div>
                @endif

                @if($detailRecord->resolved_at)
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Resolución</h4>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div><span class="text-gray-400">Resuelta el</span><p class="font-bold text-gray-800">{{ $detailRecord->resolved_at?->format('d/m/Y H:i') ?? '—' }}</p></div>
                        <div><span class="text-gray-400">Resuelta por</span><p class="font-bold text-gray-800">{{ $detailRecord->resolver?->name ?? '—' }}</p></div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <div class="mt-6 pt-4 border-t border-gray-100">
                <button type="button" @click="show = false" class="w-full px-6 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs font-black hover:bg-slate-50 transition">Cerrar</button>
            </div>
        </div>
    </div>

    {{-- INCIDENTS LIST --}}
    <div class="space-y-3">
        @forelse($incidents as $inc)
        <div class="bg-white rounded-xl p-4 border border-gray-100 flex flex-col md:flex-row justify-between gap-4 hover:shadow-sm transition">
            <div class="flex items-start gap-3 flex-1 min-w-0">
                <div class="w-16 h-16 bg-red-50 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
                <div class="space-y-1.5 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[11px] font-bold text-slate-400">#{{ $inc->id }}</span>
                        @if($inc->type)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-600">{{ $inc->type }}</span>
                        @endif
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                            @switch($inc->severity?->value ?? $inc->severity)
                                @case('Alta') bg-red-100 text-red-800 @break
                                @case('Media') bg-amber-100 text-amber-800 @break
                                @case('Baja') bg-blue-100 text-blue-800 @break
                                @case('Crítica') bg-purple-100 text-purple-800 @break
                            @endswitch">
                            {{ $inc->severity?->value ?? $inc->severity }}
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                            @switch($inc->status?->value ?? $inc->status)
                                @case('Resuelta') bg-green-100 text-green-800 @break
                                @case('Cerrada') bg-gray-100 text-gray-600 @break
                                @case('Atendida') bg-amber-100 text-amber-800 @break
                                @default bg-yellow-100 text-yellow-800
                            @endswitch">
                            {{ $inc->status?->value ?? $inc->status }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-700 font-semibold">{{ $inc->description }}</p>
                    <div class="flex items-center gap-3 text-[10px] text-slate-400 font-bold flex-wrap">
                        <span>{{ $inc->driver_name }}</span>
                        <span>·</span>
                        <span>{{ $inc->vehicle?->plate ?? '—' }}</span>
                        <span>·</span>
                        <span>{{ $inc->date?->format('d/m/Y') ?? '—' }} {{ $inc->time }}</span>
                        @if($inc->cost)
                        <span>·</span>
                        <span class="text-amber-600">${{ number_format($inc->cost, 2) }}</span>
                        @endif
                        @if($inc->location)
                        <span>·</span>
                        <span>{{ $inc->location }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 pt-1">
                        <button wire:click="showExpediente({{ $inc->id }})" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-800">Detalle</button>
                        <span class="text-slate-300">|</span>
                        <button wire:click="edit({{ $inc->id }})" class="text-[10px] font-bold text-[#008FD3] hover:text-blue-700">Editar</button>
                        <span class="text-slate-300">|</span>
                        <button wire:click="confirmDelete({{ $inc->id }})" class="text-[10px] font-bold text-red-500 hover:text-red-700">Eliminar</button>
                    </div>
                </div>
            </div>
            <div class="flex md:flex-col items-center md:items-end gap-2 shrink-0">
                <span class="text-[10px] font-bold text-gray-400">Cambiar Estado:</span>
                <div class="flex gap-1.5 flex-wrap justify-end">
                    @if(($inc->status?->value ?? $inc->status) === 'Reportada')
                    <button wire:click="changeStatus({{ $inc->id }}, 'Atendida')" class="px-3 py-1 rounded-lg bg-amber-500 text-white text-[10px] font-black hover:bg-amber-600 transition">Atendida</button>
                    @endif
                    @if(in_array(($inc->status?->value ?? $inc->status), ['Reportada', 'Atendida']))
                    <button wire:click="changeStatus({{ $inc->id }}, 'Resuelta')" class="px-3 py-1 rounded-lg bg-green-600 text-white text-[10px] font-black hover:bg-green-700 transition">Resuelta</button>
                    @endif
                    @if(($inc->status?->value ?? $inc->status) === 'Resuelta')
                    <button wire:click="changeStatus({{ $inc->id }}, 'Cerrada')" class="px-3 py-1 rounded-lg bg-gray-600 text-white text-[10px] font-black hover:bg-gray-700 transition">Cerrar</button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-gray-400 text-sm font-bold">Sin incidencias registradas</div>
        @endforelse
    </div>

    @if($incidents->hasPages())
    <div class="pt-2">
        {{ $incidents->links() }}
    </div>
    @endif
</div>
