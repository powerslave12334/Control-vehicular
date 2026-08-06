
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-5">
        <h1 class="text-xl font-black text-slate-900">Buenos días, {{ Auth::user()->name }}</h1>
        <p class="text-xs text-slate-500 font-semibold">{{ now()->format('l d \d\e F \d\e Y') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-4 text-center">
            <p class="text-2xl font-black text-[#E72085]">{{ count($todayRoutes) }}</p>
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mt-1">Rutas Hoy</p>
        </div>
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-4 text-center">
            <p class="text-2xl font-black text-emerald-600 truncate px-2" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</p>
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mt-1">{{ Auth::user()->role }}</p>
        </div>
    </div>

    @if(count($todayRoutes) > 0)
    <div>
        <h2 class="text-sm font-black text-slate-800 mb-3">Rutas de Hoy</h2>
        <div class="grid grid-cols-1 gap-3">
            @foreach($todayRoutes as $r)
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <div class="min-w-0">
                        <h3 class="text-sm font-black text-slate-900 break-words">{{ $r['client_name'] }}</h3>
                        <p class="text-[10px] text-slate-400 font-semibold break-words">{{ $r['origin'] ?? '' }} &rarr; {{ $r['destination'] ?? '' }}</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider shrink-0
                        @switch($r['status'])
                            @case('Completada') bg-emerald-50 text-emerald-600 @break
                            @case('En tránsito') bg-amber-50 text-amber-600 @break
                            @case('Instalando') bg-indigo-50 text-indigo-600 @break
                            @default bg-blue-50 text-blue-600
                        @endswitch">{{ $r['status'] }}</span>
                </div>
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[10px] text-slate-500 font-semibold mb-3">
                    <span class="flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        {{ $r['vehicle_plate'] ?? '—' }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                        {{ $r['planned_km'] ?? 0 }} km
                    </span>
                </div>
                <a href="{{ route('driver.route', $r['id']) }}" class="block text-center w-full h-11 flex items-center justify-center px-3 rounded-xl bg-[#E72085] text-white text-[10px] font-black hover:bg-[#d01c73] transition">
                    Ver Ruta
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-8 text-center">
        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <p class="text-sm font-bold text-slate-400">No tienes rutas asignadas hoy</p>
        <p class="text-xs text-slate-300 mt-1">Descansa o contacta a tu supervisor</p>
    </div>
    @endif
</div>
