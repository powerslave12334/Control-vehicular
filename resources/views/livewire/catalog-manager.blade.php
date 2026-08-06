<div>
    <div class="bg-white rounded-3xl shadow-xs border border-slate-200 p-6 md:p-8 space-y-6">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <h2 class="text-lg font-black text-slate-800">Catálogos del Sistema</h2>
            <div class="flex items-center gap-2">
                <button wire:click="create" class="flex items-center gap-1.5 bg-[#E72085] hover:bg-[#E72085]/95 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-md shadow-[#E72085]/15">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nuevo
                </button>
            </div>
        </div>

        <div class="flex gap-2 flex-wrap">
            <button wire:click="$set('filterGroup', '')" type="button"
                class="px-3 py-1.5 rounded-lg text-xs font-bold transition
                {{ $filterGroup === '' ? 'bg-[#E72085] text-white shadow-md' : 'bg-white border border-gray-200 text-gray-500 hover:bg-slate-50' }}">
                Todos
            </button>
            @foreach($groups as $g)
            <button wire:click="$set('filterGroup', '{{ $g }}')" type="button"
                wire:key="filter-{{ $loop->index }}"
                class="px-3 py-1.5 rounded-lg text-xs font-bold transition
                {{ $filterGroup === $g ? 'bg-[#E72085] text-white shadow-md' : 'bg-white border border-gray-200 text-gray-500 hover:bg-slate-50' }}">
                {{ $groupLabels[$g] ?? $g }}
            </button>
            @endforeach
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($catalogs as $groupName => $items)
            <div class="bg-white rounded-xl border-2 border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-4 py-2.5 border-b border-gray-100">
                    <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider">{{ $groupLabels[$groupName] ?? $groupName }}</h3>
                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">{{ $items->count() }} registros</p>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($items as $c)
                    <div class="px-4 py-2.5 flex items-center justify-between hover:bg-slate-50/50 transition" wire:key="catalog-{{ $c->id }}">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ $c->label }}</p>
                            <p class="text-[10px] text-slate-400 font-semibold">Valor: <span class="font-bold text-slate-600">{{ $c->value }}</span></p>
                        </div>
                        <div class="flex items-center gap-1 shrink-0 ml-2">
                            <button wire:click="edit({{ $c->id }})" class="p-1 rounded-lg text-blue-500 hover:bg-blue-50 transition" title="Editar">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="confirmDelete({{ $c->id }})" class="p-1 rounded-lg text-red-500 hover:bg-red-50 transition" title="Eliminar">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12 text-gray-400 text-sm font-bold">No hay catálogos registrados</div>
            @endforelse
        </div>
    </div>

    <div x-data="{ show: @entangle('showForm') }" x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition.opacity>
        <div class="absolute inset-0 bg-black/40" x-on:click="show = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6" x-on:click.stop>
            <button x-on:click="show = false" class="absolute top-3 right-3 p-1.5 rounded-lg hover:bg-slate-100 text-gray-400 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <h3 class="text-sm font-black text-gray-800 mb-4">{{ $editId ? 'Editar' : 'Nuevo' }} Catálogo</h3>
            <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Grupo</label>
                    <input wire:model="group" list="group-suggestions" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    <datalist id="group-suggestions">
                        @foreach($groups as $g)
                        <option value="{{ $g }}">
                        @endforeach
                    </datalist>
                    @error('group') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Valor</label>
                    <input wire:model="value" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('value') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Etiqueta</label>
                    <input wire:model="label" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @error('label') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="px-6 py-2 rounded-xl bg-[#E72085] text-white text-xs font-black uppercase tracking-wider hover:bg-[#d01c73] transition shadow-md shadow-[#E72085]/15">{{ $editId ? 'Actualizar' : 'Guardar' }}</button>
                    <button type="button" x-on:click="show = false" class="px-6 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs font-black hover:bg-slate-50 transition">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
