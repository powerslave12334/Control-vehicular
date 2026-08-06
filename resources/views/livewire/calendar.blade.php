<div class="bg-white rounded-3xl shadow-xs border border-slate-200 p-6 md:p-8 space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-black text-slate-800 tracking-tight">Calendario de Rutas</h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">{{ $view === 'mes' ? 'Tablero mensual de despacho' : 'Tablero semanal de despacho' }}</p>
        </div>

        @php $summary = $view === 'mes' ? $monthSummary : $weekSummary; @endphp

        {{-- SUMMARY --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2">
            <div class="bg-white rounded-xl border border-slate-200 px-4 py-3">
                <p class="font-mono text-lg font-black tabular-nums text-slate-800 leading-none">{{ $summary['routes'] }}</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1.5">rutas</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 px-4 py-3">
                <p class="font-mono text-lg font-black tabular-nums text-slate-800 leading-none">{{ number_format($summary['km'], 0) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1.5">km planeados</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 border-l-4 border-l-amber-500 px-4 py-3">
                <p class="font-mono text-lg font-black tabular-nums text-slate-800 leading-none">{{ $summary['active'] }}</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1.5">en tránsito / instalando</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 border-l-4 border-l-emerald-500 px-4 py-3">
                <p class="font-mono text-lg font-black tabular-nums text-slate-800 leading-none">{{ $summary['completed'] }}</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1.5">completadas</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 border-l-4 border-l-red-500 px-4 py-3">
                <p class="font-mono text-lg font-black tabular-nums text-slate-800 leading-none">{{ $summary['cancelled'] }}</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1.5">canceladas</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <div class="flex items-center bg-slate-100 rounded-lg p-0.5">
                <button wire:click="switchView('semana')"
                    class="px-3 py-1.5 rounded-md text-[11px] font-bold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#E72085]/30
                        {{ $view === 'semana' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Semana</button>
                <button wire:click="switchView('mes')"
                    class="px-3 py-1.5 rounded-md text-[11px] font-bold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#E72085]/30
                        {{ $view === 'mes' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">Mes</button>
            </div>
            <div class="w-px h-6 bg-slate-200"></div>
            <button wire:click="previous"
                class="flex items-center gap-1 px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#E72085]/30">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Anterior
            </button>
            <span class="font-mono text-xs font-bold text-gray-700 px-2 tabular-nums">{{ $view === 'mes' ? $monthRange : $weekRange }}</span>
            <button wire:click="next"
                class="flex items-center gap-1 px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#E72085]/30">
                Siguiente
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <button wire:click="today"
                class="bg-[#E72085] text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-[#d01c73] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#E72085]/40 focus-visible:ring-offset-1">Hoy</button>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="flex flex-col lg:flex-row gap-3 items-start lg:items-center justify-between">
        <div class="flex flex-wrap gap-2 items-center">
            <input wire:model.live.debounce.300ms="filterSearch" type="text"
                placeholder="Buscar (cliente, folio, placa, operador...)"
                class="px-3 py-2 rounded-lg border border-slate-200 text-xs font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] w-56">
            <select wire:model.live="filterStatus"
                class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                <option value="">Estatus: Todos</option>
                @foreach($routeStatuses as $rs)
                <option value="{{ $rs->value }}">{{ $rs->label }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterVehicleId"
                class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                <option value="">Vehículo: Todos</option>
                @foreach($vehicles as $v)
                <option value="{{ $v->id }}">{{ $v->plate }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterOperatorId"
                class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                <option value="">Operador: Todos</option>
                @foreach($operatorsList as $op)
                <option value="{{ $op['id'] }}">{{ $op['name'] }}</option>
                @endforeach
            </select>
            @if($filterSearch || $filterStatus || $filterVehicleId || $filterOperatorId)
            <button wire:click="clearFilters"
                class="px-3 py-2 rounded-lg text-[11px] font-bold text-slate-500 hover:bg-slate-100 transition">Limpiar</button>
            @endif
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
        @php
        $detailStatus = $detailRecord?->status?->value ?? $detailRecord?->status;
        $statusPill = match($detailStatus) {
            'Completada' => 'bg-emerald-100 text-emerald-700',
            'Cancelada' => 'bg-red-100 text-red-700',
            'En tránsito' => 'bg-amber-100 text-amber-700',
            'Instalando' => 'bg-indigo-100 text-indigo-700',
            default => 'bg-slate-100 text-slate-600',
        };
        $stepLabelMap = [
            'departurePlant' => 'Salida Planta',
            'arrivalClient' => 'Llegada Cliente',
            'startInstallation' => 'Inicio Instalación',
            'endInstallation' => 'Fin Instalación',
            'returnToPlant' => 'Regreso a Planta',
            'arrivalPlant' => 'Llegada Planta',
        ];
        @endphp
        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-[620px] max-h-[90vh] overflow-y-auto"
             @click.outside="show = false">
            @if($detailRecord)
            <div class="px-6 py-5 border-b border-slate-100 flex items-start justify-between gap-4">
                <div>
                    <p class="font-mono text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $detailRecord->code ?? 'RUT #'.$detailRecord->id }}</p>
                    <h3 class="text-base font-black text-slate-900 mt-0.5">{{ $detailRecord->client_name ?? '—' }}</h3>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="px-2 py-0.5 rounded-full font-bold text-[10px] {{ $statusPill }}">{{ $detailStatus }}</span>
                        <span class="font-mono text-[10px] font-bold text-slate-400 tabular-nums">{{ $detailRecord->date?->format('d/m/Y') ?? '—' }}</span>
                    </div>
                </div>
                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-600 transition p-1 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#E72085]/30 rounded">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div><span class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Conductor</span><p class="font-bold text-gray-800 mt-0.5">{{ $detailRecord->driver_name }}</p></div>
                    <div><span class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Vehículo</span><p class="font-mono font-bold text-gray-800 mt-0.5">{{ $detailRecord->vehicle_plate }}</p></div>
                    <div><span class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Ayudante</span><p class="font-bold text-gray-800 mt-0.5">{{ $detailRecord->assistant_name ?? '—' }}</p></div>
                    <div><span class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Semana</span><p class="font-bold text-gray-800 mt-0.5">{{ $detailRecord->week ?? '—' }}</p></div>
                </div>

                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Ubicación</h4>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="col-span-2"><span class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Origen</span><p class="font-bold text-gray-800 mt-0.5">{{ $detailRecord->origin ?? '—' }}</p></div>
                        <div class="col-span-2"><span class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Destino(s)</span>
                            @if(!empty($detailRecord->destinations))
                            <div class="mt-0.5 space-y-0.5">
                                @foreach($detailRecord->destinations as $dest)
                                <p class="font-bold text-gray-800">{{ $dest }}</p>
                                @endforeach
                            </div>
                            @else
                            <p class="font-bold text-gray-800">{{ $detailRecord->destination ?? '—' }}</p>
                            @endif
                        </div>
                        <div><span class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Ciudad</span><p class="font-bold text-gray-800 mt-0.5">{{ $detailRecord->city ?? '—' }}</p></div>
                        <div><span class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Estado</span><p class="font-bold text-gray-800 mt-0.5">{{ $detailRecord->state ?? '—' }}</p></div>
                    </div>
                </div>

                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Kilometraje</h4>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div><span class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Planeados</span><p class="font-mono font-black text-gray-800 mt-0.5 tabular-nums">{{ number_format($detailRecord->planned_km, 0) }} km</p></div>
                        <div><span class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Distancia</span><p class="font-mono font-black text-gray-800 mt-0.5 tabular-nums">{{ $detailRecord->distance_km ? number_format($detailRecord->distance_km, 0).' km' : '—' }}</p></div>
                    </div>
                </div>

                @if($detailRecord->description)
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Descripción</h4>
                    <p class="text-xs text-gray-600 leading-relaxed">{{ $detailRecord->description }}</p>
                </div>
                @endif

                @if(($detailRecord->status?->value ?? $detailRecord->status) === 'Cancelada' && $detailRecord->cancellation_reason)
                <div>
                    <h4 class="text-[10px] font-black text-red-500 uppercase tracking-wider mb-2 pb-1 border-b border-red-100">Motivo de cancelación</h4>
                    <p class="text-xs text-red-600 leading-relaxed">"{{ $detailRecord->cancellation_reason }}"</p>
                </div>
                @endif

                @if($detailRecord->steps->count() > 0)
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-3 pb-1 border-b border-gray-100">Recorrido ({{ $detailRecord->steps->count() }})</h4>
                    <div class="space-y-0">
                        @foreach($detailRecord->steps as $i => $step)
                        @php $hasCoords = $step->latitude && $step->longitude; @endphp
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <span class="w-5 h-5 rounded-full {{ $hasCoords ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-[10px] font-black shrink-0">
                                    @if($hasCoords)
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                    {{ $i + 1 }}
                                    @endif
                                </span>
                                @if(!$loop->last)<span class="w-px flex-1 bg-slate-200"></span>@endif
                            </div>
                            <div class="pb-3 min-w-0">
                                <p class="font-bold text-gray-800 text-xs">{{ $stepLabelMap[$step->step_type] ?? ($step->description ?? $step->step_type) }}</p>
                                <p class="font-mono text-[10px] text-slate-400 tabular-nums">{{ $step->timestamp?->format('d/m H:i') ?? '—' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                <button type="button" @click="show = false"
                    class="w-full px-6 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 text-xs font-black hover:bg-slate-100 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#E72085]/30">Cerrar</button>
            </div>
            @endif
        </div>
    </div>

    {{-- WEEK GRID --}}
    @if($view === 'semana')
    <div class="rounded-xl overflow-hidden border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[820px] border-separate border-spacing-0">
                <thead>
                    <tr>
                        <th class="sticky left-0 bg-slate-50 z-20 text-left px-3 py-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-wider w-36 border-b border-slate-200">Operador</th>
                        @foreach($weekDays as $day)
                        @php
                        $isToday = $day->isToday();
                        $isPast = $day->lt(\Carbon\Carbon::today());
                        @endphp
                        <th class="text-center px-2 py-3 text-[10px] font-extrabold uppercase tracking-wider border-b border-slate-200
                            {{ $isToday ? 'bg-[#E72085] text-white' : ($isPast ? 'bg-slate-100 text-slate-400' : 'bg-slate-50 text-slate-500') }}">
                            <span class="block">{{ $day->locale('es')->shortDayName }}</span>
                            <span class="block font-mono text-sm font-black tabular-nums {{ $isToday ? 'text-white' : '' }}">{{ $day->format('d') }}</span>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($operatorsList as $op)
                    <tr class="hover:bg-slate-50">
                        <td class="sticky left-0 bg-white z-10 px-3 py-3 font-bold text-slate-800 text-xs border-r border-slate-100">
                            {{ $op['name'] }}
                        </td>
                        @foreach($weekDays as $day)
                        @php
                        $dayIndex = $day->dayOfWeek;
                        $dayRoutes = $calendarData[$op['id']][$dayIndex] ?? [];
                        $isToday = $day->isToday();
                        $isPast = $day->lt(\Carbon\Carbon::today());
                        @endphp
                        <td class="p-1.5 align-top border-r border-slate-100 {{ $isToday ? 'bg-[#E72085]/[0.03]' : ($isPast ? 'bg-slate-50/60' : 'bg-white') }}">
                            @forelse($dayRoutes as $route)
                            @php
                            $status = $route['status'];
                            $isCancelled = $status === 'Cancelada';
                            $stripColor = match($status) {
                                'Completada' => 'bg-emerald-500',
                                'En tránsito' => 'bg-amber-500',
                                'Instalando' => 'bg-indigo-500',
                                'Cancelada' => 'bg-red-500',
                                default => 'bg-slate-400',
                            };
                            @endphp
                            <button wire:click="showExpediente({{ $route['id'] }})"
                                title="{{ $status }}{{ $route['code'] ? ' · '.$route['code'] : '' }}"
                                class="relative w-[200px] text-left mb-1 rounded-lg border bg-white px-1.5 py-1 transition motion-safe:hover:-translate-y-px motion-safe:hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#E72085] focus-visible:ring-offset-1
                                    {{ $isCancelled ? 'border-red-200 opacity-60' : 'border-slate-200' }}
                                    {{ $isPast && !$isCancelled ? 'opacity-55' : '' }}
                                    {{ $isToday ? 'border-[#E72085]/30' : '' }}">
                                <span class="absolute left-0 top-0 bottom-0 w-0.5 rounded-l-lg {{ $stripColor }}"></span>
                                <span class="block pl-1.5">
                                    <span class="flex items-center justify-between gap-1">
                                        <span class="font-mono text-[10px] font-bold text-slate-400 uppercase tracking-wider truncate">{{ $route['code'] ?? 'RUT #'.$route['id'] }}</span>
                                        <span class="w-1 h-1 rounded-full {{ $stripColor }} shrink-0"></span>
                                    </span>
                                    <span class="block text-[10px] font-bold text-slate-700 leading-tight truncate">{{ $route['client'] }}</span>
                                    <span class="block font-mono text-[10px] text-slate-400 tabular-nums truncate">{{ $route['plate'] }} · {{ number_format((float) $route['planned_km'], 0) }} km</span>
                                </span>
                            </button>
                            @empty
                            <a href="{{ route('routes') }}" title="Programar ruta"
                                class="h-10 mb-1 flex items-center justify-center rounded-lg border border-dashed border-slate-200 text-slate-300 hover:border-[#008FD3]/40 hover:bg-blue-50/40 hover:text-[#008FD3] transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#008FD3]/30">
                                <svg class="w-3 h-3 opacity-40 hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            </a>
                            @endforelse
                        </td>
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12 text-slate-400 text-sm font-bold">Sin conductores asignados esta semana</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- MONTH GRID --}}
    @if($view === 'mes')
    @if($summary['routes'] === 0)
    <div class="flex items-center gap-2 px-4 py-3 rounded-xl border border-dashed border-slate-200 text-slate-400 text-xs font-bold">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Sin rutas este mes — programa desde <a href="{{ route('routes') }}" class="text-[#008FD3] hover:underline">Rutas</a></span>
    </div>
    @endif
    <div class="rounded-xl overflow-hidden border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[820px] border-separate border-spacing-0">
                <thead>
                    <tr>
                        @foreach(array_slice($monthCells, 0, 7) as $d)
                        <th class="bg-slate-50 text-center px-2 py-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-wider border-b border-slate-200">{{ $d->locale('es')->shortDayName }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach(collect($monthCells)->chunk(7) as $week)
                    <tr>
                        @foreach($week as $day)
                        @php
                        $dateKey = $day->format('Y-m-d');
                        $monthNo = (int) $day->format('n');
                        $inMonth = $monthNo === (int) \Carbon\Carbon::parse($monthStart)->format('n') && (int) $day->format('Y') === (int) \Carbon\Carbon::parse($monthStart)->format('Y');
                        $isToday = $day->isToday();
                        $isPast = $day->lt(\Carbon\Carbon::today());
                        $dayRoutes = $monthlyData[$dateKey] ?? [];
                        @endphp
                        <td class="p-1.5 align-top border-r border-slate-100 {{ $inMonth ? ($isToday ? 'bg-[#E72085]/[0.04]' : ($isPast ? 'bg-slate-50/60' : 'bg-white')) : 'bg-slate-50/70' }}">
                            <div class="px-0.5 pb-1">
                                <span class="font-mono text-[11px] font-black tabular-nums {{ $isToday ? 'text-[#E72085]' : ($inMonth ? 'text-slate-700' : 'text-slate-300') }}">{{ $day->format('d') }}</span>
                            </div>
                            <div class="{{ $inMonth ? '' : 'opacity-50' }}">
                                @forelse($dayRoutes as $route)
                                @php
                                $status = $route['status'];
                                $isCancelled = $status === 'Cancelada';
                                $stripColor = match($status) {
                                    'Completada' => 'bg-emerald-500',
                                    'En tránsito' => 'bg-amber-500',
                                    'Instalando' => 'bg-indigo-500',
                                    'Cancelada' => 'bg-red-500',
                                    default => 'bg-slate-400',
                                };
                                @endphp
                                <button wire:click="showExpediente({{ $route['id'] }})"
                                    title="{{ $status }}{{ $route['code'] ? ' · '.$route['code'] : '' }}"
                                    class="relative w-[200px] text-left mb-1 rounded-lg border bg-white px-1.5 py-1 transition motion-safe:hover:-translate-y-px motion-safe:hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#E72085] focus-visible:ring-offset-1
                                        {{ $isCancelled ? 'border-red-200 opacity-60' : 'border-slate-200' }}
                                        {{ $isPast && !$isCancelled ? 'opacity-55' : '' }}
                                        {{ $isToday ? 'border-[#E72085]/30' : '' }}">
                                    <span class="absolute left-0 top-0 bottom-0 w-0.5 rounded-l-lg {{ $stripColor }}"></span>
                                    <span class="block pl-1.5">
                                        <span class="flex items-center justify-between gap-1">
                                            <span class="font-mono text-[10px] font-bold text-slate-400 uppercase tracking-wider truncate">{{ $route['code'] ?? 'RUT #'.$route['id'] }}</span>
                                            <span class="w-1 h-1 rounded-full {{ $stripColor }} shrink-0"></span>
                                        </span>
                                        <span class="block text-[10px] font-bold text-slate-700 leading-tight truncate">{{ $route['client'] }}</span>
                                        <span class="block font-mono text-[10px] text-slate-400 tabular-nums truncate">{{ $route['driver'] }} · {{ $route['plate'] }} · {{ number_format((float) $route['planned_km'], 0) }} km</span>
                                    </span>
                                </button>
                                @empty
                                @if($inMonth)
                                <a href="{{ route('routes') }}" title="Programar ruta"
                                    class="h-10 mb-1 flex items-center justify-center rounded-lg border border-dashed border-slate-200 text-slate-300 hover:border-[#008FD3]/40 hover:bg-blue-50/40 hover:text-[#008FD3] transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#008FD3]/30">
                                    <svg class="w-3 h-3 opacity-40 hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                </a>
                                @endif
                                @endforelse
                            </div>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- LEGEND --}}
    <div class="flex flex-wrap items-center gap-x-5 gap-y-2 px-4 py-3 bg-slate-50 rounded-xl text-[10px] text-slate-500 font-bold">
        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-slate-400"></span> Programada</span>
        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-amber-500"></span> En tránsito</span>
        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-indigo-500"></span> Instalando</span>
        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-emerald-500"></span> Completada</span>
        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-red-500"></span> Cancelada</span>
        <span class="flex items-center gap-1.5 ml-auto text-slate-400">
            @if($view === 'mes')
            <span class="w-4 h-[3px] rounded-full bg-slate-300"></span> Días tenues = fuera del mes
            @else
            <span class="w-4 h-[3px] rounded-full bg-slate-300"></span> Días atenuados = pasados
            @endif
        </span>
    </div>
</div>
