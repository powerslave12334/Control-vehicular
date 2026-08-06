<div class="bg-white rounded-3xl shadow-xs border border-slate-200 p-6 md:p-8 space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-base font-bold text-gray-800">Programa de Instalaciones y Rutas de la Flota</h3>
        <button wire:click="create"
            class="flex items-center gap-1 text-xs font-bold text-[#E72085] bg-pink-50 hover:bg-pink-100 px-3 py-1.5 rounded-lg transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Programar Nueva Ruta
        </button>
    </div>

    {{-- FORM MODAL --}}
    <div x-data="{ show: @entangle('showForm') }" x-show="show" x-cloak
        x-effect="if (show) { $dispatch('form:opened'); }" x-transition.opacity.duration.200ms
        @keydown.escape.window="show = false" class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display: none;">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="show = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200 max-h-[90vh] overflow-y-auto p-6"
            @click.outside="show = false">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-slate-800">{{ $editId ? 'Editar' : 'Nueva' }} Ruta</h3>
                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-600 transition p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form wire:submit="save" class="grid grid-cols-2 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Conductor</label>
                            <select wire:model="driver_id"
                                class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                                <option value="">Seleccionar...</option>
                                @foreach($drivers as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                            @error('driver_id') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Ayudante</label>
                            <select wire:model="assistant_id"
                                class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                                <option value="">Ninguno</option>
                                @foreach($assistants as $a)
                                <option value="{{ $a->id }}">{{ $a->name }}</option>
                                @endforeach
                            </select>
                            @error('assistant_id') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Vehículo</label>
                            <select wire:model="vehicle_id"
                                class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                                <option value="">Seleccionar...</option>
                                @foreach($vehicles as $v)
                                <option value="{{ $v->id }}">{{ $v->brand }} {{ $v->model }} · {{ $v->plate }}</option>
                                @endforeach
                            </select>
                            @error('vehicle_id') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fecha</label>
                            <input wire:model="date" type="date"
                                class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                            @error('date') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Código</label>
                            <input wire:model="code"
                                class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                            @error('code') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cliente</label>
                            <input wire:model="client_name"
                                class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                            @error('client_name') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Km Planeados</label>
                            <input wire:model="planned_km" type="number" step="0.1"
                                class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                            @error('planned_km') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Distancia
                                (km)</label>
                            <input wire:model="distance_km" type="number" step="0.1"
                                class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                            @error('distance_km') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Duración Est.
                                (horas)</label>
                            <input wire:model="estimated_duration" type="number" step="any" min="0"
                                class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                            @error('estimated_duration') <span class="text-[10px] text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Status</label>
                            <select wire:model="status"
                                class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                                @foreach($routeStatuses as $rs)
                                <option value="{{ $rs->value }}">{{ $rs->label }}</option>
                                @endforeach
                            </select>
                            @error('status') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Origen</label>
                            <input id="originInput" wire:model="origin"
                                class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none"
                                placeholder="Buscar dirección...">
                            @error('origin') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase">Destinos
                                    @if(count($destinations) > 0)
                                    <span
                                        class="ml-1 px-1.5 py-0.5 rounded-full bg-[#E72085]/10 text-[#E72085] text-[10px] font-black align-middle">{{ count($destinations) }}</span>
                                    @endif
                                </label>
                                <button type="button" wire:click="addDestination"
                                    class="text-[10px] font-black text-[#008FD3] hover:text-[#0070a8] transition flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 4v16m8-8H4" /></svg>
                                    Agregar destino
                                </button>
                            </div>
                            @forelse($destinations as $i => $dest)
                            <div class="flex gap-2 mb-1.5" wire:key="dest-{{ $i }}">
                                <input id="destinationInput-{{ $i }}" wire:model="destinations.{{ $i }}"
                                    data-index="{{ $i }}"
                                    class="js-destination-input flex-1 bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none"
                                    placeholder="Destino {{ $i + 1 }}...">
                                <button type="button" wire:click="removeDestination({{ $i }})"
                                    class="shrink-0 px-2.5 rounded-lg border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            @empty
                            <div class="flex gap-2 mb-1.5">
                                <input id="destinationInput-0" wire:model="destinations.0" data-index="0"
                                    class="js-destination-input flex-1 bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none"
                                    placeholder="Destino 1...">
                            </div>
                            @endforelse
                            @error('destinations') <span class="text-[10px] text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Descripción</label>
                            <textarea wire:model="description" rows="2"
                                class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none"></textarea>
                            @error('description') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2 flex gap-2 mt-2 pt-2 border-t border-gray-100">
                            <button type="submit"
                                class="px-6 py-2 rounded-xl bg-[#E72085] text-white text-xs font-black uppercase tracking-wider hover:bg-[#d01c73] transition shadow-md shadow-[#E72085]/15">{{ $editId ? 'Actualizar' : 'Guardar' }}</button>
                            <button type="button" @click="show = false"
                                class="px-6 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs font-black hover:bg-slate-50 transition">Cancelar</button>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-6">
                    <div wire:ignore class="lg:sticky lg:top-4">
                        <div class="relative">
                            <div wire:ignore id="formMap" style="height: 340px; border-radius: 12px;"></div>
                            <div id="formMapPlaceholder"
                                class="absolute inset-0 flex items-center justify-center bg-slate-50/80 rounded-xl pointer-events-none text-center"
                                style="z-index: 5;">
                                <div>
                                    <svg class="w-8 h-8 mx-auto text-slate-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                    <p class="text-slate-400 text-xs font-bold mt-2">Ingresa origen y destino</p>
                                    <p class="text-slate-300 text-[10px]">para ver la ruta en el mapa</p>
                                </div>
                            </div>
                        </div>
                        <div id="formMapInfo"
                            class="hidden mt-2 rounded-xl border border-slate-200 bg-white p-3 text-[10px]"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- DETAIL MODAL --}}
    <div x-data="{ show: @entangle('showDetail') }" x-show="show" x-cloak x-transition.opacity.duration.200ms
        @keydown.escape.window="show = false" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="show = false"></div>
        <div
            class="relative bg-white rounded-2xl shadow-xl border border-slate-200 w-5/6 max-w-[95vw] max-h-[95vh] overflow-y-auto">
            @php
            $detailStatus = $detailRecord?->status?->value ?? $detailRecord?->status;
            $statusPill = match($detailStatus) {
            'Completada' => 'bg-emerald-100 text-emerald-700',
            'Cancelada' => 'bg-red-100 text-red-600',
            'En tránsito' => 'bg-amber-100 text-amber-700',
            'Instalando' => 'bg-indigo-100 text-indigo-700',
            default => 'bg-slate-100 text-slate-600',
            };
            $kmReal = $detailRecord?->actual_km ?? 0;
            $kmPlan = $detailRecord?->planned_km ?? 0;
            $kmDiff = $kmPlan > 0 ? round($kmReal - $kmPlan, 1) : 0;
            $kmDiffPct = $kmPlan > 0 ? round(($kmDiff / $kmPlan) * 100) : 0;
            $stepLabelMap = [
            'departurePlant' => 'Salida Planta',
            'arrivalClient' => 'Llegada Cliente',
            'startInstallation' => 'Inicio Instalación',
            'endInstallation' => 'Fin Instalación',
            'returnToPlant' => 'Regreso a Planta',
            'arrivalPlant' => 'Llegada Planta',
            ];
            @endphp
            @if($detailRecord)
            <div class="bg-white/10 px-6 py-5 mt-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-9 h-9 rounded-lg bg-[#E72085] shadow-lg shadow-[#E72085]/30 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-black text-black">Ruta #{{ $detailRecord->id }}</h3>
                                <span
                                    class="px-2 py-0.5 rounded-full font-bold text-[10px] {{ $statusPill }}">{{ $detailStatus }}</span>
                            </div>
                            <p class="text-xs font-bold text-black mt-0.5">{{ $detailRecord->client_name ?? '—' }}</p>
                            <p class="text-[10px] text-black/70 mt-0.5">
                                {{ $detailRecord->date?->format('d/m/Y') ?? '' }}{{ $detailRecord->week ? ' · ' . $detailRecord->week : '' }}
                            </p>
                        </div>
                    </div>
                    <button type="button" @click="show = false" class="text-black/50 hover:text-black transition p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
            @endif

            <div class="p-6">
                @if($detailRecord)
                <div class="gap-6">
                    <div class="space-y-6 min-w-0">
                        <div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="w-1 h-3.5 rounded-full bg-[#E72085]"></span>
                                <h4 class="text-[10px] font-black text-black uppercase tracking-wider">Información
                                    General</h4>
                            </div>
                            <div class="grid grid-cols-2 gap-x-3 gap-y-3 text-xs">
                                <div><span class="text-black">Código</span>
                                    <p class="font-bold text-black">{{ $detailRecord->code ?? '—' }}</p>
                                </div>
                                <div><span class="text-black">Fecha</span>
                                    <p class="font-bold text-black">{{ $detailRecord->date?->format('d/m/Y') ?? '—' }}
                                    </p>
                                </div>
                                <div><span class="text-black">Conductor</span>
                                    <p class="font-bold text-black">{{ $detailRecord->driver_name }}</p>
                                </div>
                                <div><span class="text-black">Ayudante</span>
                                    <p class="font-bold text-black">{{ $detailRecord->assistant_name ?? '—' }}</p>
                                </div>
                                <div><span class="text-black">Vehículo</span>
                                    <p class="font-bold text-black">{{ $detailRecord->vehicle_plate }}</p>
                                </div>
                                <div><span class="text-black">Semana</span>
                                    <p class="font-bold text-black">{{ $detailRecord->week ?? '—' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 mb-2">
                            <span class="w-1 h-3.5 rounded-full bg-[#E72085]"></span>
                            <h4 class="text-[10px] font-black text-black uppercase tracking-wider">Ubicación</h4>
                        </div>
                        <div class="space-y-3 text-xs">
                            <div>
                                <span class="text-black">Origen</span>
                                <p class="font-bold text-black">{{ $detailRecord->origin ?? '—' }}</p>
                            </div>
                            <div>
                                <span class="text-black">Destino(s)
                                    @if(count($detailRecord->destinations ?? []) > 1)
                                    <span
                                        class="ml-1 px-1.5 py-0.5 rounded-full bg-[#E72085]/10 text-[#E72085] text-[10px] font-black">{{ count($detailRecord->destinations) }}</span>
                                    @endif
                                </span>
                                @if(!empty($detailRecord->destinations))
                                <div class="mt-1.5 space-y-1">
                                    @foreach($detailRecord->destinations as $di => $dest)
                                    <div class="flex items-start gap-1.5">
                                        <span
                                            class="w-2 h-2 mt-1 rounded-full shrink-0 {{ $loop->last ? 'bg-[#008FD3]' : 'bg-emerald-500' }}"></span>
                                        <p class="font-bold text-black text-xs">{{ $dest }}</p>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <p class="font-bold text-black">{{ $detailRecord->destination ?? '—' }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($detailRecord->description)
                    <div>
                        <div class="flex items-center gap-1.5 mb-2">
                            <span class="w-1 h-3.5 rounded-full bg-[#E72085]"></span>
                            <h4 class="text-[10px] font-black text-black uppercase tracking-wider">Descripción</h4>
                        </div>
                        <p class="text-xs text-black leading-relaxed">{{ $detailRecord->description }}</p>
                    </div>
                    @endif

                    @if(($detailRecord->status?->value ?? $detailRecord->status) === 'Cancelada' && $detailRecord->cancellation_reason)
                    <div>
                        <div class="flex items-center gap-1.5 mb-2">
                            <span class="w-1 h-3.5 rounded-full bg-red-500"></span>
                            <h4 class="text-[10px] font-black text-black uppercase tracking-wider">Motivo de Cancelación</h4>
                        </div>
                        <p class="text-xs text-red-700 leading-relaxed">"{{ $detailRecord->cancellation_reason }}"</p>
                    </div>
                    @endif

                    @if($detailRecord->steps->count() > 0)
                    @php
                    $half = (int) ceil($detailRecord->steps->count() / 2);
                    $leftSteps = $detailRecord->steps->take($half);
                    $rightSteps = $detailRecord->steps->skip($half);
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                        <ul class="space-y-1">
                            @foreach($leftSteps as $i => $step)
                            @php $stepLabel = $stepLabelMap[$step->step_type] ?? ($step->description ??
                            $step->step_type);
                            @endphp
                            <li
                                class="flex items-center gap-3 py-2.5 hover:bg-slate-50 transition rounded-lg px-2 -mx-2">
                                <span
                                    class="w-6 h-6 rounded-full {{ $step->latitude && $step->longitude ? 'bg-[#10B981]/10 text-[#10B981]' : 'bg-slate-100 text-black' }} flex items-center justify-center text-[10px] font-black shrink-0">
                                    @if($step->latitude && $step->longitude)
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" /></svg>
                                    @else
                                    {{ $detailRecord->steps->search($step) + 1 }}
                                    @endif
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="font-bold text-black">{{ $stepLabel }}</p>
                                </div>
                                @if($step->timestamp)
                                <span class="text-[10px] font-bold text-black/60 shrink-0 tabular-nums">
                                    {{ $step->timestamp->format('d/m/Y H:i') }}
                                </span>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        @if($rightSteps->isNotEmpty())
                        <ul class="space-y-1">
                            @foreach($rightSteps as $i => $step)
                            @php $stepLabel = $stepLabelMap[$step->step_type] ?? ($step->description ??
                            $step->step_type);
                            @endphp
                            <li
                                class="flex items-center gap-3 py-2.5 hover:bg-slate-50 transition rounded-lg px-2 -mx-2">
                                <span
                                    class="w-6 h-6 rounded-full {{ $step->latitude && $step->longitude ? 'bg-[#10B981]/10 text-[#10B981]' : 'bg-slate-100 text-black' }} flex items-center justify-center text-[10px] font-black shrink-0">
                                    @if($step->latitude && $step->longitude)
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" /></svg>
                                    @else
                                    {{ $detailRecord->steps->search($step) + 1 }}
                                    @endif
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="font-bold text-black">{{ $stepLabel }}</p>
                                </div>
                                @if($step->timestamp)
                                <span class="text-[10px] font-bold text-black/60 shrink-0 tabular-nums">
                                    {{ $step->timestamp->format('d/m/Y H:i') }}
                                </span>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    @endif

                    <div class="lg:sticky lg:top-6 space-y-3 self-start min-w-0">
                        <div>
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="w-1 h-3.5 rounded-full bg-[#E72085]"></span>
                                <h4 class="text-[10px] font-black text-black uppercase tracking-wider">Simulación de
                                    Ruta</h4>
                            </div>
                            <div class="rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                                <div wire:ignore id="detailMap" style="height: 400px; z-index: 1;"></div>
                                <div id="detailRouteInfo"
                                    class="hidden p-3 text-[10px] border-t border-slate-100 bg-slate-50/50"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <button type="button" @click="show = false"
                            class="w-full px-6 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-black text-xs font-black hover:bg-slate-100 hover:text-black/70 transition">Cerrar</button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>



    {{-- FILTERS --}}
    <div class="flex flex-col lg:flex-row gap-3 items-start lg:items-center justify-between">
        <div class="flex flex-wrap gap-2 items-center">
            <input wire:model.live.debounce.300ms="filterSearch" type="text"
                placeholder="Buscar (cliente, operador, placa, código...)"
                class="px-3 py-2 rounded-lg border border-slate-200 text-xs font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] w-56">
            <select wire:model.live="filterVehicleId"
                class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                <option value="">Vehículo: Todos</option>
                @foreach($vehicles as $v)
                <option value="{{ $v->id }}">{{ $v->plate }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterStatus"
                class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                <option value="">Estatus: Todos</option>
                @foreach($routeStatuses as $rs)
                <option value="{{ $rs->value }}">{{ $rs->label }}</option>
                @endforeach
            </select>
            <input wire:model.live="filterDateFrom" type="date" title="Desde"
                class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
            <span class="text-[10px] font-bold text-gray-400">→</span>
            <input wire:model.live="filterDateTo" type="date" title="Hasta"
                class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
            @if($filterSearch || $filterVehicleId || $filterStatus || $filterDateFrom || $filterDateTo)
            <button wire:click="clearFilters"
                class="px-3 py-2 rounded-lg text-[11px] font-bold text-slate-500 hover:bg-slate-100 transition">Limpiar</button>
            @endif
        </div>
        @if($routes->total() > 0)
        <p class="text-[10px] text-slate-400 font-bold">
            Mostrando {{ $routes->firstItem() }}-{{ $routes->lastItem() }} de {{ $routes->total() }}
        </p>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-gray-600">
            <thead class="bg-slate-50 text-gray-400 font-bold uppercase tracking-wider">
                <tr>
                    <th class="py-2.5 px-3 rounded-l-lg">ID</th>
                    <th class="py-2.5 px-3">Fecha</th>
                    <th class="py-2.5 px-3">Cliente / Proyecto</th>
                    <th class="py-2.5 px-3">Vehículo</th>
                    <th class="py-2.5 px-3">Operador</th>
                    <th class="py-2.5 px-3">Km Planeados</th>
                    <th class="py-2.5 px-3">Gasto Autorizado</th>
                    <th class="py-2.5 px-3">Estatus</th>
                    <th class="py-2.5 px-3 rounded-r-lg">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($routes as $r)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="py-3 px-3 font-mono font-bold text-gray-900">{{ $r->id }}</td>
                    <td class="py-3 px-3">{{ $r->date?->format('d/m/Y') ?? '—' }}</td>
                    <td class="py-3 px-3 font-bold text-gray-800">{{ $r->client_name }}
                        @if(!empty($r->destinations) && count($r->destinations) > 1)
                        <span
                            class="ml-1 px-1.5 py-0.5 rounded-full bg-[#E72085]/10 text-[#E72085] text-[10px] font-black align-middle">{{ count($r->destinations) }}
                            destinos</span>
                        @endif
                    </td>
                    <td class="py-3 px-3 font-mono text-gray-800 font-bold">{{ $r->vehicle_plate }}</td>
                    <td class="py-3 px-3 text-gray-600">{{ $r->driver_name }}</td>
                    <td class="py-3 px-3 text-center">{{ number_format($r->planned_km) }} Km</td>
                    @php
                    $expSpent = $r->expenses_sum_amount ?? 0;
                    $expAuth = $r->vehicle?->authorized_expense ?? 0;
                    $expRemaining = max(0, $expAuth - $expSpent);
                    $expPercent = $expAuth > 0 ? round(($expSpent / $expAuth) * 100) : 0;
                    @endphp
                    <td class="py-3 px-3 min-w-[160px]">
                        @if($expAuth > 0)
                        <div class="flex items-center gap-2 text-[10px]">
                            <div class="flex-1">
                                <div class="flex justify-between mb-0.5">
                                    <span class="text-gray-500">${{ number_format($expSpent, 0) }}</span>
                                    <span class="font-bold text-slate-700">${{ number_format($expAuth, 0) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-full rounded-full {{ $expPercent >= 90 ? 'bg-red-500' : ($expPercent >= 70 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                        style="width: {{ min(100, $expPercent) }}%"></div>
                                </div>
                                <div class="flex justify-between mt-0.5">
                                    <span class="text-gray-400">{{ $expPercent }}%</span>
                                    <span
                                        class="font-bold {{ $expRemaining > 0 ? 'text-emerald-600' : 'text-red-500' }}">${{ number_format($expRemaining, 0) }}
                                        disp.</span>
                                </div>
                            </div>
                            <button wire:click="addAuthorizedExpense({{ $r->id }})"
                                class="shrink-0 p-1 text-blue-500 hover:bg-blue-50 rounded transition"
                                title="Agregar gasto autorizado">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" /></svg>
                            </button>
                        </div>
                        @else
                        <div class="flex items-center gap-2">
                            <span class="text-gray-400 text-[10px]">Sin autorizar</span>
                            <button wire:click="addAuthorizedExpense({{ $r->id }})"
                                class="p-1 text-blue-500 hover:bg-blue-50 rounded transition"
                                title="Configurar gasto autorizado">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" /></svg>
                            </button>
                        </div>
                        @endif
                    </td>
                    <td class="py-3 px-3">
                        <span class="px-2 py-0.5 rounded-full font-bold text-[10px]
                            @switch($r->status?->value ?? $r->status)
                                @case(\App\Domains\Route\Enums\RouteStatusEnum::Completada->value) bg-green-100 text-green-800 @break
                                @case(\App\Domains\Route\Enums\RouteStatusEnum::Instalando->value) bg-indigo-100 text-indigo-800 @break
                                @case(\App\Domains\Route\Enums\RouteStatusEnum::EnTransito->value) bg-amber-100 text-amber-800 @break
                                @case('Cancelada') bg-red-100 text-red-700 @break
                                @case('Programada') bg-gray-100 text-gray-600 @break
                                @default bg-gray-100 text-gray-600
                            @endswitch">
                            {{ $r->status?->value ?? $r->status }}
                        </span>
                    </td>
                    <td class="py-3 px-3">
                        <div class="flex gap-1">
                            <button wire:click="showExpediente({{ $r->id }})"
                                class="p-1 text-emerald-600 hover:bg-emerald-50 rounded" title="Detalle">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            @if(in_array(($r->status?->value ?? $r->status), ['En tránsito', 'Instalando']))
                            <button wire:click="confirmFinish({{ $r->id }})"
                                class="p-1 text-sky-600 hover:bg-sky-50 rounded" title="Completar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                            @endif
                            <button wire:click="edit({{ $r->id }})" class="p-1 text-[#008FD3] hover:bg-blue-50 rounded"
                                title="Editar">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button wire:click="confirmDelete({{ $r->id }})"
                                class="p-1 text-red-500 hover:bg-red-50 rounded" title="Eliminar">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-12 text-gray-400 text-sm font-bold">No hay rutas
                        registradas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $routes->links() }}
    </div>
</div>

@push('scripts')
<script>
    window.MapPins = (function () {
        const drop =
            '<path d="M16 6.5 C16 6.5 22.5 13.5 22.5 18 A6.5 6.5 0 1 1 9.5 18 C9.5 13.5 16 6.5 16 6.5 Z" fill="#ffffff"/>';

        function text(n) {
            return `<text x="16" y="20.5" text-anchor="middle" font-family="Segoe UI, Arial, sans-serif" font-weight="800" font-size="13" fill="#ffffff">${n}</text>`;
        }

        function pin(content, color, size) {
            const s = size || 32;
            const h = Math.round(s * 40 / 32);
            const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${s}" height="${h}" viewBox="0 0 32 40">
                <defs><filter id="sh" x="-30%" y="-30%" width="160%" height="160%">
                    <feDropShadow dx="0" dy="1.2" stdDeviation="1.4" flood-color="rgba(0,0,0,0.35)"/>
                </filter></defs>
                <path d="M16 40 C16 40 31.5 25 31.5 16 C31.5 7.44 24.56 0.5 16 0.5 C7.44 0.5 0.5 7.44 0.5 16 C0.5 25 16 40 16 40 Z" fill="${color}" stroke="#ffffff" stroke-width="2.2" filter="url(#sh)"/>
                <ellipse cx="10.5" cy="10" rx="5" ry="3.4" fill="#ffffff" opacity="0.22" transform="rotate(-20 10.5 10)"/>
                ${content}
            </svg>`;
            return {
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg),
                scaledSize: new google.maps.Size(s, h),
                anchor: new google.maps.Point(s / 2, h - 1),
            };
        }

        function flag(size) {
            const s = size || 32;
            const h = Math.round(s * 40 / 32);
            const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${s}" height="${h}" viewBox="0 0 32 40">
                <defs><filter id="sh" x="-30%" y="-30%" width="160%" height="160%">
                    <feDropShadow dx="0" dy="1.2" stdDeviation="1.4" flood-color="rgba(0,0,0,0.35)"/>
                </filter></defs>
                <g filter="url(#sh)">
                    <rect x="15" y="4" width="2.6" height="34" rx="1.3" fill="#334155"/>
                    <circle cx="16.3" cy="3.2" r="1.9" fill="#008FD3"/>
                    <rect x="9" y="3" width="18" height="12" rx="1" fill="#ffffff" stroke="#008FD3" stroke-width="1"/>
                    <rect x="9" y="3" width="6" height="6" fill="#008FD3"/>
                    <rect x="21" y="3" width="6" height="6" fill="#008FD3"/>
                    <rect x="15" y="9" width="6" height="6" fill="#008FD3"/>
                </g>
            </svg>`;
            return {
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg),
                scaledSize: new google.maps.Size(s, h),
                anchor: new google.maps.Point(s / 2, h - 1),
            };
        }

        return {
            drop,
            text,
            pin,
            flag
        };
    })();

    document.addEventListener('livewire:init', () => {
        let map = null;
        let markers = [];
        let polylines = [];
        let directionsRenderer = null;

        const stepColors = {
            departurePlant: '#E72085',
            arrivalClient: '#008FD3',
            startInstallation: '#F59E0B',
            endInstallation: '#10B981',
            returnToPlant: '#8B5CF6',
            arrivalPlant: '#059669',
        };

        const stepLabels = {
            departurePlant: 'Salida Planta',
            arrivalClient: 'Llegada Cliente',
            startInstallation: 'Inicio Instalación',
            endInstallation: 'Fin Instalación',
            returnToPlant: 'Regreso a Planta',
            arrivalPlant: 'Llegada Planta',
        };

        function formatDuration(minutes) {
            if (minutes >= 60) {
                const h = Math.floor(minutes / 60);
                const m = Math.round(minutes % 60);
                return m > 0 ? `${h} h ${m} min` : `${h} h`;
            }
            return `${Math.round(minutes)} min`;
        }

        function formatKm(km) {
            return km.toLocaleString('es-MX', {
                maximumFractionDigits: 1
            }) + ' km';
        }

        async function renderMap(origin, destinations, steps) {
            const google = await window.loadGoogleMaps(@js($googleMapsApiKey));
            const container = document.getElementById('detailMap');
            if (!container) return;
            const infoEl = document.getElementById('detailRouteInfo');

            const inViewport = () => {
                const r = container.getBoundingClientRect();
                return r.top < window.innerHeight && r.bottom > 0 && r.left < window.innerWidth && r
                    .right > 0;
            };
            if (!inViewport()) {
                await new Promise((resolve) => {
                    const io = new IntersectionObserver((entries) => {
                        if (entries[0].isIntersecting) {
                            io.disconnect();
                            resolve(true);
                        }
                    }, {
                        threshold: 0
                    });
                    io.observe(container);
                    setTimeout(() => {
                        io.disconnect();
                        resolve(false);
                    }, 10000);
                });
            }

            if (!map) {
                map = new google.maps.Map(container, {
                    center: {
                        lat: 19.43,
                        lng: -99.13
                    },
                    zoom: 10,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    mapTypeControl: false,
                    streetViewControl: false,
                    fullscreenControl: true,
                });
                setTimeout(() => google.maps.event.trigger(map, 'resize'), 60);
            } else {
                google.maps.event.trigger(map, 'resize');
            }

            markers.forEach(m => m.setMap(null));
            markers = [];
            polylines.forEach(p => p.setMap(null));
            polylines = [];
            if (directionsRenderer) {
                directionsRenderer.setMap(null);
                directionsRenderer = null;
            }
            if (infoEl) {
                infoEl.classList.add('hidden');
                infoEl.innerHTML = '';
            }

            const bounds = new google.maps.LatLngBounds();
            const geocoder = new google.maps.Geocoder();
            const geocode = (address) => new Promise(resolve => {
                geocoder.geocode({
                    address
                }, (res, status) => {
                    resolve(status === 'OK' && res.length > 0 ? res[0].geometry
                        .location :
                        null);
                });
            });

            const stopQueries = [];
            if (origin) stopQueries.push({
                text: origin,
                label: 'Origen'
            });
            (destinations || []).forEach((d) => {
                if (d) stopQueries.push({
                    text: d,
                    label: 'Destino'
                });
            });

            let stopCoords = [];
            if (stopQueries.length >= 1) {
                const results = await Promise.all(stopQueries.map(q => geocode(q.text)));
                stopCoords = stopQueries.map((q, i) => ({
                    ...q,
                    position: results[i]
                })).filter(r => r.position);
            }

            const drawStepOverlay = () => {
                steps.forEach((p, i) => {
                    const pos = {
                        lat: p.lat,
                        lng: p.lng
                    };
                    const color = stepColors[p.type] || '#64748b';
                    const label = stepLabels[p.type] || p.label || `Paso ${i + 1}`;
                    const marker = new google.maps.Marker({
                        position: pos,
                        map,
                        icon: window.MapPins.pin(window.MapPins.text(i + 1), color,
                            22),
                        zIndex: 10,
                        title: label,
                    });
                    marker.addListener('click', () => {
                        new google.maps.InfoWindow({
                            content: `<div style="font-size:12px;font-weight:700;margin-bottom:2px">${label}</div><div style="font-size:11px;color:#64748b">${p.lat.toFixed(4)}, ${p.lng.toFixed(4)}</div>`,
                        }).open(map, marker);
                    });
                    markers.push(marker);
                    bounds.extend(pos);
                });
                if (steps.length > 1) {
                    const polyline = new google.maps.Polyline({
                        path: steps.map(s => ({
                            lat: s.lat,
                            lng: s.lng
                        })),
                        geodesic: true,
                        strokeColor: '#8B5CF6',
                        strokeOpacity: 0.6,
                        strokeWeight: 2.5,
                        map,
                    });
                    polylines.push(polyline);
                }
            };

            if (stopCoords.length >= 2) {
                const addresses = stopCoords.map(c => c.text);
                const stops = stopCoords.map(c => c.position);
                const maxStops = 25;
                const truncated = stops.length > maxStops;
                const orderedStops = truncated ? stops.slice(0, maxStops) : stops;

                const stopMarkers = orderedStops.map((pos, i) => {
                    const isOrigin = i === 0;
                    const isLast = i === orderedStops.length - 1;
                    const marker = new google.maps.Marker({
                        position: pos,
                        map,
                        icon: isOrigin ? window.MapPins.pin(window.MapPins.drop,
                                '#E72085',
                                34) : isLast ? window.MapPins.flag(34) : window.MapPins
                            .pin(window.MapPins.text(i), '#10B981', 30),
                        zIndex: isLast ? 3 : (isOrigin ? 2 : 1),
                    });
                    marker.addListener('click', () => {
                        new google.maps.InfoWindow({
                            content: `<div style="font-size:12px;font-weight:700;margin-bottom:2px">${isOrigin ? 'Origen' : (isLast ? 'Destino final' : `Parada ${i}`)}</div><div style="font-size:11px;color:#64748b">${addresses[i] || ''}</div>`,
                        }).open(map, marker);
                    });
                    markers.push(marker);
                    bounds.extend(pos);
                    return marker;
                });

                const renderer = new google.maps.DirectionsRenderer({
                    map,
                    suppressMarkers: true,
                    polylineOptions: {
                        strokeColor: '#10B981',
                        strokeWeight: 4,
                        strokeOpacity: 0.8
                    },
                });
                directionsRenderer = renderer;

                const waypoints = orderedStops.slice(1, -1).map(location => ({
                    location,
                    stopover: true
                }));
                new google.maps.DirectionsService().route({
                    origin: orderedStops[0],
                    destination: orderedStops[orderedStops.length - 1],
                    waypoints,
                    travelMode: 'DRIVING',
                    optimizeWaypoints: true,
                    provideRouteAlternatives: false,
                }, (result, status) => {
                    if (status === 'OK') {
                        renderer.setDirections(result);
                        const legs = result.routes[0].legs;
                        const totalKm = legs.reduce((acc, l) => acc + l.distance.value, 0) /
                            1000;
                        const totalMin = legs.reduce((acc, l) => acc + l.duration.value, 0) /
                            60;
                        const trafficMin = legs.reduce((acc, l) => acc + (l
                            .duration_in_traffic ? l
                            .duration_in_traffic.value : 0), 0) / 60;
                        const waypointOrder = result.routes[0].waypoint_order || [];
                        const order = [0, ...waypointOrder.map(i => i + 1), orderedStops
                            .length -
                            1
                        ];
                        order.forEach((stopIdx, k) => {
                            const m = stopMarkers[stopIdx];
                            if (!m) return;
                            const isOrigin = k === 0;
                            const isLast = k === order.length - 1;
                            m.setIcon(isOrigin ? window.MapPins.pin(window.MapPins.drop,
                                    '#E72085', 34) :
                                isLast ? window.MapPins.flag(34) :
                                window.MapPins.pin(window.MapPins.text(k),
                                    '#10B981',
                                    30));
                        });
                        const hasTraffic = legs.length > 0 && legs.every(l => l
                            .duration_in_traffic);
                        const stopsHtml = order.map((origIdx, k) => {
                            const addr = addresses[origIdx] || (k === 0 ? 'Origen' :
                                `Parada ${k}`);
                            const isOrigin = k === 0;
                            const isLast = k === order.length - 1;
                            const color = isOrigin ? '#E72085' : (isLast ? '#008FD3' :
                                '#10B981');
                            const label = isOrigin ? 'Origen' : (isLast ?
                                'Destino final' :
                                `Parada ${k}`);
                            return `
                            <div class="flex items-start gap-1.5 text-[10px] text-slate-500">
                                <span class="w-2 h-2 mt-0.5 rounded-full shrink-0" style="background:${color}"></span>
                                <span class="font-semibold text-slate-400">${label}:</span>
                                <span>${addr}</span>
                            </div>`;
                        }).join('');
                        infoEl.innerHTML = `
                        <div class="flex items-center gap-2 text-[11px] font-black text-slate-800 uppercase tracking-wider">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            Ruta optimizada por Google · ${orderedStops.length - 1} destino${orderedStops.length - 1 > 1 ? 's' : ''}
                        </div>
                        ${truncated ? `<div class="mt-1.5 text-[10px] font-bold text-amber-600">Google limita a 25 puntos; solo se muestran los primeros ${maxStops}.</div>` : ''}
                        <div class="grid grid-cols-2 gap-2 pt-2">
                            <div class="rounded-lg bg-[#E72085]/5 border border-[#E72085]/15 p-2">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase">Distancia total</span>
                                <span class="text-xs font-black text-slate-800">${formatKm(totalKm)}</span>
                            </div>
                            <div class="rounded-lg bg-[#008FD3]/5 border border-[#008FD3]/15 p-2">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase">Tiempo estimado</span>
                                <span class="text-xs font-black text-slate-800">${formatDuration(totalMin)}</span>
                            </div>
                            ${hasTraffic && trafficMin ? `
                            <div class="rounded-lg bg-amber-50 border border-amber-100 p-2 col-span-2">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase">🚦 Con tráfico actual</span>
                                <span class="text-xs font-black text-slate-800">${formatDuration(trafficMin)}</span>
                            </div>` : ''}
                        </div>
                        <div class="pt-2 space-y-1 border-t border-slate-100">
                            ${stopsHtml}
                        </div>`;
                        infoEl.classList.remove('hidden');
                    } else {
                        infoEl.innerHTML = `
                        <div class="flex items-start gap-1.5 text-[10px] font-bold text-amber-600">
                            <span>⚠️</span>
                            <span>No se pudo calcular la ruta entre las direcciones (${status}).</span>
                        </div>`;
                        infoEl.classList.remove('hidden');
                    }
                });
                drawStepOverlay();
            } else if (stopCoords.length === 1) {
                map.setCenter(stopCoords[0].position);
                map.setZoom(12);
                const marker = new google.maps.Marker({
                    position: stopCoords[0].position,
                    map,
                    icon: window.MapPins.pin(window.MapPins.drop, '#E72085', 30),
                    zIndex: 2,
                });
                marker.addListener('click', () => {
                    new google.maps.InfoWindow({
                        content: `<div style="font-size:12px;font-weight:700;margin-bottom:2px">${stopCoords[0].label}</div><div style="font-size:11px;color:#64748b">${stopCoords[0].text}</div>`,
                    }).open(map, marker);
                });
                markers.push(marker);
                bounds.extend(stopCoords[0].position);
                drawStepOverlay();
            } else if (steps.length > 0) {
                drawStepOverlay();
            } else {
                map.setCenter({
                    lat: 19.43,
                    lng: -99.13
                });
                map.setZoom(5);
                return;
            }

            if (!bounds.isEmpty()) {
                map.fitBounds(bounds, {
                    top: 40,
                    right: 40,
                    bottom: 40,
                    left: 40
                });
            }
        }

        Livewire.on('route:detail-loaded', (data) => {
            setTimeout(() => {
                renderMap(data.origin || '', data.destinations || [], data.steps || []);
            }, 200);
        });
    });

</script>
<script>
    document.addEventListener('livewire:init', () => {
        let formMap = null;
        let formRenderer = null;
        let formMarkers = [];
        let formSyncTimer = null;
        const formCoords = {
            origin: null,
            destinations: []
        };
        const coordCache = new Map();

        async function ensureFormMap() {
            if (formMap) return formMap;
            const google = await window.loadGoogleMaps(@js($googleMapsApiKey));
            const el = document.getElementById('formMap');
            if (!el) return null;
            formMap = new google.maps.Map(el, {
                center: {
                    lat: 19.43,
                    lng: -99.13
                },
                zoom: 5,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false,
            });
            return formMap;
        }

        function clearFormMap() {
            formMarkers.forEach(m => m.setMap(null));
            formMarkers = [];
            if (formRenderer) {
                formRenderer.setMap(null);
                formRenderer = null;
            }
            const info = document.getElementById('formMapInfo');
            info.classList.add('hidden');
            info.innerHTML = '';
        }

        function setPlaceholder(show) {
            const placeholder = document.getElementById('formMapPlaceholder');
            if (placeholder) placeholder.classList.toggle('hidden', !show);
        }

        function formatKm(km) {
            return km.toLocaleString('es-MX', {
                maximumFractionDigits: 1
            }) + ' km';
        }

        function formatDuration(minutes) {
            if (minutes >= 60) {
                const h = Math.floor(minutes / 60);
                const m = Math.round(minutes % 60);
                return m > 0 ? `${h} h ${m} min` : `${h} h`;
            }
            return `${Math.round(minutes)} min`;
        }

        function getStopAddresses() {
            const out = [];
            const originEl = document.getElementById('originInput');
            out.push(originEl && originEl.value.trim() ? originEl.value.trim() : 'Origen');
            [...document.querySelectorAll('.js-destination-input')].forEach((el, i) => {
                if (formCoords.destinations[i]) {
                    out.push(el.value.trim() || `Destino ${i + 1}`);
                }
            });
            return out;
        }

        const PIN_DROP = window.MapPins.drop;
        const pinText = window.MapPins.text;
        const createPin = window.MapPins.pin;
        const createFlagPin = window.MapPins.flag;

        function drawFormRoute() {
            const google = window.google;
            const map = formMap;
            if (!google || !map) return;

            const destCoords = formCoords.destinations.filter(c => c);
            if (!formCoords.origin || destCoords.length === 0) {
                setPlaceholder(true);
                clearFormMap();
                return;
            }

            setPlaceholder(false);
            clearFormMap();

            const stops = [formCoords.origin, ...destCoords];
            const maxStops = 25;
            const truncated = stops.length > maxStops;
            const orderedStops = truncated ? stops.slice(0, maxStops) : stops;
            const addresses = getStopAddresses();

            const bounds = new google.maps.LatLngBounds();
            const stopMarkers = [];
            orderedStops.forEach((pos, i) => {
                const isOrigin = i === 0;
                const isLast = i === orderedStops.length - 1;
                const marker = new google.maps.Marker({
                    position: pos,
                    map,
                    icon: isOrigin ? createPin(PIN_DROP, '#E72085', 34) : (isLast ?
                        createFlagPin(34) : createPin(pinText(i), '#10B981', 30)),
                    zIndex: isLast ? 3 : (isOrigin ? 2 : 1),
                });
                marker.addListener('click', () => {
                    new google.maps.InfoWindow({
                        content: `<div style="font-size:12px;font-weight:700;margin-bottom:2px">${isOrigin ? 'Origen' : (isLast ? 'Destino final' : `Parada ${i}`)}</div><div style="font-size:11px;color:#64748b">${addresses[i] || ''}</div>`,
                    }).open(map, marker);
                });
                formMarkers.push(marker);
                stopMarkers.push(marker);
                bounds.extend(pos);
            });

            formRenderer = new google.maps.DirectionsRenderer({
                map,
                suppressMarkers: true,
                polylineOptions: {
                    strokeColor: '#10B981',
                    strokeWeight: 3,
                    strokeOpacity: 0.8
                },
            });
            const service = new google.maps.DirectionsService();
            const waypoints = orderedStops.slice(1, -1).map(location => ({
                location,
                stopover: true
            }));
            service.route({
                origin: orderedStops[0],
                destination: orderedStops[orderedStops.length - 1],
                waypoints,
                travelMode: 'DRIVING',
                drivingOptions: {
                    departureTime: new Date(),
                    trafficModel: 'bestguess',
                },
                optimizeWaypoints: true,
                provideRouteAlternatives: false,
            }, (result, status) => {
                if (status === 'OK') {
                    formRenderer.setDirections(result);
                    const legs = result.routes[0].legs;
                    const totalKm = legs.reduce((acc, l) => acc + l.distance.value, 0) / 1000;
                    const totalMin = legs.reduce((acc, l) => acc + l.duration.value, 0) / 60;
                    const trafficMin = legs.reduce((acc, l) => acc + (l.duration_in_traffic ? l
                        .duration_in_traffic.value : 0), 0) / 60;
                    const kmVal = totalKm.toFixed(1);
                    const durVal = (totalMin / 60).toFixed(1);
                    @this.set('distance_km', kmVal, false);
                    @this.set('estimated_duration', durVal, false);
                    const kmInput = document.querySelector('[wire\\:model="distance_km"]');
                    if (kmInput) kmInput.value = kmVal;
                    const durInput = document.querySelector('[wire\\:model="estimated_duration"]');
                    if (durInput) durInput.value = durVal;
                    const info = document.getElementById('formMapInfo');
                    const hasTraffic = legs.length > 0 && legs.every(l => l.duration_in_traffic);
                    const waypointOrder = result.routes[0].waypoint_order || [];
                    const order = [0, ...waypointOrder.map(i => i + 1), orderedStops.length - 1];
                    order.forEach((stopIdx, k) => {
                        const m = stopMarkers[stopIdx];
                        if (!m) return;
                        const isOrigin = k === 0;
                        const isLast = k === order.length - 1;
                        m.setIcon(isOrigin ? createPin(PIN_DROP, '#E72085', 34) : (isLast ?
                            createFlagPin(34) : createPin(pinText(k), '#10B981', 30)
                        ));
                    });
                    const stopsHtml = order.map((origIdx, k) => {
                        const addr = addresses[origIdx] || (k === 0 ? 'Origen' :
                            `Parada ${k}`);
                        const isOrigin = k === 0;
                        const isLast = k === order.length - 1;
                        const color = isOrigin ? '#E72085' : (isLast ? '#008FD3' :
                            '#10B981');
                        const label = isOrigin ? 'Origen' : (isLast ? 'Destino final' :
                            `Parada ${k}`);
                        return `
                        <div class="flex items-start gap-1.5 text-[10px] text-slate-500">
                            <span class="w-2 h-2 mt-0.5 rounded-full shrink-0" style="background:${color}"></span>
                            <span class="font-semibold text-slate-400">${label}:</span>
                            <span>${addr}</span>
                        </div>`;
                    }).join('');
                    info.innerHTML = `
                    <div class="flex items-center gap-2 text-[11px] font-black text-slate-800 uppercase tracking-wider">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        Ruta optimizada por Google · ${orderedStops.length - 1} destino${orderedStops.length - 1 > 1 ? 's' : ''}
                    </div>
                    ${truncated ? `<div class="mt-1.5 text-[10px] font-bold text-amber-600">Google limita a 25 puntos; solo se muestran los primeros ${maxStops}.</div>` : ''}
                    <div class="grid grid-cols-2 gap-2 pt-2">
                        <div class="rounded-lg bg-[#E72085]/5 border border-[#E72085]/15 p-2">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Distancia total</span>
                            <span class="text-xs font-black text-slate-800">${formatKm(totalKm)}</span>
                        </div>
                        <div class="rounded-lg bg-[#008FD3]/5 border border-[#008FD3]/15 p-2">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Tiempo estimado</span>
                            <span class="text-xs font-black text-slate-800">${formatDuration(totalMin)}</span>
                        </div>
                        ${hasTraffic && trafficMin ? `
                        <div class="rounded-lg bg-amber-50 border border-amber-100 p-2 col-span-2">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">🚦 Con tráfico actual</span>
                            <span class="text-xs font-black text-slate-800">${formatDuration(trafficMin)}</span>
                        </div>` : ''}
                    </div>
                    <div class="pt-2 space-y-1 border-t border-slate-100">
                        ${stopsHtml}
                    </div>
                `;
                    info.classList.remove('hidden');
                } else {
                    const info = document.getElementById('formMapInfo');
                    info.innerHTML = `
                    <div class="flex items-start gap-1.5 text-[10px] font-bold text-amber-600">
                        <span>⚠️</span>
                        <span>No se pudo calcular la ruta entre las direcciones (${status}). Verifica los destinos.</span>
                    </div>`;
                    info.classList.remove('hidden');
                }
            });

            map.fitBounds(bounds, {
                top: 40,
                right: 40,
                bottom: 40,
                left: 40
            });
        }

        async function syncFormRoute() {
            const google = await window.loadGoogleMaps(@js($googleMapsApiKey));
            const originInput = document.getElementById('originInput');
            if (!originInput) return;

            const destInputs = [...document.querySelectorAll('.js-destination-input')];
            const originText = originInput.value.trim();
            const destTexts = destInputs.map(el => el.value.trim());

            if (!originText || destTexts.every(t => !t)) {
                formCoords.origin = null;
                formCoords.destinations = [];
                drawFormRoute();
                return;
            }

            const geocoder = new google.maps.Geocoder();
            const geocode = (address) => new Promise(r =>
                geocoder.geocode({
                    address
                }, (res, st) => r(st === 'OK' && res[0] ? res[0].geometry.location : null))
            );
            const resolve = async (text) => {
                if (!text) return null;
                if (coordCache.has(text)) return coordCache.get(text);
                const loc = await geocode(text);
                if (loc) coordCache.set(text, loc);
                return loc;
            };

            formCoords.origin = await resolve(originText);
            formCoords.destinations = await Promise.all(destTexts.map(resolve));
            drawFormRoute();
        }

        function scheduleSync() {
            clearTimeout(formSyncTimer);
            formSyncTimer = setTimeout(syncFormRoute, 800);
        }

        function handlePlace(input, autocomplete, index) {
            const place = autocomplete.getPlace();
            if (place ?.geometry ?.location) {
                if (place.formatted_address) {
                    input.value = place.formatted_address;
                }
                coordCache.set(input.value, place.geometry.location);
                if (index === 'origin') {
                    formCoords.origin = place.geometry.location;
                    @this.set('origin', input.value, false);
                } else {
                    formCoords.destinations[index] = place.geometry.location;
                    @this.set('destinations.' + index, input.value, false);
                }
                input.blur();
                drawFormRoute();
            } else {
                if (index === 'origin') {
                    formCoords.origin = null;
                } else {
                    formCoords.destinations[index] = null;
                }
                scheduleSync();
            }
        }

        function initAutocomplete() {
            if (!window.google ?.maps ?.places) return;
            const originInput = document.getElementById('originInput');
            if (!originInput) return;

            const bindInput = (el) => {
                el.addEventListener('input', () => scheduleSync());
            };

            if (!originInput._ac) {
                const ac = new window.google.maps.places.Autocomplete(originInput, {
                    types: ['address'],
                    componentRestrictions: {
                        country: 'MX'
                    },
                });
                ac.addListener('place_changed', () => handlePlace(originInput, ac, 'origin'));
                originInput._ac = ac;
                bindInput(originInput);
            }

            document.querySelectorAll('.js-destination-input').forEach((el) => {
                if (el._ac) return;
                const getIndex = () => parseInt(el.getAttribute('data-index'), 10);
                const ac = new window.google.maps.places.Autocomplete(el, {
                    types: ['address'],
                    componentRestrictions: {
                        country: 'MX'
                    },
                });
                ac.addListener('place_changed', () => handlePlace(el, ac, getIndex()));
                el._ac = ac;
                bindInput(el);
            });
        }

        document.addEventListener('form:opened', async () => {
            const el = document.getElementById('formMap');
            if (el) await ensureFormMap();
            initAutocomplete();
            setTimeout(syncFormRoute, 300);
        });

        document.addEventListener('form:destinations-changed', () => {
            setTimeout(() => {
                initAutocomplete();
                syncFormRoute();
            }, 150);
        });

        const observer = new MutationObserver(() => initAutocomplete());
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });

</script>
@endpush
