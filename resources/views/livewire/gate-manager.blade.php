<div class="space-y-6">

    {{-- ═══════════════════════ MASTHEAD — La caseta ═══════════════════════ --}}
    <div>
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <p class="font-mono text-[10px] font-bold uppercase tracking-[0.25em] text-[#E72085]">Garita · Agua
                    Inmaculada</p>
                <h2
                    class="mt-1.5 font-mono text-2xl md:text-3xl font-black uppercase tracking-tight text-slate-900 leading-none">
                    Bitácora de portería</h2>
                <p class="mt-2 text-xs text-slate-500 font-medium">Registro de entradas y salidas de vehículos de la
                    flota.</p>
            </div>
            <div class="text-left md:text-right">
                <p class="font-mono text-xl md:text-2xl font-black tabular-nums text-slate-900 uppercase leading-none">
                    {{ $todayMono }}</p>
                <div class="mt-1.5 flex items-center gap-2 md:justify-end">
                    <span class="font-mono text-[10px] font-bold uppercase tracking-widest text-slate-400">Hora</span>
                    <span class="font-mono text-sm font-bold tabular-nums text-slate-700" x-data
                        x-init="$el.textContent = new Date().toLocaleTimeString('es-MX', {hour12:false}); setInterval(() => $el.textContent = new Date().toLocaleTimeString('es-MX', {hour12:false}), 1000)">--:--:--</span>
                </div>
            </div>
        </div>

        {{-- Pulso operativo --}}
        <div
            class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-px bg-slate-200 rounded-2xl overflow-hidden border border-slate-200">
            <div class="bg-white px-5 py-3.5 flex items-center gap-3">
                <span
                    class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </span>
                <div>
                    <p class="font-mono text-xl font-black tabular-nums text-slate-900 leading-none">{{ $todayEntries }}
                    </p>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-0.5">Entradas hoy</p>
                </div>
            </div>
            <div class="bg-white px-5 py-3.5 flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-red-100 text-red-700 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16l4-4m0 0l-4-4m4 4H3m5 4v1a3 3 0 003 3h7a3 3 0 003-3V7a3 3 0 00-3-3h-7a3 3 0 00-3 3v1" />
                    </svg>
                </span>
                <div>
                    <p class="font-mono text-xl font-black tabular-nums text-slate-900 leading-none">{{ $todayExits }}
                    </p>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-0.5">Salidas hoy</p>
                </div>
            </div>
            <div class="bg-white px-5 py-3.5 flex items-center gap-3">
                <span
                    class="w-9 h-9 rounded-xl bg-[#E72085]/10 text-[#E72085] flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 10h.01M15 10h.01" />
                    </svg>
                </span>
                <div>
                    <p class="font-mono text-xl font-black tabular-nums text-slate-900 leading-none">{{ $inYard }}</p>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-0.5">En patio</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════ WORKSPACE ═══════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">

        {{-- FORM — el sello --}}
        <div class="lg:col-span-2 lg:sticky lg:top-20 lg:max-h-[calc(100dvh-6.25rem)] lg:flex lg:flex-col lg:overflow-hidden min-w-0">
            <div
                class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden lg:flex lg:flex-col lg:flex-1 lg:min-h-0">
                <div class="px-5 pt-5 pb-4 border-b border-slate-100 lg:shrink-0">
                    <p class="font-mono text-[10px] font-bold uppercase tracking-[0.25em] text-[#E72085]">Nuevo pase</p>
                    <h3 class="mt-1 font-mono text-lg font-black uppercase tracking-tight text-slate-900">Registrar pase
                    </h3>
                </div>

                <form wire:submit="save" x-on:submit="gateSign.capture()"
                    class="p-5 space-y-4 lg:flex lg:flex-col lg:flex-1 lg:min-h-0 lg:p-4 lg:space-y-0">

                    <div class="lg:flex-1 lg:overflow-y-auto lg:space-y-3 lg:pr-0.5">
                        {{-- Sentido --}}
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Sentido</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label
                                    class="flex items-center justify-center gap-2 p-2.5 rounded-xl border-2 cursor-pointer text-xs font-black uppercase tracking-wider transition focus-within:ring-2 focus-within:ring-[#E72085]/25
                                {{ $type === 'entry' ? 'border-emerald-600 bg-emerald-600 text-white shadow-sm shadow-emerald-600/20' : 'border-slate-200 bg-white text-slate-500 hover:border-emerald-400' }}">
                                    <input wire:model.live="type" type="radio" value="entry" class="sr-only">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    Entrada
                                </label>
                                <label
                                    class="flex items-center justify-center gap-2 p-2.5 rounded-xl border-2 cursor-pointer text-xs font-black uppercase tracking-wider transition focus-within:ring-2 focus-within:ring-[#E72085]/25
                                {{ $type === 'exit' ? 'border-red-600 bg-red-600 text-white shadow-sm shadow-red-600/20' : 'border-slate-200 bg-white text-slate-500 hover:border-red-400' }}">
                                    <input wire:model.live="type" type="radio" value="exit" class="sr-only">
                                    Salida
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16l4-4m0 0l-4-4m4 4H3m5 4v1a3 3 0 003 3h7a3 3 0 003-3V7a3 3 0 00-3-3h-7a3 3 0 00-3 3v1" />
                                    </svg>
                                </label>
                            </div>
                            @error('type') <span class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Vehículo --}}
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Vehículo</label>
                            <select wire:model.live="vehicle_id"
                                class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-800 focus:ring-2 focus:ring-[#E72085]/15 focus:border-[#E72085] outline-none transition">
                                <option value="">Seleccionar…</option>
                                @foreach($vehicles as $v)
                                <option value="{{ $v->id }}">{{ $v->plate }} · {{ $v->brand }} {{ $v->model }}</option>
                                @endforeach
                            </select>
                            @error('vehicle_id') <span class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                            @if($vehicle_plate)
                            <div
                                class="flex items-center gap-3 mt-1.5 text-[10px] font-mono font-semibold text-slate-500">
                                <span>Placas: <b class="text-slate-700">{{ $vehicle_plate }}</b></span>
                                <span>Marca: <b class="text-slate-700">{{ $vehicle_type_name }}</b></span>
                            </div>
                            @endif
                        </div>

                        {{-- CAMPOS DE ENTRADA --}}
                        @if($type === 'entry')
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Conductor</label>
                                <input wire:model="driver_name"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-800 placeholder:text-slate-400 focus:ring-2 focus:ring-[#E72085]/15 focus:border-[#E72085] outline-none transition">
                                @error('driver_name') <span
                                    class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Folio</label>
                                <input wire:model="route_folio" placeholder="R-2026-001"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-800 placeholder:text-slate-400 focus:ring-2 focus:ring-[#E72085]/15 focus:border-[#E72085] outline-none transition">
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Fecha
                                    entrada <span
                                        class="ml-1 text-[9px] font-black text-slate-300 normal-case tracking-wider">Automática</span></label>
                                <input wire:model="entry_date" type="date" disabled
                                    class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-600 cursor-not-allowed outline-none">
                                @error('entry_date') <span
                                    class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Hora
                                    entrada <span
                                        class="ml-1 text-[9px] font-black text-slate-300 normal-case tracking-wider">Automática</span></label>
                                <input wire:model="entry_time" type="time" disabled
                                    class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-600 cursor-not-allowed outline-none">
                                @error('entry_time') <span
                                    class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kilometraje</label>
                                <input wire:model="initial_odometer" type="number" step="0.1" placeholder="45 230"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-800 placeholder:text-slate-400 focus:ring-2 focus:ring-[#E72085]/15 focus:border-[#E72085] outline-none transition">
                                @error('initial_odometer') <span
                                    class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Gasolina</label>
                                <select wire:model="fuel_level"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-800 focus:ring-2 focus:ring-[#E72085]/15 focus:border-[#E72085] outline-none transition">
                                    <option value="">Seleccionar…</option>
                                    @foreach($fuelLevels as $fl)
                                    <option value="{{ $fl->value }}">{{ $fl->label }}</option>
                                    @endforeach
                                </select>
                                @error('fuel_level') <span
                                    class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Llanta
                                    refacción</label>
                                <div class="flex gap-4 h-full items-center px-1">
                                    <label
                                        class="flex items-center gap-1.5 cursor-pointer text-xs font-bold text-slate-600">
                                        <input wire:model="has_spare_tire" type="radio" value="1"
                                            class="text-emerald-600 focus:ring-emerald-500">Sí
                                    </label>
                                    <label
                                        class="flex items-center gap-1.5 cursor-pointer text-xs font-bold text-slate-600">
                                        <input wire:model="has_spare_tire" type="radio" value="0"
                                            class="text-emerald-600 focus:ring-emerald-500">No
                                    </label>
                                </div>
                                @error('has_spare_tire') <span
                                    class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        @endif

                        {{-- CAMPOS DE SALIDA --}}
                        @if($type === 'exit')
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Folio</label>
                                <input wire:model="route_folio" placeholder="R-2026-001"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-800 placeholder:text-slate-400 focus:ring-2 focus:ring-[#E72085]/15 focus:border-[#E72085] outline-none transition">
                                @error('route_folio') <span
                                    class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Llanta
                                    refacción</label>
                                <div class="flex gap-4 h-full items-center px-1">
                                    <label
                                        class="flex items-center gap-1.5 cursor-pointer text-xs font-bold text-slate-600">
                                        <input wire:model="has_spare_tire" type="radio" value="1"
                                            class="text-emerald-600 focus:ring-emerald-500">Sí
                                    </label>
                                    <label
                                        class="flex items-center gap-1.5 cursor-pointer text-xs font-bold text-slate-600">
                                        <input wire:model="has_spare_tire" type="radio" value="0"
                                            class="text-emerald-600 focus:ring-emerald-500">No
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Fecha
                                    salida <span
                                        class="ml-1 text-[9px] font-black text-slate-300 normal-case tracking-wider">Automática</span></label>
                                <input wire:model="exit_date" type="date" disabled
                                    class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-600 cursor-not-allowed outline-none">
                                @error('exit_date') <span
                                    class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Hora
                                    salida <span
                                        class="ml-1 text-[9px] font-black text-slate-300 normal-case tracking-wider">Automática</span></label>
                                <input wire:model="exit_time" type="time" disabled
                                    class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-600 cursor-not-allowed outline-none">
                                @error('exit_time') <span
                                    class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kilometraje</label>
                                <input wire:model="initial_odometer" type="number" step="0.1" placeholder="45 230"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-800 placeholder:text-slate-400 focus:ring-2 focus:ring-[#E72085]/15 focus:border-[#E72085] outline-none transition">
                                @error('initial_odometer') <span
                                    class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Gasolina</label>
                                <select wire:model="fuel_level"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-800 focus:ring-2 focus:ring-[#E72085]/15 focus:border-[#E72085] outline-none transition">
                                    <option value="">Seleccionar…</option>
                                    @foreach($fuelLevels as $fl)
                                    <option value="{{ $fl->value }}">{{ $fl->label }}</option>
                                    @endforeach
                                </select>
                                @error('fuel_level') <span
                                    class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        @endif

                        {{-- COMUNES: fotos conductor + vehículo --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Foto
                                    del conductor</label>
                                <input wire:model="driver_photo" type="file" accept="image/*"
                                    class="w-full text-[10px] font-mono text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                @error('driver_photo') <span
                                    class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                @if($driver_photo)
                                <div class="mt-2">
                                    <img src="{{ $driver_photo->temporaryUrl() }}"
                                        class="h-16 rounded-lg object-cover shadow-xs">
                                </div>
                                @endif
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Foto
                                    del vehículo</label>
                                <input wire:model="photo" type="file" accept="image/*"
                                    class="w-full text-[10px] font-mono text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                @error('photo') <span class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span>
                                @enderror
                                @if($photo)
                                <div class="mt-2">
                                    <img src="{{ $photo->temporaryUrl() }}"
                                        class="h-16 rounded-lg object-cover shadow-xs">
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- COMUNES: estado del vehículo --}}
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Estado
                                del vehículo</label>
                            <select wire:model="vehicle_condition"
                                class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-800 focus:ring-2 focus:ring-[#E72085]/15 focus:border-[#E72085] outline-none transition">
                                <option value="">Seleccionar…</option>
                                @foreach($vehicleConditions as $vc)
                                <option value="{{ $vc->value }}">{{ $vc->label }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- CHECK LIST --}}
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Check
                                list del vehículo</label>
                            <div class="grid grid-cols-1 gap-1">
                                @foreach($checklistOptions as $key => $label)
                                <label
                                    class="flex items-center gap-2 p-2 rounded-lg border transition cursor-pointer text-xs
                                {{ in_array($key, $checklistItems) ? 'border-emerald-400 bg-emerald-50 text-emerald-700 font-bold' : 'border-slate-200 text-slate-600' }}">
                                    <input type="checkbox" value="{{ $key }}" wire:model="checklistItems"
                                        class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                    {{ $label }}
                                </label>
                                @endforeach
                            </div>
                            @error('checklistItems') <span
                                class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- OBSERVACIONES --}}
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Observaciones</label>
                            <textarea wire:model="notes" rows="2"
                                class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-800 placeholder:text-slate-400 focus:ring-2 focus:ring-[#E72085]/15 focus:border-[#E72085] outline-none transition"></textarea>
                            @error('notes') <span class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- CONFIRMACIÓN --}}
                        <div>
                            <label
                                class="flex items-start gap-2.5 p-3 rounded-xl border-2 cursor-pointer text-xs transition
                                {{ $confirmed ? 'border-emerald-400 bg-emerald-50 text-emerald-800 font-bold' : 'border-slate-200 bg-white text-slate-600 hover:border-emerald-300' }}">
                                <input wire:model="confirmed" type="checkbox"
                                    class="mt-0.5 w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span>Confirmo que revisé el estado del vehículo descrito arriba y que los datos
                                    capturados son correctos.</span>
                            </label>
                            @error('confirmed') <span class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- FIRMA --}}
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Firma</label>
                            <div class="border border-slate-200 rounded-xl overflow-hidden" x-data="signaturePad()">
                                <canvas x-ref="canvas" class="w-full h-24 bg-white cursor-crosshair"
                                    style="touch-action: none;"></canvas>
                                <div
                                    class="flex items-center justify-between px-3 py-1.5 bg-slate-50 border-t border-slate-100">
                                    <span class="text-[10px] text-slate-400 font-mono" x-show="!sigData">Firme
                                        aquí</span>
                                    <span class="text-[10px] text-emerald-600 font-mono font-bold" x-show="sigData"
                                        x-cloak>● Firma capturada</span>
                                    <button type="button" @click="clear()"
                                        class="text-[10px] font-bold text-red-500 hover:text-red-700 transition px-2 py-0.5 rounded hover:bg-red-50">
                                        Borrar firma
                                    </button>
                                </div>
                                <input type="hidden" wire:model="signature" x-ref="sigInput">
                            </div>
                            @error('signature') <span class="text-[10px] text-red-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- ACCIONES --}}
                    <div class="flex gap-2 lg:mt-3 lg:pt-3 lg:border-t lg:border-slate-100 lg:shrink-0">
                        <button type="submit"
                            class="flex-1 px-6 py-3 rounded-xl bg-[#E72085] text-white font-mono text-xs font-black uppercase tracking-[0.2em] shadow-md shadow-[#E72085]/25 transition hover:bg-[#C9106A] active:translate-y-[1px] active:scale-[0.99] focus-visible:ring-2 focus-visible:ring-[#E72085]/40 focus-visible:outline-none">
                            Registrar pase
                        </button>
                        <button type="button" wire:click="resetForm"
                            class="px-4 py-3 rounded-xl border border-slate-200 text-slate-500 text-xs font-black uppercase tracking-wider hover:bg-slate-50 transition focus-visible:ring-2 focus-visible:ring-slate-300 focus-visible:outline-none">
                            Borrar campos
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- BITÁCORA --}}
        <div class="lg:col-span-3 min-w-0">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between gap-3">
                    <div>
                        <p class="font-mono text-[10px] font-bold uppercase tracking-[0.25em] text-[#E72085]">Bitácora
                        </p>
                        <h3 class="mt-0.5 font-mono text-base font-black uppercase tracking-tight text-slate-900">
                            {{ $todayFull }}</h3>
                    </div>
                    <span class="font-mono text-xs font-bold text-slate-400 tabular-nums shrink-0">{{ $logs->total() }}
                        pases</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-left">
                        <thead>
                            <tr class="font-mono text-[9px] uppercase tracking-[0.15em] text-slate-400">
                                <th class="px-4 py-2.5 font-bold border-b-2 border-slate-200 whitespace-nowrap">Hora
                                </th>
                                <th class="px-2 py-2.5 font-bold border-b-2 border-slate-200 whitespace-nowrap">Sentido
                                </th>
                                <th class="px-2 py-2.5 font-bold border-b-2 border-slate-200 whitespace-nowrap">Placa
                                </th>
                                <th class="px-2 py-2.5 font-bold border-b-2 border-slate-200 whitespace-nowrap">
                                    Conductor</th>
                                <th
                                    class="px-2 py-2.5 font-bold border-b-2 border-slate-200 text-right whitespace-nowrap">
                                    Km</th>
                                <th
                                    class="px-2 py-2.5 font-bold border-b-2 border-slate-200 text-right whitespace-nowrap">
                                    Gas</th>
                                <th class="px-2 py-2.5 font-bold border-b-2 border-slate-200 whitespace-nowrap">Firma
                                </th>
                                <th class="px-4 py-2.5 border-b-2 border-slate-200 text-right whitespace-nowrap"><span
                                        class="sr-only">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr data-log="{{ $log->id }}"
                                class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td
                                    class="px-4 py-3 font-mono text-xs font-bold tabular-nums text-slate-700 whitespace-nowrap">
                                    {{ $log->type->value === 'entry' ? ($log->entry_time ?? $log->logged_at?->format('H:i')) : ($log->exit_time ?? $log->logged_at?->format('H:i')) }}
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full font-mono text-[9px] font-black uppercase tracking-wider {{ $log->type->value === 'entry' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($log->type->value === 'entry')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                            @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16l4-4m0 0l-4-4m4 4H3m5 4v1a3 3 0 003 3h7a3 3 0 003-3V7a3 3 0 00-3-3h-7a3 3 0 00-3 3v1" />
                                            @endif
                                        </svg>
                                        {{ $log->type->value === 'entry' ? 'Entrada' : 'Salida' }}
                                    </span>
                                </td>
                                <td class="px-2 py-3 font-mono text-xs font-black text-slate-800 whitespace-nowrap">
                                    {{ $log->vehicle?->plate ?? '—' }}</td>
                                <td class="px-2 py-3 text-xs text-slate-600 whitespace-nowrap">
                                    {{ $log->driver_name ?? '—' }}</td>
                                <td
                                    class="px-2 py-3 text-right font-mono text-xs tabular-nums text-slate-700 whitespace-nowrap">
                                    {{ $log->initial_odometer !== null ? number_format($log->initial_odometer, 0) : '—' }}
                                </td>
                                <td
                                    class="px-2 py-3 text-right font-mono text-xs tabular-nums text-slate-700 whitespace-nowrap">
                                    {{ $log->fuel_level !== null ? $log->fuel_level . '%' : '—' }}</td>
                                <td class="px-2 py-3 whitespace-nowrap">
                                    @if($log->signature)
                                    <span
                                        class="text-[10px] font-black uppercase tracking-wider text-blue-600">Firmado</span>
                                    @else
                                    <span class="text-slate-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-0.5">
                                        @if($log->photo)
                                        <a href="{{ \Illuminate\Support\Facades\Storage::url($log->photo) }}"
                                            target="_blank"
                                            class="p-1.5 text-slate-400 hover:text-emerald-600 rounded-lg hover:bg-emerald-50 transition"
                                            title="Ver foto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        @endif
                                        @if($log->signature)
                                        <button type="button" x-data @click="showSignature('{{ $log->signature }}')"
                                            class="p-1.5 text-slate-400 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition"
                                            title="Ver firma">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        @endif
                                        <button wire:click="confirmDelete({{ $log->id }})"
                                            class="p-1.5 text-slate-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition"
                                            title="Eliminar">
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
                                <td colspan="8">
                                    <div class="py-14 px-6 text-center">
                                        <div
                                            class="mx-auto w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <p class="mt-3 text-sm font-black text-slate-700">La bitácora está vacía</p>
                                        <p class="mt-1 text-xs text-slate-400">Registra la primera entrada del día desde
                                            el formulario.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                <div class="px-4 py-3 border-t border-slate-200">
                    {{ $logs->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.gateSign = {
        pad: null,
        register(pad) {
            this.pad = pad;
        },
        capture() {
            if (this.pad) this.pad.capture();
        },
    };

    function signaturePad() {
        return {
            isDrawing: false,
            sigData: '',
            init() {
                window.gateSign.register(this);

                const canvas = this.$refs.canvas;
                const ctx = canvas.getContext('2d');

                const setup = () => {
                    const rect = canvas.getBoundingClientRect();
                    canvas.width = rect.width;
                    canvas.height = rect.height;
                    ctx.strokeStyle = '#0f172a';
                    ctx.lineWidth = 2;
                    ctx.lineCap = 'round';
                    ctx.lineJoin = 'round';
                };
                setup();

                const restore = () => {
                    const input = this.$refs.sigInput;
                    const src = this.sigData || (input ? input.value : '');
                    if (src) {
                        this.sigData = src;
                        const img = new window.Image();
                        img.onload = () => ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        img.src = src;
                    }
                };
                restore();

                window.addEventListener('resize', () => {
                    const saved = canvas.toDataURL();
                    setup();
                    if (saved) {
                        const img = new window.Image();
                        img.onload = () => ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        img.src = saved;
                    }
                });

                const getPos = (e) => {
                    const r = canvas.getBoundingClientRect();
                    const t = e.touches ? e.touches[0] : e;
                    return {
                        x: t.clientX - r.left,
                        y: t.clientY - r.top
                    };
                };

                const start = (e) => {
                    e.preventDefault();
                    this.isDrawing = true;
                    const pos = getPos(e);
                    ctx.beginPath();
                    ctx.moveTo(pos.x, pos.y);
                };

                const move = (e) => {
                    e.preventDefault();
                    if (!this.isDrawing) return;
                    const pos = getPos(e);
                    ctx.lineTo(pos.x, pos.y);
                    ctx.stroke();
                };

                const end = () => {
                    if (!this.isDrawing) return;
                    this.isDrawing = false;
                    this.sigData = canvas.toDataURL('image/png');
                };

                canvas.addEventListener('mousedown', start);
                canvas.addEventListener('mousemove', move);
                canvas.addEventListener('mouseup', end);
                canvas.addEventListener('touchstart', start, {
                    passive: false
                });
                canvas.addEventListener('touchmove', move, {
                    passive: false
                });
                canvas.addEventListener('touchend', end);
            },
            capture() {
                if (this.sigData) {
                    const input = this.$refs.sigInput;
                    if (input) {
                        input.value = this.sigData;
                        input.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                    }
                }
            },
            clear() {
                const canvas = this.$refs.canvas;
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                this.sigData = '';
                const input = this.$refs.sigInput;
                if (input) {
                    input.value = '';
                    input.dispatchEvent(new Event('input', {
                        bubbles: true
                    }));
                }
            },
        };
    }

    function showSignature(dataUrl) {
        if (!dataUrl) return;
        const w = window.open('', '_blank', 'width=600,height=400');
        w.document.write(
            `<img src="${dataUrl}" style="max-width:100%;max-height:90vh;margin:auto;display:block;padding:20px;">`);
        w.document.title = 'Firma';
    }

    document.addEventListener('livewire:init', () => {
        Livewire.on('gate-row-added', ({
            id
        }) => {
            const el = document.querySelector('[data-log="' + id + '"]');
            if (el) {
                el.classList.add('gate-flash');
                window.setTimeout(() => el.classList.remove('gate-flash'), 1800);
            }
        });
    });

</script>
@endpush
