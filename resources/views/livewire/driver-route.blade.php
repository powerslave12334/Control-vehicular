
<div class="space-y-5">
    <a href="{{ route('driver.dashboard') }}" class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-500 hover:text-[#E72085] transition">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Volver al inicio
    </a>

    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-5">
        <div class="flex items-start justify-between mb-3">
            <div>
                <h1 class="text-lg font-black text-slate-900">{{ $routeInfo['client_name'] }}</h1>
                <p class="text-xs text-slate-500 font-semibold">{{ $routeInfo['origin'] ?? '' }} &rarr; {{ $routeInfo['destination'] ?? '' }}</p>
            </div>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                @switch($routeInfo['status'])
                    @case('Completada') bg-emerald-50 text-emerald-600 @break
                    @case('En tránsito') bg-amber-50 text-amber-600 @break
                    @case('Instalando') bg-indigo-50 text-indigo-600 @break
                    @default bg-blue-50 text-blue-600
                @endswitch">{{ $routeInfo['status'] }}</span>
        </div>
        <div class="grid grid-cols-2 gap-3 text-xs">
            <div class="bg-slate-50 rounded-xl p-3">
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Unidad</p>
                <p class="font-bold text-slate-800">{{ $routeInfo['vehicle_plate'] ?? '—' }}</p>
            </div>
            <div class="bg-slate-50 rounded-xl p-3">
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Km Planeados</p>
                <p class="font-bold text-slate-800">{{ $routeInfo['planned_km'] ?? 0 }} km</p>
            </div>
        </div>
    </div>

    <div x-data="{ tab: 'bitacora' }">
        <div class="flex gap-1 border-b border-slate-200 mb-4 overflow-x-auto whitespace-nowrap scrollbar-hide">
            <button @click="tab = 'bitacora'" :class="tab === 'bitacora' ? 'border-[#E72085] text-[#E72085]' : 'border-transparent text-slate-500 hover:text-slate-700'" class="shrink-0 px-4 py-2.5 text-[10px] font-black uppercase tracking-wider border-b-2 transition">
                Bitácora
            </button>
            <button @click="tab = 'movimientos'" :class="tab === 'movimientos' ? 'border-[#E72085] text-[#E72085]' : 'border-transparent text-slate-500 hover:text-slate-700'" class="shrink-0 px-4 py-2.5 text-[10px] font-black uppercase tracking-wider border-b-2 transition">
                Mov. Extras
            </button>
            <button @click="tab = 'fallas'" :class="tab === 'fallas' ? 'border-[#E72085] text-[#E72085]' : 'border-transparent text-slate-500 hover:text-slate-700'" class="shrink-0 px-4 py-2.5 text-[10px] font-black uppercase tracking-wider border-b-2 transition">
                Reportar Fallas
            </button>
        </div>

        <div x-show="tab === 'bitacora'" class="space-y-5">
            @php
                $stepLabels = [
                    'departurePlant' => 'Salida de Planta',
                    'arrivalClient' => 'Llegada con Cliente',
                    'startInstallation' => 'Inicio de Instalación',
                    'endInstallation' => 'Fin de Instalación',
                    'returnToPlant' => 'Regreso a Planta',
                    'arrivalPlant' => 'Llegada a Planta',
                ];

                $pendingIndex = null;
                $index = 1;
                foreach ($steps as $s) {
                    if (!$s['photo']) {
                        $pendingIndex = $index;
                        break;
                    }
                    $index++;
                }
            @endphp

            @if($currentStep)
                <form x-on:submit.prevent="
                    const doSubmit = () => $wire.completeCurrentStep();
                    const fail = () => {
                        Swal.fire({ icon: 'error', title: 'Ubicación requerida', text: 'Para completar este paso debes permitir el acceso a tu ubicación. Verifica que el GPS esté activo y que el sitio use HTTPS.', confirmButtonColor: '#E72085' });
                    };
                    if ('geolocation' in navigator) {
                        navigator.geolocation.getCurrentPosition(
                            pos => { $wire.set('currentLat', pos.coords.latitude); $wire.set('currentLng', pos.coords.longitude); doSubmit(); },
                            fail,
                            { timeout: 15000, maximumAge: 60000, enableHighAccuracy: true }
                        );
                    } else {
                        fail();
                    }
                " class="bg-white rounded-2xl shadow-xs border border-slate-200 p-5 space-y-4">
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="text-base font-black text-slate-900">{{ $pendingIndex }}. Registro de {{ $stepLabels[$currentStep->step_type] ?? $currentStep->step_type }}</h2>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-600 uppercase tracking-wider">Pendiente</span>
                    </div>

                    @if($currentStep->step_type === 'departurePlant' || $currentStep->step_type === 'arrivalPlant')
                    <div>
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 block">
                            @if($currentStep->step_type === 'departurePlant')
                                Kilometraje Inicial
                            @else
                                Kilometraje Final
                            @endif
                        </label>
                        <input type="number" wire:model="formOdometer" step="1" min="{{ $currentOdometer }}" class="w-full bg-white border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none" placeholder="Ej. {{ $currentOdometer + 1 }}">
                        <p class="text-[10px] text-slate-400 mt-0.5">Mínimo: {{ number_format($currentOdometer) }} km (odómetro actual del vehículo)</p>
                        @error('formOdometer') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    @if(in_array($currentStep->step_type, ['departurePlant', 'arrivalClient', 'returnToPlant', 'arrivalPlant']))
                    <div>
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 block">Nivel de Combustible</label>
                        <select wire:model="formFuelLevel" class="w-full bg-white border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                            <option value="">— Sin especificar —</option>
                            <option value="1/4">1/4 (~{{ $tankCapacity ? round($tankCapacity * 0.25) : '?' }}L)</option>
                            <option value="1/2">1/2 (~{{ $tankCapacity ? round($tankCapacity * 0.5) : '?' }}L)</option>
                            <option value="3/4">3/4 (~{{ $tankCapacity ? round($tankCapacity * 0.75) : '?' }}L)</option>
                            <option value="Lleno">Lleno (~{{ $tankCapacity ? round($tankCapacity) : '?' }}L)</option>
                        </select>
                        @error('formFuelLevel') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <div>
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 block">Foto de Evidencia</label>
                        <div class="relative">
                            <input type="file" wire:model="formPhoto" accept="image/*" capture="environment" class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-[#E72085] file:text-white hover:file:bg-[#d01c73] transition">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">La cámara se abrirá automáticamente en dispositivos móviles</p>
                        @error('formPhoto') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5 block">Observaciones</label>
                        <textarea wire:model="formObservations" rows="3" class="w-full bg-white border border-slate-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none" placeholder="Notas adicionales..."></textarea>
                        @error('formObservations') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full px-5 py-3 rounded-xl bg-[#E72085] text-white text-sm font-black hover:bg-[#d01c73] transition shadow-lg shadow-[#E72085]/20 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        @if($currentStep->step_type === 'departurePlant')
                            Iniciar Viaje
                        @elseif($currentStep->step_type === 'arrivalPlant')
                            Finalizar Viaje
                        @elseif($currentStep->step_type === 'arrivalClient')
                            Registrar Llegada
                        @elseif($currentStep->step_type === 'startInstallation')
                            Iniciar Instalación
                        @elseif($currentStep->step_type === 'endInstallation')
                            Finalizar Instalación
                        @elseif($currentStep->step_type === 'returnToPlant')
                            Iniciar Regreso
                        @else
                            Completar Paso
                        @endif
                    </button>
                </form>
            @elseif(count($steps) > 0)
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-8 text-center">
                    <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-base font-black text-slate-800">Viaje Completado</p>
                    <p class="text-xs text-slate-500 mt-1">Todos los pasos han sido registrados exitosamente.</p>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-8 text-center">
                    <p class="text-sm font-bold text-slate-400">No hay pasos definidos para esta ruta</p>
                </div>
            @endif

            <div class="border-t border-slate-100 pt-4">
                <button wire:click="toggleRefuelForm" class="w-full flex items-center justify-between bg-amber-50 hover:bg-amber-100 rounded-xl px-4 py-3 transition">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span class="text-xs font-black text-amber-800">Carga de Combustible</span>
                    </div>
                    <svg class="w-4 h-4 text-amber-600 transition {{ $showRefuelForm ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>

                @if($showRefuelForm)
                <form wire:submit="saveRefuel" class="bg-amber-50/50 rounded-xl p-4 mt-2 space-y-3 border border-amber-100">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1 block">Odómetro</label>
                            <input type="number" wire:model="refuelOdometer" min="{{ $currentOdometer }}" class="w-full bg-white border border-amber-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none" placeholder="Km">
                            <p class="text-[10px] text-slate-400 mt-0.5">Mín: {{ number_format($currentOdometer) }} km</p>
                            @error('refuelOdometer') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1 block">Nivel Gasolina</label>
                            <select wire:model="refuelFuelLevel" class="w-full bg-white border border-amber-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none">
                                <option value="">Seleccionar...</option>
                                <option value="1/4">1/4 (~{{ $tankCapacity ? round($tankCapacity * 0.25) : '?' }}L)</option>
                                <option value="1/2">1/2 (~{{ $tankCapacity ? round($tankCapacity * 0.5) : '?' }}L)</option>
                                <option value="3/4">3/4 (~{{ $tankCapacity ? round($tankCapacity * 0.75) : '?' }}L)</option>
                                <option value="Lleno">Lleno (~{{ $tankCapacity ? round($tankCapacity) : '?' }}L)</option>
                            </select>
                            @error('refuelFuelLevel') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1 block">Litros</label>
                            <input type="number" wire:model="refuelLiters" step="0.01" min="0.01" class="w-full bg-white border border-amber-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none" placeholder="Ej. 20.5">
                            @error('refuelLiters') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1 block">Total Gastado</label>
                            <input type="number" wire:model="refuelAmount" step="0.01" min="0.01" class="w-full bg-white border border-amber-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none" placeholder="Ej. 850.00">
                            @error('refuelAmount') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1 block">Foto del Ticket</label>
                        <input type="file" wire:model="refuelTicketPhoto" accept="image/*" capture="environment" class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:bg-amber-600 file:text-white hover:file:bg-amber-700">
                        @error('refuelTicketPhoto') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="w-full px-4 py-2 rounded-lg bg-amber-600 text-white text-[10px] font-black hover:bg-amber-700 transition shadow-sm">Registrar Carga</button>
                </form>
                @endif

                @if(count($refuels) > 0)
                <div class="space-y-1.5 mt-3">
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Cargas Registradas</p>
                    @foreach($refuels as $rf)
                    <div class="flex items-center gap-2 bg-white rounded-lg border border-slate-100 p-2.5">
                        <div class="w-6 h-6 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-bold text-slate-800">{{ number_format((float) $rf['liters'], 2) }}L — ${{ number_format((float) $rf['amount'], 2) }}</p>
                            <p class="text-[10px] text-slate-400">{{ $rf['odometer'] ? $rf['odometer'] . ' km' : '' }}{{ $rf['fuel_level'] && isset($levelLiters[$rf['fuel_level']]) ? ' · ' . $levelLiters[$rf['fuel_level']] : ($rf['fuel_level'] ? ' · ' . $rf['fuel_level'] : '') }}</p>
                        </div>
                        <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($rf['created_at'])->format('H:i') }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="space-y-1.5">
                <h3 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2">Progreso</h3>
                @php
                    $stepIndex = 1;
                    $levelLiters = $tankCapacity ? [
                        '1/4' => '1/4 (~' . round($tankCapacity * 0.25) . 'L)',
                        '1/2' => '1/2 (~' . round($tankCapacity * 0.5) . 'L)',
                        '3/4' => '3/4 (~' . round($tankCapacity * 0.75) . 'L)',
                        'Lleno' => 'Lleno (~' . round($tankCapacity) . 'L)',
                    ] : [];
                @endphp
                @foreach($steps as $s)
                <div class="flex items-center gap-3 {{ $s['photo'] ? 'text-emerald-600' : ($currentStep && $s['id'] === $currentStep->id ? 'text-[#E72085]' : 'text-slate-400') }}">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-black shrink-0 {{ $s['photo'] ? 'bg-emerald-100 text-emerald-600' : ($currentStep && $s['id'] === $currentStep->id ? 'bg-[#E72085]/10 text-[#E72085] ring-2 ring-[#E72085]/30' : 'bg-slate-100 text-slate-400') }}">
                        @if($s['photo'])
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        @else
                        {{ $stepIndex }}
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold {{ $s['photo'] ? 'text-slate-700' : ($currentStep && $s['id'] === $currentStep->id ? 'text-slate-900' : 'text-slate-400') }}">
                            {{ $stepIndex }}. {{ $stepLabels[$s['step_type']] ?? $s['step_type'] }}
                        </p>
                        @if($s['photo'])
                        <p class="text-[10px] text-emerald-500 font-semibold">{{ \Carbon\Carbon::parse($s['timestamp'])->format('H:i') }}{{ $s['fuel_level'] && isset($levelLiters[$s['fuel_level']]) ? ' · ' . $levelLiters[$s['fuel_level']] : ($s['fuel_level'] ? ' · ' . $s['fuel_level'] : '') }}</p>
                        @endif
                    </div>
                </div>
                @php $stepIndex++; @endphp
                @endforeach
            </div>
        </div>

        @if($tripSummary)
        <div x-data="{ show: @entangle('showTripSummary') }"
             x-show="show"
             x-cloak
             x-transition.opacity.duration.300ms
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="display: none;">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-sm p-6">
                <div class="text-center mb-4">
                    <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-2">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="text-base font-black text-slate-900">Viaje Finalizado</h3>
                    <p class="text-[10px] text-slate-500">{{ $routeInfo['client_name'] }}</p>
                </div>

                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-slate-50 rounded-xl p-3 text-center">
                            <p class="text-lg font-black text-slate-800">{{ number_format($tripSummary['distance']) }}</p>
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Km Recorridos</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3 text-center">
                            <p class="text-lg font-black text-blue-600">{{ number_format($tripSummary['total_liters'], 1) }}</p>
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Litros Cargados</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-slate-50 rounded-xl p-3 text-center">
                            <p class="text-lg font-black {{ $tripSummary['km_per_liter'] && $tripSummary['km_per_liter'] >= 8 ? 'text-emerald-600' : ($tripSummary['km_per_liter'] && $tripSummary['km_per_liter'] >= 5 ? 'text-amber-600' : 'text-red-500') }}">
                                {{ $tripSummary['km_per_liter'] ? number_format($tripSummary['km_per_liter'], 1) : '—' }}
                            </p>
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">km/L</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3 text-center">
                            <p class="text-lg font-black text-[#E72085]">${{ number_format($tripSummary['total_amount'], 2) }}</p>
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Total Gastado</p>
                        </div>
                    </div>

                    @if($tripSummary['cost_per_km'])
                    <div class="bg-slate-50 rounded-xl p-3 text-center">
                        <p class="text-lg font-black text-slate-600">${{ number_format($tripSummary['cost_per_km'], 2) }}</p>
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Costo por km</p>
                    </div>
                    @endif
                </div>

                <div class="mt-4">
                    <a href="{{ route('driver.dashboard') }}" class="block w-full px-4 py-2.5 rounded-xl bg-[#E72085] text-white text-xs font-black text-center hover:bg-[#d01c73] transition shadow-md shadow-[#E72085]/15">
                        Volver al inicio
                    </a>
                </div>
            </div>
        </div>
        @endif

        <div x-show="tab === 'movimientos'" class="space-y-4">
            <form wire:submit="saveExtraMovement" class="bg-white rounded-2xl shadow-xs border border-slate-200 p-4 space-y-3">
                <h3 class="text-xs font-black text-slate-800">Nuevo Movimiento Extra</h3>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Tipo</label>
                    <select wire:model="em_type" class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        <option value="">Seleccionar...</option>
                        @foreach($movementTypes as $mt)
                        <option value="{{ $mt->value }}">{{ $mt->label }}</option>
                        @endforeach
                    </select>
                    @error('em_type') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Descripción</label>
                    <textarea wire:model="em_description" rows="2" class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none"></textarea>
                    @error('em_description') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Foto (opcional)</label>
                    <input type="file" wire:model="em_photo" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:bg-[#E72085] file:text-white hover:file:bg-[#d01c73]">
                    @error('em_photo') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl bg-[#E72085] text-white text-[10px] font-black hover:bg-[#d01c73] transition shadow-md shadow-[#E72085]/15">Guardar</button>
            </form>

            @if(count($extraMovements) > 0)
            <div class="space-y-2">
                <h3 class="text-xs font-black text-slate-800">Movimientos Registrados</h3>
                @foreach($extraMovements as $em)
                <div class="bg-white rounded-xl border border-slate-200 p-3 flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-[11px] font-bold text-slate-800">{{ $em['type'] }}</p>
                        <p class="text-[10px] text-slate-500">{{ $em['description'] }}</p>
                        @if($em['timestamp'])
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($em['timestamp'])->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div x-show="tab === 'fallas'" class="space-y-4">
            <form wire:submit="saveIncident" class="bg-white rounded-2xl shadow-xs border border-slate-200 p-4 space-y-3">
                <h3 class="text-xs font-black text-slate-800">Reportar Nueva Falla</h3>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Descripción</label>
                    <textarea wire:model="inc_description" rows="2" class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none"></textarea>
                    @error('inc_description') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Severidad</label>
                    <select wire:model="inc_severity" class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        @foreach($incidentSeverities as $is)
                        <option value="{{ $is->value }}">{{ $is->label }}</option>
                        @endforeach
                    </select>
                    @error('inc_severity') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl bg-amber-500 text-white text-[10px] font-black hover:bg-amber-600 transition shadow-md shadow-amber-500/15">Reportar</button>
            </form>

            @if(count($incidents) > 0)
            <div class="space-y-2">
                <h3 class="text-xs font-black text-slate-800">Fallas Reportadas</h3>
                @foreach($incidents as $inc)
                <div class="bg-white rounded-xl border border-slate-200 p-3 flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-[11px] font-bold text-slate-800">{{ $inc['description'] }}</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold
                                {{ $inc['severity'] === 'Alta' || $inc['severity'] === 'Crítica' ? 'bg-red-100 text-red-700' : ($inc['severity'] === 'Media' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ $inc['severity'] }}
                            </span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">{{ $inc['status'] }}</span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($inc['created_at'])->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
