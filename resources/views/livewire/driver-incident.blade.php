

<div class="space-y-5">
    <a href="{{ route('driver.dashboard') }}" class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-500 hover:text-[#E72085] transition">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Volver al inicio
    </a>

    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-5">
        <h1 class="text-lg font-black text-slate-900 mb-1">Reportar Incidencia</h1>
        <p class="text-xs text-slate-500 font-semibold mb-4">Ruta: {{ $route->client_name }} · {{ $route->vehicle_plate }}</p>

        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Título</label>
                <input wire:model="title" class="w-full h-11 px-3 rounded-lg border border-slate-200 text-sm font-bold focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none" placeholder="Ej: Cliente no estaba en domicilio">
                @error('title') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Descripción</label>
                <textarea wire:model="description" rows="4" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm font-bold focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none" placeholder="Describe lo sucedido..."></textarea>
                @error('description') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Severidad</label>
                <select wire:model="severity" class="w-full h-11 px-3 rounded-lg border border-slate-200 text-sm font-bold focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @foreach($incidentSeverities as $is)
                    <option value="{{ $is->value }}">{{ $is->label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 h-11 px-4 rounded-xl bg-[#E72085] text-white text-xs font-black hover:bg-[#d01c73] transition shadow-lg shadow-[#E72085]/25">
                    Reportar Incidencia
                </button>
                <a href="{{ route('driver.dashboard') }}" class="flex items-center justify-center h-11 px-4 rounded-xl border border-slate-200 text-slate-600 text-xs font-black hover:bg-slate-50 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
