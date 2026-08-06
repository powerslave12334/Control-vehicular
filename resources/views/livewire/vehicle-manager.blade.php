
 
<div class="bg-white rounded-3xl shadow-xs border border-slate-200 p-6 md:p-8 space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-base font-bold text-gray-800">Expediente de Unidades Vehiculares ({{ $vehicles->total() }})</h3>
        <button wire:click="create" class="flex items-center gap-1.5 bg-[#E72085] hover:bg-[#E72085]/95 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-md shadow-[#E72085]/15">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Registrar Vehículo
        </button>
    </div>

    {{-- FILTERS --}}
    <div class="bg-white rounded-xl border border-slate-200 p-3">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2">
            <div class="col-span-2 md:col-span-1 lg:col-span-2">
                <input wire:model.live="search" placeholder="Buscar por marca, modelo, placas o VIN..." class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-[10px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085]">
            </div>
            <select wire:model.live="filterStatus" class="px-2 py-1.5 rounded-lg border border-slate-200 text-[10px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085]">
                <option value="">Todos los estatus</option>
                @foreach(\App\Domains\Vehicle\Enums\VehicleStatusEnum::cases() as $s)
                <option value="{{ $s->value }}">{{ $s->value }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterFuelType" class="px-2 py-1.5 rounded-lg border border-slate-200 text-[10px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085]">
                <option value="">Todos los combustibles</option>
                @foreach($fuelTypes as $ft)
                <option value="{{ $ft->value }}">{{ $ft->label }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterVehicleType" class="px-2 py-1.5 rounded-lg border border-slate-200 text-[10px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085]">
                <option value="">Todos los tipos</option>
                @foreach($vehicleTypes as $vt)
                <option value="{{ $vt->value }}">{{ $vt->label }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterGps" class="px-2 py-1.5 rounded-lg border border-slate-200 text-[10px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085]">
                <option value="">GPS: Todos</option>
                <option value="1">GPS Instalado</option>
                <option value="0">GPS No instalado</option>
            </select>
            <select wire:model.live="filterDriverId" class="px-2 py-1.5 rounded-lg border border-slate-200 text-[10px] font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085]">
                <option value="">Todos los choferes</option>
                @foreach($drivers as $d)
                <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
            </select>
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
        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200 w-[800px] max-h-[90vh] overflow-y-auto p-6"
             @click.outside="show = false">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-slate-800">{{ $editId ? 'Editar' : 'Nuevo' }} Vehículo</h3>
                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-600 transition p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Marca</label>
                    <input wire:model="brand" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('brand') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Modelo</label>
                    <input wire:model="model" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('model') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Año</label>
                    <input wire:model="year" type="number" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('year') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Placas</label>
                    <input wire:model="plate" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs uppercase focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('plate') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo Combustible</label>
                    <select wire:model="fuel_type" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        @foreach($fuelTypes as $ft)
                        <option value="{{ $ft->value }}">{{ $ft->label }}</option>
                        @endforeach
                    </select>
                    @error('fuel_type') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Capacidad de Carga</label>
                    <select wire:model="cargo_capacity" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        @foreach($cargoCapacities as $cc)
                        <option value="{{ $cc->value }}">{{ $cc->label }}</option>
                        @endforeach
                    </select>
                    @error('cargo_capacity') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Capacidad Tanque (L)</label>
                    <input wire:model="tank_capacity" type="number" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('tank_capacity') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">VIN</label>
                    <input wire:model="vin" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs uppercase focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('vin') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Color</label>
                    <input wire:model="color" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('color') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Motor</label>
                    <input wire:model="engine" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('engine') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Fecha de Adquisición</label>
                    <input wire:model="acquisition_date" type="date" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('acquisition_date') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Costo de Adquisición ($)</label>
                    <input wire:model="acquisition_cost" type="number" step="0.01" min="0" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('acquisition_cost') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tipo de Vehículo</label>
                    <select wire:model="vehicle_type" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        <option value="">Seleccionar...</option>
                        @foreach($vehicleTypes as $vt)
                        <option value="{{ $vt->value }}">{{ $vt->label }}</option>
                        @endforeach
                    </select>
                    @error('vehicle_type') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Chofer Asignado</label>
                    <select wire:model="assigned_driver_id" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                        <option value="">Sin asignar</option>
                        @foreach($drivers as $d)
                        <option value="{{ $d->id }}">{{ $d->name }} @if($d->license_type) · {{ $d->license_type }} @endif</option>
                        @endforeach
                    </select>
                    @error('assigned_driver_id') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Odómetro Actual (Km)</label>
                    <input wire:model="current_odometer" type="number" min="0" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('current_odometer') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Combustible Autorizado (L/semana)</label>
                    <input wire:model="authorized_fuel" type="number" step="0.1" min="0" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('authorized_fuel') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="gps_installed" class="w-3.5 h-3.5 rounded border-gray-300 text-[#E72085] focus:ring-[#E72085]">
                        <span class="text-xs font-bold text-slate-600">GPS Instalado</span>
                    </label>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Notas</label>
                    <textarea wire:model="notes" rows="2" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none"></textarea>
                    @error('notes') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2 flex gap-2">
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
        <div class="relative bg-white rounded-2xl shadow-xl border border-slate-200 w-[800px] max-h-[90vh] overflow-y-auto p-6"
             @click.outside="show = false">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-slate-800">
                    Expediente · {{ $detailVehicle?->brand }} {{ $detailVehicle?->model }} ({{ $detailVehicle?->year }})
                </h3>
                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-600 transition p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if($detailVehicle)
            <div class="space-y-5">
                {{-- Identificación --}}
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Identificación</h4>
                    <div class="grid grid-cols-3 gap-3 text-xs">
                        <div><span class="text-gray-400">Placas</span><p class="font-bold text-gray-800">{{ $detailVehicle->plate }}</p></div>
                        <div><span class="text-gray-400">VIN</span><p class="font-bold text-gray-800">{{ $detailVehicle->vin }}</p></div>
                        <div><span class="text-gray-400">Marca</span><p class="font-bold text-gray-800">{{ $detailVehicle->brand }}</p></div>
                        <div><span class="text-gray-400">Modelo</span><p class="font-bold text-gray-800">{{ $detailVehicle->model }}</p></div>
                        <div><span class="text-gray-400">Año</span><p class="font-bold text-gray-800">{{ $detailVehicle->year }}</p></div>
                        <div><span class="text-gray-400">Color</span><p class="font-bold text-gray-800">{{ $detailVehicle->color ?? '—' }}</p></div>
                        <div><span class="text-gray-400">Motor</span><p class="font-bold text-gray-800">{{ $detailVehicle->engine ?? '—' }}</p></div>
                        <div><span class="text-gray-400">Status</span>
                            <p class="font-bold {{ $detailVehicle->status === \App\Domains\Vehicle\Enums\VehicleStatusEnum::Activa ? 'text-emerald-600' : ($detailVehicle->status === \App\Domains\Vehicle\Enums\VehicleStatusEnum::EnMantenimiento ? 'text-amber-600' : 'text-red-600') }}">
                                {{ $detailVehicle->status->value }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Especificaciones --}}
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Especificaciones</h4>
                    <div class="grid grid-cols-3 gap-3 text-xs">
                        <div><span class="text-gray-400">Combustible</span><p class="font-bold text-gray-800">{{ $detailVehicle->fuel_type }}</p></div>
                        <div><span class="text-gray-400">Cap. Carga</span><p class="font-bold text-gray-800">{{ $detailVehicle->cargo_capacity }}</p></div>
                        <div><span class="text-gray-400">Cap. Tanque</span><p class="font-bold text-gray-800">{{ $detailVehicle->tank_capacity }} L</p></div>
                        <div><span class="text-gray-400">GPS</span><p class="font-bold text-gray-800">{{ $detailVehicle->gps_installed ? 'Instalado' : 'No instalado' }}</p></div>
                        <div><span class="text-gray-400">Odómetro</span><p class="font-bold text-gray-800">{{ number_format($detailVehicle->current_odometer) }} Km</p></div>
                        <div><span class="text-gray-400">Comb. Autorizado</span><p class="font-bold text-[#008FD3]">{{ $detailVehicle->authorized_fuel }} L/sem</p></div>
                    </div>
                </div>

                {{-- Asignación --}}
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Asignación</h4>
                    <div class="grid grid-cols-3 gap-3 text-xs">
                        <div><span class="text-gray-400">Tipo de Vehículo</span>
                            <p class="font-bold text-gray-800">{{ $detailVehicle->vehicle_type ?? '—' }}</p>
                        </div>
                        <div><span class="text-gray-400">Chofer Asignado</span>
                            <p class="font-bold text-gray-800">{{ $detailVehicle->assignedDriver?->name ?? 'Sin asignar' }}</p>
                        </div>
                        @if($detailVehicle->assignedDriver)
                        <div><span class="text-gray-400">Licencia</span>
                            <p class="font-bold text-gray-800">{{ $detailVehicle->assignedDriver->license_type ?? '—' }}</p>
                        </div>
                        <div><span class="text-gray-400">Documento</span>
                            <p class="font-bold text-gray-800">{{ $detailVehicle->assignedDriver->document_type ?? '' }} {{ $detailVehicle->assignedDriver->document_number ?? '' }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Adquisición --}}
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Adquisición</h4>
                    <div class="grid grid-cols-3 gap-3 text-xs">
                        <div><span class="text-gray-400">Fecha de Adquisición</span>
                            <p class="font-bold text-gray-800">{{ $detailVehicle->acquisition_date ? \Carbon\Carbon::parse($detailVehicle->acquisition_date)->format('d/m/Y') : '—' }}</p>
                        </div>
                        <div><span class="text-gray-400">Costo</span>
                            <p class="font-bold text-gray-800">{{ $detailVehicle->acquisition_cost ? '$ '.number_format($detailVehicle->acquisition_cost, 2) : '—' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Mantenimiento --}}
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Mantenimiento</h4>
                    <div class="grid grid-cols-3 gap-3 text-xs">
                        <div><span class="text-gray-400">Último Servicio</span><p class="font-bold text-gray-800">{{ $detailVehicle->last_maintenance }}</p></div>
                        <div><span class="text-gray-400">Próximo Servicio</span>
                            @php $daysLeft = now()->diffInDays(\Carbon\Carbon::parse($detailVehicle->next_maintenance), false); @endphp
                            <p class="font-bold {{ $daysLeft < 0 ? 'text-red-500' : ($daysLeft <= 15 ? 'text-amber-500' : 'text-emerald-600') }}">{{ $detailVehicle->next_maintenance }}</p>
                        </div>
                        <div><span class="text-gray-400">Incidencias</span>
                            <p class="font-bold {{ $detailVehicle->incidents_count > 0 ? 'text-red-500' : 'text-gray-700' }}">{{ $detailVehicle->incidents_count }} registradas</p>
                        </div>
                    </div>
                </div>

                {{-- Notas --}}
                @if($detailVehicle->notes)
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2 pb-1 border-b border-gray-100">Notas</h4>
                    <p class="text-xs text-gray-600 leading-relaxed">{{ $detailVehicle->notes }}</p>
                </div>
                @endif
            </div>
            @endif

            <div class="mt-6 pt-4 border-t border-gray-100">
                <button type="button" @click="show = false" class="w-full px-6 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs font-black hover:bg-slate-50 transition">Cerrar</button>
            </div>
        </div>
    </div>

    {{-- LIST --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($vehicles as $v)
        <div class="border border-gray-100 rounded-xl p-4 bg-slate-50/50 hover:border-pink-200 hover:shadow-sm transition">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">V-{{ $v->id }} · {{ $v->vin }}</span>
                    <h4 class="text-sm font-black text-gray-900 mt-0.5">{{ $v->brand }} {{ $v->model }} ({{ $v->year }})</h4>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold
                    @if($v->status === \App\Domains\Vehicle\Enums\VehicleStatusEnum::Activa) bg-emerald-100 text-emerald-800
                    @elseif($v->status === \App\Domains\Vehicle\Enums\VehicleStatusEnum::EnMantenimiento) bg-amber-100 text-amber-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ $v->status->value }}
                </span>
            </div>
 
            <div class="space-y-1.5 text-xs text-gray-600 mt-3 pt-3 border-t border-gray-100">
                <div class="flex justify-between">
                    <span>Placas:</span>
                    <span class="font-bold text-gray-800">{{ $v->plate }}</span>
                </div>

                <div class="flex justify-between">
                    <span>Kilometraje Actual:</span>
                    <span class="font-bold text-gray-800">{{ number_format($v->current_odometer) }} Km</span>
                </div>

                <div class="flex justify-between">
                    <span>Combustible / Tanque:</span>
                    <span class="text-gray-800">{{ $v->fuel_type }} ({{ $v->tank_capacity }} L)</span>
                </div>
                
                <div class="flex justify-between">
                    <span>Último Servicio:</span>
                    <span class="text-gray-800">{{ $v->last_maintenance }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span>Próximo Servicio:</span>
                    @php
                        $daysLeft = now()->diffInDays(\Carbon\Carbon::parse($v->next_maintenance), false);
                    @endphp
                    <span class="font-bold {{ $daysLeft < 0 ? 'text-red-500' : ($daysLeft <= 15 ? 'text-amber-500' : 'text-emerald-600') }}">
                        {{ $v->next_maintenance }}
                        @if($daysLeft < 0) ({{ abs($daysLeft) }} días vencido)
                        @elseif($daysLeft === 0) (Hoy)
                        @else ({{ $daysLeft }} días)
                        @endif
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span>Límite Semanal Autorizado:</span>
                    <span class="font-bold text-[#008FD3]">{{ $v->authorized_fuel }} L</span>
                </div>
                
                <div class="flex justify-between">
                    <span>Incidencias Totales:</span>
                    <span class="font-bold {{ $v->incidents_count > 0 ? 'text-red-500' : 'text-gray-700' }}">
                        {{ $v->incidents_count }} registradas
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span>GPS:</span>
                    <span class="font-bold text-gray-800">{{ $v->gps_installed ? 'Instalado' : 'No instalado' }}</span>
                </div>
            </div>
            @if($v->notes)
                <div class="pt-2 mt-2 border-t border-dashed border-gray-200">
                    <span class="text-[10px] text-gray-400 font-bold block mb-1">Observaciones:</span>
                    <p class="text-xs text-gray-600 leading-relaxed">{{ $v->notes }}</p>
                </div>
            @endif
 
            {{-- Change Vehicle Status --}}
            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between gap-1">
                <span class="text-[10px] text-gray-400 font-bold">Cambiar Estatus:</span>
                <div class="flex gap-1">
                    <button wire:click="changeStatus({{ $v->id }}, 'Activa')" class="px-2 py-0.5 rounded text-[10px] font-bold {{ $v->status === \App\Domains\Vehicle\Enums\VehicleStatusEnum::Activa ? 'bg-emerald-500 text-white' : 'bg-white border text-gray-500 hover:bg-gray-100' }}">Activa</button>
                    <button wire:click="changeStatus({{ $v->id }}, 'En mantenimiento')" class="px-2 py-0.5 rounded text-[10px] font-bold {{ $v->status === \App\Domains\Vehicle\Enums\VehicleStatusEnum::EnMantenimiento ? 'bg-amber-500 text-white' : 'bg-white border text-gray-500 hover:bg-gray-100' }}">Taller</button>
                    <button wire:click="changeStatus({{ $v->id }}, 'Fuera de servicio')" class="px-2 py-0.5 rounded text-[10px] font-bold {{ $v->status === \App\Domains\Vehicle\Enums\VehicleStatusEnum::FueraDeServicio ? 'bg-red-500 text-white' : 'bg-white border text-gray-500 hover:bg-gray-100' }}">Baja</button>
                </div>
            </div>
 
            <div class="mt-3 flex gap-2">
                <button wire:click="showExpediente({{ $v->id }})" class="flex-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 py-1.5 rounded-lg transition">
                    Expediente
                </button>
                <button wire:click="edit({{ $v->id }})" class="flex-1 text-[10px] font-bold text-[#008FD3] bg-blue-50 hover:bg-blue-100 py-1.5 rounded-lg transition">
                    Editar
                </button>
                <button wire:click="confirmDelete({{ $v->id }})" class="flex-1 text-[10px] font-bold text-red-600 bg-red-50 hover:bg-red-100 py-1.5 rounded-lg transition">
                    Eliminar
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-400 text-sm font-bold">No hay vehículos registrados</div>
        @endforelse
    </div>

    <div class="pt-2">
        {{ $vehicles->links() }}
    </div>
</div>
