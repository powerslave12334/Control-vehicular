
@php
$fuelStatusStyles = [
    'pendiente' => ['bg-amber-100 text-amber-700', 'Pendiente'],
    'aprobado'  => ['bg-emerald-100 text-emerald-700', 'Aprobado'],
    'rechazado' => ['bg-red-100 text-red-600', 'Rechazado'],
];
@endphp

<div x-data="{ lightbox: false, lightboxSrc: '' }" class="bg-white rounded-3xl shadow-xs border border-slate-200 p-6 md:p-8 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-black text-slate-800">Control de Combustible y Presupuestos Semanales</h1>
            <p class="text-xs text-slate-400 font-semibold mt-0.5">Contraste de Consumo Real vs. Consumo Autorizado por semana.</p>
        </div>
        <button wire:click="create" class="bg-[#E72085] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-md shadow-[#E72085]/15 px-4 py-2 flex items-center gap-2 hover:bg-[#d01c73] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Registrar Carga
        </button>
    </div>

    {{-- LIGHTBOX --}}
    <div x-show="lightbox" x-cloak x-transition.opacity.duration.200ms @keydown.escape.window="lightbox = false"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm" style="display: none;">
        <button type="button" @click="lightbox = false" class="absolute top-4 right-4 text-white/70 hover:text-white transition p-2">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <img :src="lightboxSrc" class="max-w-full max-h-[85vh] rounded-xl shadow-2xl object-contain">
    </div>

    <div class="flex items-center gap-3">
        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Semana:</label>
        <select wire:model.live="selectedWeek" class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
            @foreach($weeks as $val => $label)
            <option value="{{ $val }}">{{ $label }}</option>
            @endforeach
        </select>
        <span class="text-[10px] text-slate-400 font-semibold">({{ $weekLabel }})</span>
    </div>

    {{-- WEEK SUMMARY --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
        <div class="bg-white rounded-xl border border-slate-200 px-4 py-3">
            <p class="font-mono text-lg font-black tabular-nums text-slate-800 leading-none">{{ number_format($fuelSummary['liters'], 1) }}</p>
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1.5">litros</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 px-4 py-3">
            <p class="font-mono text-lg font-black tabular-nums text-slate-800 leading-none">${{ number_format($fuelSummary['amount'], 2) }}</p>
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1.5">monto</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 px-4 py-3">
            <p class="font-mono text-lg font-black tabular-nums text-slate-800 leading-none">{{ number_format($fuelSummary['distance']) }}</p>
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1.5">km recorridos</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 px-4 py-3">
            @php $kml = $fuelSummary['distance'] > 0 && $fuelSummary['liters'] > 0 ? $fuelSummary['distance'] / $fuelSummary['liters'] : 0; @endphp
            <p class="font-mono text-lg font-black tabular-nums text-slate-800 leading-none">{{ $kml > 0 ? number_format($kml, 1) : '—' }}</p>
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1.5">km/litro</p>
        </div>
    </div>

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
                <h3 class="text-sm font-black text-slate-800">{{ $editId ? 'Editar' : 'Registrar' }} Carga de Combustible</h3>
                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-600 transition p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fecha</label>
                    <input wire:model="date" type="date" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('date') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Litros</label>
                    <input wire:model="liters" type="number" step="0.1" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('liters') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Monto ($)</label>
                    <input wire:model="amount" type="number" step="0.01" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('amount') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Odómetro</label>
                    <input wire:model="odometer" type="number" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('odometer') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Método de Pago</label>
                    <select wire:model="payment_method" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        @foreach($paymentMethods as $pm)
                        <option value="{{ $pm->value }}">{{ $pm->label }}</option>
                        @endforeach
                    </select>
                    @error('payment_method') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Operador</label>
                    <select wire:model="driver_id" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        <option value="">Seleccionar...</option>
                        @foreach($operators as $op)
                        <option value="{{ $op->id }}">{{ $op->name }} · {{ $op->role }}</option>
                        @endforeach
                    </select>
                    @error('driver_id') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Folio</label>
                    <input wire:model="folio" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('folio') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Gasolinera</label>
                    <select wire:model="fuel_station_id" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        <option value="">Seleccionar...</option>
                        @foreach($fuelStations as $fs)
                        <option value="{{ $fs->id }}">{{ $fs->name }}</option>
                        @endforeach
                    </select>
                    @error('fuel_station_id') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tarjeta Combustible</label>
                    <select wire:model="fuel_card_id" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        <option value="">Seleccionar...</option>
                        @foreach($fuelCards as $fc)
                        <option value="{{ $fc->id }}">{{ $fc->card_number }} · {{ $fc->holder_name }}</option>
                        @endforeach
                    </select>
                    @error('fuel_card_id') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Status</label>
                    <select wire:model="status" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        @foreach($fuelStatuses as $fs)
                        <option value="{{ $fs->value }}">{{ $fs->label }}</option>
                        @endforeach
                    </select>
                    @error('status') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Ticket (foto)</label>
                    <input wire:model="ticket_photo" type="file" accept="image/*" class="w-full text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-pink-50 file:text-[#E72085] hover:file:bg-pink-100">
                    @error('ticket_photo') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                    @if($ticket_photo)
                        <img src="{{ $ticket_photo->temporaryUrl() }}" class="h-24 rounded-lg object-cover mt-2 shadow-sm">
                    @elseif($existing_ticket_photo)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($existing_ticket_photo) }}" class="h-24 rounded-lg object-cover mt-2 shadow-sm">
                    @endif
                </div>
                <div class="md:col-span-2 flex gap-2 items-end">
                    <button type="submit" class="px-6 py-2 rounded-xl bg-[#E72085] text-white text-xs font-black uppercase tracking-wider hover:bg-[#d01c73] transition shadow-md shadow-[#E72085]/15">{{ $editId ? 'Actualizar' : 'Guardar' }}</button>
                    <button type="button" @click="show = false" class="px-6 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs font-black hover:bg-slate-50 transition">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

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
                    Carga #{{ $detailRecord?->id }} · {{ $detailRecord?->vehicle?->plate ?? '—' }}
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
                        <div><span class="text-gray-400">Fecha</span><p class="font-bold text-gray-800">{{ $detailRecord->date?->format('d/m/Y') ?? '—' }}</p></div>
                        <div><span class="text-gray-400">Folio</span><p class="font-bold text-gray-800">{{ $detailRecord->folio ?? '—' }}</p></div>
                        <div><span class="text-gray-400">Status</span>
                            <p class="font-bold {{ $fuelStatusStyles[$detailRecord->status][0] ?? 'bg-slate-100 text-slate-600' }} px-2 py-0.5 rounded inline-block mt-0.5">{{ $fuelStatusStyles[$detailRecord->status][1] ?? ucfirst($detailRecord->status) }}</p>
                        </div>
                        <div><span class="text-gray-400">Conductor</span><p class="font-bold text-gray-800">{{ $detailRecord->driver_name ?: ($detailRecord->driver?->name ?? '—') }}</p></div>
                        <div><span class="text-gray-400">Método Pago</span><p class="font-bold text-gray-800">{{ $detailRecord->payment_method?->value ?? $detailRecord->getRawOriginal('payment_method') }}</p></div>
                    </div>
                </div>

                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Combustible</h4>
                    <div class="grid grid-cols-3 gap-3 text-xs">
                        <div><span class="text-gray-400">Litros</span><p class="font-bold text-slate-700">{{ number_format($detailRecord->liters, 1) }} L</p></div>
                        <div><span class="text-gray-400">Monto</span><p class="font-bold text-[#E72085]">${{ number_format($detailRecord->amount, 2) }}</p></div>
                        <div><span class="text-gray-400">Precio/L</span><p class="font-bold text-gray-800">${{ number_format($detailRecord->price_per_liter, 2) }}</p></div>
                        <div><span class="text-gray-400">Odómetro</span><p class="font-bold text-gray-800">{{ number_format($detailRecord->odometer) }} km</p></div>
                    </div>
                </div>

                @if($detailRecord->fuelStation)
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Gasolinera</h4>
                    <p class="text-xs font-bold text-gray-800">{{ $detailRecord->fuelStation->name }}</p>
                    @if($detailRecord->fuelStation->address)
                    <p class="text-[10px] text-gray-400">{{ $detailRecord->fuelStation->address }}</p>
                    @endif
                </div>
                @endif

                @if($detailRecord->fuelCard)
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Tarjeta de Combustible</h4>
                    <p class="text-xs font-bold text-gray-800">{{ $detailRecord->fuelCard->card_number }} · {{ $detailRecord->fuelCard->holder_name }}</p>
                </div>
                @endif

                @if($detailRecord->route)
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Ruta</h4>
                    <p class="text-xs font-bold text-gray-800">{{ $detailRecord->route->name ?? 'Ruta #'.$detailRecord->route_id }}</p>
                </div>
                @endif

                @if($detailRecord->ticket_photo)
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Ticket</h4>
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($detailRecord->ticket_photo) }}" alt="Ticket"
                         class="w-full max-h-56 object-cover rounded-lg cursor-pointer"
                         @click="lightbox = true; lightboxSrc = $event.currentTarget.src">
                </div>
                @endif

                @if($detailRecord->rejection_reason)
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Motivo de Rechazo</h4>
                    <p class="text-xs text-red-600 italic">&ldquo;{{ $detailRecord->rejection_reason }}&rdquo;</p>
                </div>
                @endif

                @if($detailRecord->status === 'pendiente')
                <div class="flex gap-2 pt-2">
                    <button wire:click="approve({{ $detailRecord->id }})" class="flex-1 px-4 py-2 rounded-xl bg-emerald-500 text-white text-xs font-black uppercase tracking-wider hover:bg-emerald-600 transition">Aprobar</button>
                    <button wire:click="confirmReject({{ $detailRecord->id }})" class="flex-1 px-4 py-2 rounded-xl bg-red-500 text-white text-xs font-black uppercase tracking-wider hover:bg-red-600 transition">Rechazar</button>
                </div>
                @endif
            </div>
            @endif

            <div class="mt-6 pt-4 border-t border-gray-100">
                <button type="button" @click="show = false" class="w-full px-6 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs font-black hover:bg-slate-50 transition">Cerrar</button>
            </div>
        </div>
    </div>

    {{-- CONSUMO POR UNIDAD --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-black text-gray-800">Consumo de Combustible por Unidad (Semanal)</h3>
            @if($unitTotal > 0)
            <p class="text-[10px] text-slate-400 font-bold">Mostrando {{ $unitFirstItem }}-{{ $unitLastItem }} de {{ $unitTotal }}</p>
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
            @forelse($fuelStats as $fs)
            @php
                $barValue = $fs['real_consumed'] > 0 ? $fs['real_consumed'] : $fs['consumed'];
                $barRatio = $fs['authorized'] > 0 ? ($barValue / $fs['authorized']) * 100 : 0;
                $hasReal = $fs['real_consumed'] > 0;
            @endphp
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-mono text-sm font-black tabular-nums text-slate-800">{{ $fs['plate'] }}</span>
                    @if($barValue > 0 && $fs['authorized'] > 0)
                    <span class="text-[10px] font-extrabold {{ $barRatio > 100 ? 'text-red-500' : ($barRatio >= 80 ? 'text-amber-500' : 'text-emerald-500') }}">
                        {{ number_format($barRatio, 1) }}%
                    </span>
                    @endif
                </div>
                <div class="h-3 rounded-full bg-slate-100 overflow-hidden mb-3">
                    <div class="h-full transition-all rounded-full {{ $hasReal ? 'bg-purple-400' : 'bg-slate-300' }}" style="width: {{ min($barRatio, 100) }}%"></div>
                </div>
                <div class="grid grid-cols-3 gap-2 text-center mb-3">
                    <div>
                        <p class="font-mono text-sm font-black tabular-nums text-slate-800">{{ $hasReal ? number_format($fs['real_consumed'], 1) : '—' }}</p>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-0.5">consumido</p>
                    </div>
                    <div>
                        <p class="font-mono text-sm font-black tabular-nums text-slate-800">{{ number_format($fs['authorized'], 1) }}</p>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-0.5">autorizado</p>
                    </div>
                    <div>
                        <p class="font-mono text-sm font-black tabular-nums text-slate-800">{{ number_format($barValue, 1) }}</p>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-0.5">comprado</p>
                    </div>
                </div>
                @if(!$hasReal && $fs['consumed'] > 0)
                <p class="text-[10px] text-slate-400 mb-2">* Basado en litros comprados</p>
                @endif
                <div class="flex items-center gap-3 mb-2 text-[10px] text-slate-400">
                    @if($fs['total_distance'] > 0)
                    <span>{{ number_format($fs['total_distance']) }} km</span>
                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                    @endif
                    @if($hasReal && $fs['real_km_per_liter'])
                    <span class="font-bold text-purple-500">{{ number_format($fs['real_km_per_liter'], 1) }} km/L R</span>
                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                    @elseif(!$hasReal && $fs['km_per_liter'])
                    <span class="font-bold text-slate-400">{{ number_format($fs['km_per_liter'], 1) }} km/L</span>
                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                    @endif
                    @if($fs['cost_per_km'])
                    <span>${{ number_format($fs['cost_per_km'], 2) }}/km</span>
                    @endif
                </div>
                <div class="flex items-center justify-between border-t border-slate-100 pt-2">
                    <div x-data="{ editing: false, val: '{{ $fs['authorized'] }}' }" class="flex items-center gap-1.5">
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Autorizado:</span>
                        <template x-if="!editing">
                            <span class="text-[10px] font-bold text-slate-600">{{ number_format($fs['authorized'], 1) }} L</span>
                        </template>
                        <template x-if="editing">
                            <input type="number" step="0.1" x-model="val" class="w-20 bg-white border border-gray-200 rounded-lg p-1 text-[10px] font-bold text-slate-600 outline-none focus:border-[#E72085]">
                        </template>
                        <template x-if="!editing">
                            <button @click="editing = true; val = '{{ $fs['authorized'] }}'" class="text-[#E72085] hover:text-[#d01c73]">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                        </template>
                        <template x-if="editing">
                            <div class="flex items-center gap-1">
                                <button @click="editing = false; $wire.updateAuthorizedFuel({{ $fs['id'] }}, val)" class="text-emerald-500 hover:text-emerald-600">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                                <button @click="editing = false" class="text-red-400 hover:text-red-500">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    @if($fs['suggested'] > 0 && $fs['authorized'] != $fs['suggested'])
                    <button wire:click="updateAuthorizedFuel({{ $fs['id'] }}, {{ $fs['suggested'] }})" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700" title="Usar sugerencia automática">
                        Sugerido: {{ number_format($fs['suggested'], 1) }}L
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="md:col-span-2 xl:col-span-3 text-center py-12 text-gray-400 text-sm font-bold">Sin vehículos registrados</div>
            @endforelse
        </div>
        @if($unitLastPage > 1)
        <div class="mt-4 flex items-center justify-between">
            <p class="text-[10px] text-slate-400 font-bold">Mostrando {{ $unitFirstItem }}-{{ $unitLastItem }} de {{ $unitTotal }}</p>
            <div class="flex gap-2">
                <button wire:click="previousUnitPage" @disabled($unitPage <= 1)
                    class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed">Anterior</button>
                <button wire:click="nextUnitPage" @disabled($unitPage >= $unitLastPage)
                    class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed">Siguiente</button>
            </div>
        </div>
        @endif
    </div>

    {{-- HISTORIAL DE TICKETS --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-6">
        <div class="flex flex-col lg:flex-row gap-3 items-start lg:items-center justify-between mb-4">
            <h3 class="text-sm font-black text-gray-800">Historial de Tickets</h3>
            <div class="flex flex-wrap gap-2 items-center">
                <input wire:model.live.debounce.300ms="filterSearch" type="text" placeholder="Buscar (folio, operador, placa...)"
                       class="px-3 py-2 rounded-lg border border-slate-200 text-xs font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] w-56">
                <select wire:model.live="filterVehicleId" class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                    <option value="">Vehículo: Todos</option>
                    @foreach($vehicles as $v)
                    <option value="{{ $v->id }}">{{ $v->plate }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterStatus" class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                    <option value="">Status: Todos</option>
                    @foreach($fuelStatuses as $fs)
                    <option value="{{ $fs->value }}">{{ $fs->label }}</option>
                    @endforeach
                </select>
                @if($filterSearch || $filterVehicleId || $filterStatus)
                <button wire:click="clearFilters" class="px-3 py-2 rounded-lg text-[11px] font-bold text-slate-500 hover:bg-slate-100 transition">Limpiar</button>
                @endif
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                        <th class="text-left py-2.5 px-2">#</th>
                        <th class="text-left py-2.5 px-2">Fecha</th>
                        <th class="text-left py-2.5 px-2">Vehículo</th>
                        <th class="text-left py-2.5 px-2">Operador</th>
                        <th class="text-right py-2.5 px-2">Litros</th>
                        <th class="text-right py-2.5 px-2">Monto</th>
                        <th class="text-left py-2.5 px-2">Método</th>
                        <th class="text-center py-2.5 px-2">Status</th>
                        <th class="text-center py-2.5 px-2">Ticket</th>
                        <th class="text-right py-2.5 px-2">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($refuels as $r)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-2.5 px-2 font-mono text-[10px] text-blue-600 font-bold">#{{ $r->id }}</td>
                        <td class="py-2.5 px-2 whitespace-nowrap text-slate-600">{{ $r->date?->format('d/m/Y') ?? '—' }}</td>
                        <td class="py-2.5 px-2 font-semibold text-slate-700">{{ $r->vehicle?->plate ?? '—' }}</td>
                        <td class="py-2.5 px-2 text-slate-600">{{ $r->driver_name ?: 'Operador' }}</td>
                        <td class="py-2.5 px-2 text-right font-bold text-slate-700">{{ number_format($r->liters, 1) }}</td>
                        <td class="py-2.5 px-2 text-right font-semibold text-slate-700">${{ number_format($r->amount, 2) }}</td>
                        <td class="py-2.5 px-2 text-slate-600">{{ $r->payment_method?->value ?? $r->getRawOriginal('payment_method') }}</td>
                        <td class="py-2.5 px-2 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $fuelStatusStyles[$r->status][0] ?? 'bg-slate-100 text-slate-600' }}">{{ $fuelStatusStyles[$r->status][1] ?? $r->status }}</span>
                        </td>
                        <td class="py-2.5 px-2 text-center">
                            @if($r->ticket_photo)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($r->ticket_photo) }}" alt="Ticket"
                                 class="w-10 h-10 rounded-lg object-cover cursor-pointer shadow-xs inline-block"
                                 @click="lightbox = true; lightboxSrc = $event.currentTarget.src">
                            @else
                            <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="py-2.5 px-2">
                            <div class="flex items-center justify-end gap-1">
                                <button wire:click="showExpediente({{ $r->id }})" class="p-1 text-emerald-600 hover:bg-emerald-50 rounded transition" title="Ver detalle">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <button wire:click="edit({{ $r->id }})" class="p-1 text-[#008FD3] hover:bg-blue-50 rounded transition" title="Editar">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @if($r->status === 'pendiente')
                                <button wire:click="approve({{ $r->id }})" class="p-1 text-emerald-500 hover:bg-emerald-50 rounded transition" title="Aprobar">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </button>
                                <button wire:click="confirmReject({{ $r->id }})" class="p-1 text-red-500 hover:bg-red-50 rounded transition" title="Rechazar">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                                @endif
                                <button wire:click="confirmDelete({{ $r->id }})" class="p-1 text-red-500 hover:bg-red-50 rounded transition" title="Eliminar">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center py-12 text-gray-400 text-sm font-bold">Sin registros en esta semana</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($refuels->hasPages())
        <div class="mt-4">
            {{ $refuels->links() }}
        </div>
        @endif
    </div>

    <div x-data="{ show: @entangle('showRouteDetail') }"
         x-show="show"
         x-cloak
         x-transition.opacity.duration.200ms
         @keydown.escape.window="show = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="show = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200 w-[720px] max-h-[90vh] overflow-y-auto p-6"
             @click.outside="show = false">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-slate-800">Detalle de Ruta</h3>
                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-600 transition p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if($selectedRouteDetail)
            <div class="space-y-5">
                <div class="grid grid-cols-3 gap-3 text-xs bg-slate-50 rounded-xl p-4">
                    <div><span class="text-[10px] font-extrabold text-slate-400 uppercase">Cliente</span><p class="font-bold text-slate-800 mt-0.5">{{ $selectedRouteDetail['client'] }}</p></div>
                    <div><span class="text-[10px] font-extrabold text-slate-400 uppercase">Unidad</span><p class="font-bold text-slate-800 mt-0.5">{{ $selectedRouteDetail['vehicle_plate'] }}</p></div>
                    <div><span class="text-[10px] font-extrabold text-slate-400 uppercase">Conductor</span><p class="font-bold text-slate-800 mt-0.5">{{ $selectedRouteDetail['driver'] }}</p></div>
                    <div><span class="text-[10px] font-extrabold text-slate-400 uppercase">Fecha</span><p class="font-bold text-slate-800 mt-0.5">{{ $selectedRouteDetail['date'] }}</p></div>
                    <div><span class="text-[10px] font-extrabold text-slate-400 uppercase">Km</span><p class="font-bold text-slate-800 mt-0.5">{{ number_format($selectedRouteDetail['distance']) }}</p></div>
                    <div><span class="text-[10px] font-extrabold text-slate-400 uppercase">km/L</span>
                        <p class="font-bold mt-0.5 {{ $selectedRouteDetail['km_per_liter'] && $selectedRouteDetail['km_per_liter'] >= 8 ? 'text-emerald-600' : ($selectedRouteDetail['km_per_liter'] && $selectedRouteDetail['km_per_liter'] >= 5 ? 'text-amber-600' : 'text-red-500') }}">
                            {{ $selectedRouteDetail['km_per_liter'] ?? '—' }}
                        </p>
                    </div>
                </div>

                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-3">Timeline de Nivel de Combustible</h4>
                    @php
                        $levelColors = ['1/4' => 'bg-red-400', '1/2' => 'bg-amber-400', '3/4' => 'bg-lime-400', 'Lleno' => 'bg-emerald-400'];
                        $tc = $selectedRouteDetail['tank_capacity'];
                        $levelLiters = $tc ? [
                            '1/4' => '1/4 (~' . round($tc * 0.25) . 'L)',
                            '1/2' => '1/2 (~' . round($tc * 0.5) . 'L)',
                            '3/4' => '3/4 (~' . round($tc * 0.75) . 'L)',
                            'Lleno' => 'Lleno (~' . round($tc) . 'L)',
                        ] : [];
                    @endphp
                    <div class="space-y-2">
                        @foreach($selectedRouteDetail['steps'] as $si => $st)
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black shrink-0 {{ $st['has_photo'] ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }}">
                                @if($st['has_photo'])
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @else
                                {{ $si + 1 }}
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-700">{{ $st['label'] }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $st['time'] }}</span>
                                </div>
                                <div class="flex items-center gap-2 mt-0.5">
                                    @if($st['fuel_level'])
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $levelColors[$st['fuel_level']] ?? 'bg-slate-200' }} text-white">{{ $levelLiters[$st['fuel_level']] ?? $st['fuel_level'] }}</span>
                                    @else
                                    <span class="text-[10px] text-slate-300">Sin nivel</span>
                                    @endif
                                    @if($st['odometer'])
                                    <span class="text-[10px] text-slate-400">{{ number_format($st['odometer']) }} km</span>
                                    @endif
                                </div>
                            </div>
                            @if($si < count($selectedRouteDetail['steps']) - 1)
                            <div class="w-4 text-center text-slate-300">
                                <svg class="w-3 h-3 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                @if(count($selectedRouteDetail['segments']) > 0)
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-3">Rendimiento por Tramo</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-[10px]">
                            <thead>
                                <tr class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                    <th class="text-left pb-1.5 pr-2">Tramo</th>
                                    <th class="text-right pb-1.5 pr-2">Km</th>
                                    <th class="text-right pb-1.5 pr-2">Δ Combustible</th>
                                    <th class="text-right pb-1.5 pr-2">km/L</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($selectedRouteDetail['segments'] as $seg)
                                <tr>
                                    <td class="py-1 pr-2 text-slate-600">{{ $seg['from'] }} → {{ $seg['to'] }}</td>
                                    <td class="py-1 pr-2 text-right font-bold text-slate-700">{{ $seg['distance'] ? number_format($seg['distance']) : '—' }}</td>
                                    <td class="py-1 pr-2 text-right">
                                        @if($seg['fuel_delta'] !== null)
                                        <span class="font-bold {{ $seg['fuel_delta'] > 0 ? 'text-red-500' : 'text-emerald-500' }}">
                                            {{ $seg['fuel_delta'] > 0 ? '-' : '+' }}{{ number_format(abs($seg['fuel_delta']), 1) }}L
                                        </span>
                                        @else
                                        <span class="text-slate-300">—</span>
                                        @endif
                                    </td>
                                    <td class="py-1 pr-2 text-right font-bold {{ $seg['km_per_liter'] && $seg['km_per_liter'] >= 8 ? 'text-emerald-600' : ($seg['km_per_liter'] && $seg['km_per_liter'] >= 5 ? 'text-amber-600' : 'text-red-400') }}">
                                        {{ $seg['km_per_liter'] ? number_format($seg['km_per_liter'], 1) : '—' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                @if($selectedRouteDetail['reconciliation'])
                <div class="rounded-xl p-4 bg-slate-50 border border-slate-200">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-xs font-black text-slate-700">Reconciliación de Combustible</span>
                    </div>
                    <div class="grid grid-cols-5 gap-2 text-[10px]">
                        <div><span class="text-slate-500">Inicial</span><p class="font-bold text-slate-700">{{ $selectedRouteDetail['reconciliation']['initial_liters'] }}L</p></div>
                        <div><span class="text-slate-500">+ Carga</span><p class="font-bold text-blue-600">{{ $selectedRouteDetail['reconciliation']['refueled_liters'] }}L</p></div>
                        <div><span class="text-slate-500">= Esperado</span><p class="font-bold text-slate-700">{{ $selectedRouteDetail['reconciliation']['expected_liters'] }}L</p></div>
                        <div><span class="text-slate-500">- Consumo</span><p class="font-bold text-amber-600">{{ $selectedRouteDetail['reconciliation']['consumed_liters'] }}L</p></div>
                        <div><span class="text-slate-500">Final Real</span><p class="font-bold text-slate-700">{{ $selectedRouteDetail['reconciliation']['final_liters'] }}L</p></div>
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

    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-black text-gray-800">Consumo por Ruta</h3>
            @if($routeTotal > 0)
            <p class="text-[10px] text-slate-400 font-bold">Mostrando {{ $routeFirstItem }}-{{ $routeLastItem }} de {{ $routeTotal }}</p>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="text-left pb-2 pr-3">Cliente</th>
                        <th class="text-left pb-2 pr-3">Fecha</th>
                        <th class="text-left pb-2 pr-3">Unidad</th>
                        <th class="text-right pb-2 pr-3">Km</th>
                        <th class="text-right pb-2 pr-3">Compra</th>
                        <th class="text-right pb-2 pr-3">km/L</th>
                        <th class="text-right pb-2 pr-3">C. Real</th>
                        <th class="text-right pb-2 pr-3">km/L R</th>
                        <th class="text-center pb-2 pr-3">Nivel Ini</th>
                        <th class="text-center pb-2 pr-3">Nivel Fin</th>
                        <th class="text-right pb-2 pr-3">Gasto</th>
                        <th class="text-right pb-2 pr-3">$/km</th>
                        <th class="text-right pb-2 pr-3">Cargas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($routeConsumptionPage as $rc)
                    <tr wire:click="showRouteDetail({{ $rc['id'] }})" class="hover:bg-slate-50/50 transition cursor-pointer" title="Ver detalle de ruta">
                        <td class="py-2 pr-3 font-bold text-slate-800">{{ $rc['client'] }}</td>
                        <td class="py-2 pr-3 text-slate-500">{{ $rc['date'] }}</td>
                        <td class="py-2 pr-3 text-slate-600">{{ $rc['vehicle_plate'] }}</td>
                        <td class="py-2 pr-3 text-right font-bold text-slate-700">{{ number_format($rc['distance']) }}</td>
                        <td class="py-2 pr-3 text-right font-bold text-slate-700">{{ number_format($rc['liters'], 1) }}</td>
                        <td class="py-2 pr-3 text-right">
                            @if($rc['km_per_liter'])
                            <span class="font-bold {{ $rc['km_per_liter'] >= 8 ? 'text-emerald-600' : ($rc['km_per_liter'] >= 5 ? 'text-amber-600' : 'text-red-500') }}">
                                {{ number_format($rc['km_per_liter'], 1) }}
                            </span>
                            @else
                            <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="py-2 pr-3 text-right font-bold {{ $rc['real_consumed'] ? 'text-purple-600' : 'text-slate-300' }}">{{ $rc['real_consumed'] ? number_format($rc['real_consumed'], 1) : '—' }}</td>
                        <td class="py-2 pr-3 text-right">
                            @if($rc['real_km_per_liter'])
                            <span class="font-bold text-purple-600">{{ number_format($rc['real_km_per_liter'], 1) }}</span>
                            @else
                            <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="py-2 pr-3 text-center">
                            <span class="text-[10px] font-bold {{ $rc['fuel_initial'] ? 'text-slate-700' : 'text-slate-300' }}">{{ $rc['fuel_initial'] ?? '—' }}</span>
                        </td>
                        <td class="py-2 pr-3 text-center">
                            <span class="text-[10px] font-bold {{ $rc['fuel_final'] ? 'text-slate-700' : 'text-slate-300' }}">{{ $rc['fuel_final'] ?? '—' }}</span>
                        </td>
                        <td class="py-2 pr-3 text-right font-bold text-slate-700">${{ number_format($rc['amount'], 2) }}</td>
                        <td class="py-2 pr-3 text-right">
                            @if($rc['cost_per_km'])
                            <span class="font-bold text-slate-600">${{ number_format($rc['cost_per_km'], 2) }}</span>
                            @else
                            <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="py-2 pr-3 text-right">
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600">{{ $rc['refuel_count'] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="13" class="text-center py-12 text-gray-400 text-sm font-bold">Sin rutas con cargas en esta semana</td></tr>
                    @endforelse
                </tbody>
                @if($routeTotal > 0)
                <tfoot>
                    @php
                        $totalDistance = $routeConsumption->sum('distance');
                        $totalLiters = $routeConsumption->sum('liters');
                        $totalAmount = $routeConsumption->sum('amount');
                        $totalReal = $routeConsumption->sum('real_consumed');
                    @endphp
                    <tr class="border-t-2 border-slate-200">
                        <td colspan="3" class="pt-2 pr-3 font-extrabold text-slate-800 text-[10px]">Totales</td>
                        <td class="pt-2 pr-3 text-right font-extrabold text-slate-800 text-[10px]">{{ number_format($totalDistance) }}</td>
                        <td class="pt-2 pr-3 text-right font-extrabold text-slate-800 text-[10px]">{{ number_format($totalLiters, 1) }}</td>
                        <td class="pt-2 pr-3 text-right font-extrabold {{ $totalDistance > 0 && $totalLiters > 0 ? 'text-emerald-600' : 'text-slate-400' }} text-[10px]">
                            {{ $totalDistance > 0 && $totalLiters > 0 ? number_format($totalDistance / $totalLiters, 1) : '—' }}
                        </td>
                        <td class="pt-2 pr-3 text-right font-extrabold {{ $totalReal > 0 ? 'text-purple-600' : 'text-slate-400' }} text-[10px]">{{ $totalReal > 0 ? number_format($totalReal, 1) : '—' }}</td>
                        <td class="pt-2 pr-3 text-right font-extrabold {{ $totalDistance > 0 && $totalReal > 0 ? 'text-purple-600' : 'text-slate-400' }} text-[10px]">
                            {{ $totalDistance > 0 && $totalReal > 0 ? number_format($totalDistance / $totalReal, 1) : '—' }}
                        </td>
                        <td colspan="2"></td>
                        <td class="pt-2 pr-3 text-right font-extrabold text-slate-800 text-[10px]">${{ number_format($totalAmount, 2) }}</td>
                        <td class="pt-2 pr-3 text-right font-extrabold text-slate-800 text-[10px]">
                            {{ $totalDistance > 0 && $totalAmount > 0 ? '$' . number_format($totalAmount / $totalDistance, 2) : '—' }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        @if($routeLastPage > 1)
        <div class="mt-4 flex items-center justify-between">
            <p class="text-[10px] text-slate-400 font-bold">Mostrando {{ $routeFirstItem }}-{{ $routeLastItem }} de {{ $routeTotal }}</p>
            <div class="flex gap-2">
                <button wire:click="previousRoutePage" @disabled($routePage <= 1)
                    class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed">Anterior</button>
                <button wire:click="nextRoutePage" @disabled($routePage >= $routeLastPage)
                    class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed">Siguiente</button>
            </div>
        </div>
        @endif
    </div>
</div>
