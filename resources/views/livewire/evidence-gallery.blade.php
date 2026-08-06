
<div class="bg-white rounded-3xl shadow-xs border border-slate-200 p-6 md:p-8 space-y-6">
    <div>
        <h2 class="text-lg font-black text-slate-800">Consulta de Evidencias Fotográficas</h2>
        <p class="text-sm text-slate-500 mt-1">Capturas subidas por operadores, instaladores y portería.</p>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-slate-50 rounded-xl border border-slate-200 p-3">
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Totales</p>
            <p class="text-lg font-black text-slate-800 mt-0.5">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-pink-50 rounded-xl border border-pink-200 p-3">
            <p class="text-[10px] font-extrabold text-pink-600 uppercase tracking-wider">Viaje / Instalación</p>
            <p class="text-lg font-black text-pink-800 mt-0.5">{{ $stats['trip'] }}</p>
        </div>
        <div class="bg-blue-50 rounded-xl border border-blue-200 p-3">
            <p class="text-[10px] font-extrabold text-blue-600 uppercase tracking-wider">Tickets Gasolina</p>
            <p class="text-lg font-black text-blue-800 mt-0.5">{{ $stats['fuel'] }}</p>
        </div>
        <div class="bg-emerald-50 rounded-xl border border-emerald-200 p-3">
            <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider">Portería</p>
            <p class="text-lg font-black text-emerald-800 mt-0.5">{{ $stats['gate'] }}</p>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="flex flex-col md:flex-row gap-3 items-start md:items-center justify-between">
        <div class="flex gap-2 flex-wrap">
            @php $tabs = [['all', 'Todos'], ['fuel', 'Tickets Gasolina'], ['trip', 'Viaje & Instalaciones'], ['gate', 'Portería']]; @endphp
            @foreach($tabs as [$val, $label])
            <button wire:click="$set('filter', '{{ $val }}')" wire:key="tab-{{ $val }}"
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition
                    {{ $filter === $val ? 'bg-[#008FD3] text-white' : 'bg-slate-100 text-gray-600 hover:bg-slate-200' }}">
                {{ $label }}
            </button>
            @endforeach
        </div>
        <div class="flex gap-2 items-center">
            <input wire:model.live="filterDateFrom" type="date" class="px-2 py-1.5 rounded-lg border border-slate-200 text-[10px] font-bold outline-none focus:ring-2 focus:ring-[#008FD3]/20 focus:border-[#008FD3]">
            <span class="text-[10px] text-slate-400">—</span>
            <input wire:model.live="filterDateTo" type="date" class="px-2 py-1.5 rounded-lg border border-slate-200 text-[10px] font-bold outline-none focus:ring-2 focus:ring-[#008FD3]/20 focus:border-[#008FD3]">
        </div>
    </div>

    {{-- LIGHTBOX --}}
    <div x-data="{ open: false, src: '', label: '' }"
         x-show="open"
         x-cloak
         x-transition.opacity.duration.200ms
         @keydown.escape.window="open = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
         style="display: none;">
        <button type="button" @click="open = false" class="absolute top-4 right-4 text-white/70 hover:text-white transition p-2">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="max-w-4xl max-h-[90vh]">
            <img :src="src" :alt="label" class="max-w-full max-h-[85vh] rounded-xl shadow-2xl object-contain">
            <p class="text-white/80 text-xs font-bold text-center mt-3" x-text="label"></p>
        </div>
    </div>

    {{-- GALLERY --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
        @forelse($evidences as $e)
        <div x-data
             @click="$dispatch('open-lightbox', { src: '{{ $e['url'] }}', label: '{{ $e['label'] }} · {{ $e['route'] }} · {{ $e['date'] }}' })"
             class="border border-gray-100 rounded-xl overflow-hidden bg-slate-50 hover:shadow-md transition group cursor-pointer">
            <div class="h-28 overflow-hidden relative">
                <img src="{{ $e['url'] }}" alt="{{ $e['label'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                    {{ $e['type'] === 'fuel' ? 'bg-blue-600 text-white' : ($e['type'] === 'gate' ? 'bg-emerald-600 text-white' : 'bg-pink-600 text-white') }}">
                    {{ $e['type'] === 'fuel' ? 'Gasolina' : ($e['type'] === 'gate' ? 'Portería' : 'Viaje') }}
                </span>
            </div>
            <div class="p-2 text-[10px]">
                <p class="font-bold text-slate-800 truncate">{{ $e['label'] }}</p>
                <p class="text-slate-500 font-semibold truncate">{{ $e['route'] }}</p>
                <p class="text-slate-400 truncate">{{ $e['date'] }}</p>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p class="text-sm text-slate-400 font-bold">Sin evidencias disponibles</p>
        </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($total > $perPage = 24)
    <div class="flex items-center justify-between pt-2">
        <p class="text-[10px] text-slate-400 font-bold">
            Mostrando {{ (($page - 1) * $perPage) + 1 }}-{{ min($page * $perPage, $total) }} de {{ $total }}
        </p>
        <div class="flex gap-2">
            <button wire:click="previousPage" @if($page <= 1) disabled @endif
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition
                    {{ $page <= 1 ? 'bg-slate-100 text-slate-300 cursor-not-allowed' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Anterior
            </button>
            <button wire:click="nextPage" @if($page >= $lastPage) disabled @endif
                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition
                    {{ $page >= $lastPage ? 'bg-slate-100 text-slate-300 cursor-not-allowed' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Siguiente
            </button>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('livewire:init', () => {
    document.addEventListener('open-lightbox', (e) => {
        window.dispatchEvent(new CustomEvent('open-lightbox', { detail: e.detail }));
    });
});
</script>
@endpush
