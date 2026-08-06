

<div class="space-y-5">
    <a href="{{ route('driver.dashboard') }}" class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-500 hover:text-[#E72085] transition">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Volver al inicio
    </a>

    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-5">
        <h1 class="text-lg font-black text-slate-900 mb-1">Registrar Carga de Combustible</h1>
        <p class="text-xs text-slate-500 font-semibold mb-4">{{ $route->vehicle_plate }} · {{ $route->client_name }}</p>

        <form wire:submit="save" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Litros</label>
                    <input wire:model="liters" type="number" step="0.01" class="w-full h-11 px-3 rounded-lg border border-slate-200 text-sm font-bold focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none" placeholder="0.00">
                    @error('liters') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Monto $</label>
                    <input wire:model="amount" type="number" step="0.01" class="w-full h-11 px-3 rounded-lg border border-slate-200 text-sm font-bold focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none" placeholder="0.00">
                    @error('amount') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Método de Pago</label>
                <select wire:model="payment_method" class="w-full h-11 px-3 rounded-lg border border-slate-200 text-sm font-bold focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @foreach($paymentMethods as $pm)
                    <option value="{{ $pm->value }}">{{ $pm->label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Odómetro (opcional)</label>
                <input wire:model="odometer" type="number" class="w-full h-11 px-3 rounded-lg border border-slate-200 text-sm font-bold focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none" placeholder="km actuales">
            </div>

            <div>
                <label class="block text-[10px] font-extrabold text-slate-500 uppercase mb-1">Foto del Ticket (opcional)</label>
                <input type="file" wire:model="ticket_photo" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:bg-[#E72085] file:text-white hover:file:bg-[#d01c73]">
                @error('ticket_photo') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 h-11 px-4 rounded-xl bg-[#E72085] text-white text-xs font-black hover:bg-[#d01c73] transition shadow-lg shadow-[#E72085]/25">
                    Guardar Carga
                </button>
                <a href="{{ route('driver.dashboard') }}" class="flex items-center justify-center h-11 px-4 rounded-xl border border-slate-200 text-slate-600 text-xs font-black hover:bg-slate-50 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
