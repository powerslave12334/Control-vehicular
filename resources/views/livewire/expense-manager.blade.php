
<div class="bg-white rounded-3xl shadow-xs border border-slate-200 p-6 md:p-8 space-y-6">
    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-black text-slate-800">Control de Gastos</h1>
            <p class="text-xs text-slate-400 font-semibold mt-0.5">Registro y aprobación de gastos operativos.</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="createCard" class="border border-[#E72085] text-[#E72085] text-xs font-black uppercase tracking-wider rounded-xl px-4 py-2 flex items-center gap-2 hover:bg-[#E72085]/5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Nueva Tarjeta
            </button>
            <button wire:click="create" class="bg-[#E72085] text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-md shadow-[#E72085]/15 px-4 py-2 flex items-center gap-2 hover:bg-[#d01c73] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Registrar Gasto
            </button>
        </div>
    </div>

    {{-- CARDS TABLE --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-6">
        <div class="flex flex-col lg:flex-row gap-3 items-start lg:items-center justify-between mb-4">
            <h3 class="text-sm font-black text-gray-800">Tarjetas Registradas</h3>
            <div class="flex flex-wrap gap-2 items-center">
                <input wire:model.live.debounce.300ms="cardFilterSearch" type="text"
                    placeholder="Buscar (placa, marca, nº tarjeta...)"
                    class="px-3 py-2 rounded-lg border border-slate-200 text-xs font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] w-56">
                <select wire:model.live="cardScope"
                    class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                    <option value="cards">Solo con tarjeta</option>
                    <option value="all">Todos los vehículos</option>
                </select>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                        <th class="text-left py-3 px-2">Vehículo</th>
                        <th class="text-left py-3 px-2">Nº Tarjeta</th>
                        <th class="text-right py-3 px-2">Autorizado</th>
                        <th class="text-right py-3 px-2">Gastado (Mes)</th>
                        <th class="text-right py-3 px-2">Disponible</th>
                        <th class="text-center py-3 px-2">Uso</th>
                        <th class="text-right py-3 px-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cardStats as $stat)
                    <tr class="border-b border-gray-50 hover:bg-slate-50 transition">
                        <td class="py-3 px-2 font-semibold text-slate-700">
                            {{ $stat->vehicle->plate }}
                            <span class="text-[10px] text-gray-400 font-normal">· {{ $stat->vehicle->brand }} {{ $stat->vehicle->model }}</span>
                        </td>
                        <td class="py-3 px-2 font-mono text-slate-600">
                            @if($stat->card_number)
                            <span class="text-[11px]">•••• {{ substr($stat->card_number, -4) }}</span>
                            @else
                            <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="py-3 px-2 text-right font-bold text-slate-800">${{ number_format($stat->authorized, 2) }}</td>
                        <td class="py-3 px-2 text-right font-semibold text-slate-700">${{ number_format($stat->spent, 2) }}</td>
                        <td class="py-3 px-2 text-right font-bold {{ $stat->remaining > 0 ? 'text-emerald-600' : 'text-red-500' }}">${{ number_format($stat->remaining, 2) }}</td>
                        <td class="py-3 px-2 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <div class="w-16 bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-full rounded-full {{ $stat->percent >= 90 ? 'bg-red-500' : ($stat->percent >= 70 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                         style="width: {{ min(100, $stat->percent) }}%"></div>
                                </div>
                                <span class="text-[10px] text-gray-400 font-semibold">{{ $stat->percent }}%</span>
                            </div>
                        </td>
                        <td class="py-3 px-2 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button wire:click="editCard({{ $stat->vehicle->id }})" class="p-1.5 text-blue-500 hover:bg-blue-50 rounded transition" title="Editar tarjeta">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="confirmDeleteCard({{ $stat->vehicle->id }})" class="p-1.5 text-red-500 hover:bg-red-50 rounded transition" title="Eliminar tarjeta">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-gray-400 text-sm font-bold">Sin tarjetas registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($cardLastPage > 1)
        <div class="mt-4 flex items-center justify-between">
            <p class="text-[10px] text-slate-400 font-bold">Mostrando {{ $cardFirstItem }}-{{ $cardLastItem }} de {{ $cardTotal }}</p>
            <div class="flex gap-2">
                <button wire:click="previousCardPage" @disabled($cardPage <= 1)
                    class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed">Anterior</button>
                <button wire:click="nextCardPage" @disabled($cardPage >= $cardLastPage)
                    class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed">Siguiente</button>
            </div>
        </div>
        @endif
    </div>

    {{-- CARD FORM MODAL --}}
    <div x-data="{ show: @entangle('showCardForm') }"
         x-show="show"
         x-cloak
         x-transition.opacity.duration.200ms
         @keydown.escape.window="show = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="show = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200 w-[450px] max-h-[90vh] overflow-y-auto p-6"
             @click.outside="show = false">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-slate-800">Nueva Tarjeta de Gastos</h3>
                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-600 transition p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form wire:submit="saveCard" class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Vehículo</label>
                    <select wire:model="card_vehicle_id" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        <option value="">Seleccionar...</option>
                        @foreach($vehicles as $v)
                            <option value="{{ $v->id }}">{{ $v->plate }} · {{ $v->brand }} {{ $v->model }}</option>
                        @endforeach
                    </select>
                    @error('card_vehicle_id') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Número de Tarjeta</label>
                    <input wire:model="card_number" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none" placeholder="0000 0000 0000 0000">
                    @error('card_number') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Monto Autorizado ($)</label>
                    <input wire:model="card_authorized_amount" type="number" step="0.01" min="0" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('card_authorized_amount') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="px-6 py-2 rounded-xl bg-[#E72085] text-white text-xs font-black uppercase tracking-wider hover:bg-[#d01c73] transition shadow-md shadow-[#E72085]/15">Guardar Tarjeta</button>
                    <button type="button" @click="show = false" class="px-6 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs font-black hover:bg-slate-50 transition">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- CARD EDIT MODAL --}}
    <div x-data="{ show: @entangle('showCardEdit') }"
         x-show="show"
         x-cloak
         x-transition.opacity.duration.200ms
         @keydown.escape.window="show = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="show = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200 w-[450px] max-h-[90vh] overflow-y-auto p-6"
             @click.outside="show = false">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-slate-800">Editar Tarjeta de Gastos</h3>
                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-600 transition p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form wire:submit="updateCard" class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Vehículo</label>
                    <input value="{{ $editCardVehicleId ? \App\Domains\Vehicle\Models\Vehicle::find($editCardVehicleId)?->plate : '' }}" disabled class="w-full bg-slate-50 border border-gray-200 rounded-lg p-2 text-xs text-slate-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Número de Tarjeta</label>
                    <input wire:model="editCardNumber" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none" placeholder="0000 0000 0000 0000">
                    @error('editCardNumber') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Monto Autorizado ($)</label>
                    <input wire:model="editCardAuthorizedAmount" type="number" step="0.01" min="0" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('editCardAuthorizedAmount') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="px-6 py-2 rounded-xl bg-[#E72085] text-white text-xs font-black uppercase tracking-wider hover:bg-[#d01c73] transition shadow-md shadow-[#E72085]/15">Actualizar Tarjeta</button>
                    <button type="button" @click="show = false" class="px-6 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs font-black hover:bg-slate-50 transition">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- EXPENSE FORM MODAL --}}
    <div x-data="{ show: @entangle('showForm') }"
         x-show="show"
         x-cloak
         x-transition.opacity.duration.200ms
         @keydown.escape.window="show = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="show = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200 w-[500px] max-h-[90vh] overflow-y-auto p-6"
             @click.outside="show = false">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-slate-800">{{ $editId ? 'Editar Gasto' : 'Registrar Gasto' }}</h3>
                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-600 transition p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Vehículo con Tarjeta</label>
                    <select wire:model.live="vehicle_id" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        <option value="">Seleccionar...</option>
                        @foreach($vehiclesWithCards as $v)
                            <option value="{{ $v->id }}">{{ $v->plate }} · {{ $v->brand }} {{ $v->model }} @if($v->expense_card_number)(•••• {{ substr($v->expense_card_number, -4) }})@endif</option>
                        @endforeach
                    </select>
                    @error('vehicle_id') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>

                @if($selectedVehicleCard)
                <div class="md:col-span-2 bg-slate-50 border border-slate-200 rounded-xl p-3">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tarjeta •••• {{ substr($selectedVehicleCard->card_number, -4) }}</span>
                        <span class="text-[10px] font-bold {{ $selectedVehicleCard->remaining > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                            ${{ number_format($selectedVehicleCard->remaining, 2) }} disponibles
                        </span>
                    </div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-500">Gastado este mes</span>
                        <span class="font-bold text-slate-700">${{ number_format($selectedVehicleCard->spent, 2) }} / ${{ number_format($selectedVehicleCard->authorized, 2) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                        <div class="h-full rounded-full {{ $selectedVehicleCard->percent >= 90 ? 'bg-red-500' : ($selectedVehicleCard->percent >= 70 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                             style="width: {{ min(100, $selectedVehicleCard->percent) }}%"></div>
                    </div>
                </div>
                @endif

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fecha</label>
                    <input wire:model="date" type="date" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('date') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo de Gasto</label>
                    <input wire:model="type" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none" placeholder="Ej: Caseta, Estacionamiento...">
                    @error('type') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Monto ($)</label>
                    <input wire:model="amount" type="number" step="0.01" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('amount') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Operador</label>
                    <select wire:model="operator_id" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        <option value="">Seleccionar...</option>
                        @foreach($operators as $op)
                            <option value="{{ $op->id }}">{{ $op->name }}</option>
                        @endforeach
                    </select>
                    @error('operator_id') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Folio</label>
                    <input wire:model="folio" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('folio') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Proveedor</label>
                    <input wire:model="provider_name" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('provider_name') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Descripción</label>
                    <textarea wire:model="description" rows="2" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none resize-none"></textarea>
                    @error('description') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="px-6 py-2 rounded-xl bg-[#E72085] text-white text-xs font-black uppercase tracking-wider hover:bg-[#d01c73] transition shadow-md shadow-[#E72085]/15">Guardar</button>
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
                    Gasto #{{ $detailRecord?->id }} · {{ $detailRecord?->vehicle?->plate ?? '—' }}
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
                        <div><span class="text-gray-400">Tipo</span><p class="font-bold text-gray-800">{{ $detailRecord->type }}</p></div>
                        <div><span class="text-gray-400">Monto</span><p class="font-bold text-[#E72085]">${{ number_format($detailRecord->amount, 2) }}</p></div>
                        <div><span class="text-gray-400">Folio</span><p class="font-bold text-gray-800">{{ $detailRecord->folio ?? '—' }}</p></div>
                        <div><span class="text-gray-400">Proveedor</span><p class="font-bold text-gray-800">{{ $detailRecord->provider_name ?? '—' }}</p></div>
                        <div><span class="text-gray-400">Operador</span><p class="font-bold text-gray-800">{{ $detailRecord->operator?->name ?? '—' }}</p></div>
                        <div><span class="text-gray-400">Status</span>
                            <p class="font-bold {{ $detailRecord->status->value === 'aprobado' ? 'text-emerald-600' : ($detailRecord->status->value === 'rechazado' ? 'text-red-600' : 'text-amber-600') }}">
                                {{ ucfirst($detailRecord->status->value) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Descripción</h4>
                    <p class="text-xs text-gray-700">{{ $detailRecord->description }}</p>
                </div>

                @if($detailRecord->approver)
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Aprobación</h4>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div><span class="text-gray-400">Aprobado por</span><p class="font-bold text-gray-800">{{ $detailRecord->approver->name }}</p></div>
                    </div>
                </div>
                @endif

                @if($detailRecord->rejection_reason)
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Motivo de Rechazo</h4>
                    <p class="text-xs text-red-600">{{ $detailRecord->rejection_reason }}</p>
                </div>
                @endif

                @if($detailRecord->status->value === 'pendiente')
                <div class="flex gap-2 pt-2">
                    <button wire:click="approve({{ $detailRecord->id }})" class="px-4 py-2 rounded-xl bg-emerald-500 text-white text-xs font-black hover:bg-emerald-600 transition">
                        Aprobar
                    </button>
                    <button wire:click="confirmReject({{ $detailRecord->id }})" class="px-4 py-2 rounded-xl bg-red-500 text-white text-xs font-black hover:bg-red-600 transition">
                        Rechazar
                    </button>
                </div>
                @endif
            </div>
            @endif

            <div class="mt-6 pt-4 border-t border-gray-100">
                <button type="button" @click="show = false" class="w-full px-6 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs font-black hover:bg-slate-50 transition">Cerrar</button>
            </div>
        </div>
    </div>

    {{-- EXPENSES LIST --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-6">
        <div class="flex flex-col lg:flex-row gap-3 items-start lg:items-center justify-between mb-4">
            <h3 class="text-sm font-black text-gray-800">Historial de Gastos</h3>
            <div class="flex flex-wrap gap-2 items-center">
                <input wire:model.live.debounce.300ms="filterSearch" type="text"
                    placeholder="Buscar (folio, tipo, proveedor, placa...)"
                    class="px-3 py-2 rounded-lg border border-slate-200 text-xs font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] w-56">
                <select wire:model.live="filterStatus"
                    class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                    <option value="">Estatus: Todos</option>
                    @foreach($expenseStatuses as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterVehicleId"
                    class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                    <option value="">Vehículo: Todos</option>
                    @foreach($vehicles as $v)
                    <option value="{{ $v->id }}">{{ $v->plate }}</option>
                    @endforeach
                </select>
                <input wire:model.live="filterDateFrom" type="date" title="Desde"
                    class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                <span class="text-[10px] font-bold text-gray-400">→</span>
                <input wire:model.live="filterDateTo" type="date" title="Hasta"
                    class="px-2 py-2 rounded-lg border border-slate-200 text-[11px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-white">
                @if($filterSearch || $filterStatus || $filterVehicleId || $filterDateFrom || $filterDateTo)
                <button wire:click="clearFilters"
                    class="px-3 py-2 rounded-lg text-[11px] font-bold text-slate-500 hover:bg-slate-100 transition">Limpiar</button>
                @endif
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                        <th class="text-left py-3 px-2">#</th>
                        <th class="text-left py-3 px-2">Fecha</th>
                        <th class="text-left py-3 px-2">Vehículo</th>
                        <th class="text-left py-3 px-2">Ruta</th>
                        <th class="text-left py-3 px-2">Tipo</th>
                        <th class="text-left py-3 px-2">Descripción</th>
                        <th class="text-right py-3 px-2">Monto</th>
                        <th class="text-center py-3 px-2">Estado</th>
                        <th class="text-right py-3 px-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $e)
                    <tr class="border-b border-gray-50 hover:bg-slate-50 transition">
                        <td class="py-3 px-2 font-mono text-[10px] text-blue-600 font-bold">#{{ $e->id }}</td>
                        <td class="py-3 px-2 font-semibold text-slate-700">{{ $e->date?->format('d/m/Y') }}</td>
                        <td class="py-3 px-2 font-semibold text-slate-700">{{ $e->vehicle?->plate ?? '—' }}</td>
                        <td class="py-3 px-2 text-slate-600">{{ $e->route ? '#'.$e->route->id.' '.$e->route->client_name : '—' }}</td>
                        <td class="py-3 px-2 text-slate-600">{{ $e->type }}</td>
                        <td class="py-3 px-2 text-slate-500 max-w-[200px] truncate">{{ $e->description }}</td>
                        <td class="py-3 px-2 text-right font-bold text-slate-800">${{ number_format($e->amount, 2) }}</td>
                        <td class="py-3 px-2 text-center">
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                                {{ $e->status->value === 'aprobado' ? 'bg-emerald-100 text-emerald-700' : ($e->status->value === 'rechazado' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                @switch($e->status->value)
                                    @case('aprobado') Aprobado @break
                                    @case('rechazado') Rechazado @break
                                    @default Pendiente
                                @endswitch
                            </span>
                        </td>
                        <td class="py-3 px-2 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button wire:click="showExpediente({{ $e->id }})" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded transition" title="Ver detalle">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                @if($e->status->value === 'pendiente')
                                <button wire:click="edit({{ $e->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="confirmDelete({{ $e->id }})" class="p-1.5 text-red-500 hover:bg-red-50 rounded transition" title="Eliminar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-12 text-gray-400 text-sm font-bold">Sin gastos registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $expenses->links() }}
        </div>
    </div>
</div>
