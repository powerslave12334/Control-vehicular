
<div class="bg-white rounded-3xl shadow-xs border border-slate-200 p-6 md:p-8 space-y-6">
    <div class="flex items-start justify-between">
        <div>
            <h2 class="text-lg font-black text-slate-800">Generación de Reportes e Indicadores</h2>
            <p class="text-xs text-gray-500 mt-1">
                {{ match($reportType) {
                    'routes' => 'Rutas y viajes realizados',
                    'fuel' => 'Cargas de combustible',
                    'maintenance' => 'Órdenes de mantenimiento',
                    'incidents' => 'Incidencias reportadas',
                    default => '',
                } }}
                · {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') : '—' }} al {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('d/m/Y') : '—' }}
            </p>
        </div>
        <div class="flex gap-2">
            <button wire:click="exportCsv" class="flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Exportar CSV
            </button>
        </div>
    </div>

    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
        <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Tipo de Reporte</label>
            <select wire:model.live="reportType" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085]">
                <option value="routes">Rutas / Viajes</option>
                <option value="fuel">Combustible</option>
                <option value="maintenance">Mantenimiento</option>
                <option value="incidents">Incidencias</option>
            </select>
        </div>
        <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Fecha Desde</label>
            <input wire:model.live="dateFrom" type="date" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085]">
        </div>
        <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Fecha Hasta</label>
            <input wire:model.live="dateTo" type="date" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085]">
        </div>
        <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Vehículo</label>
            <select wire:model.live="vehicleFilter" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085]">
                <option value="">Todos</option>
                @foreach($vehicles as $v)
                <option value="{{ $v->id }}">{{ $v->plate }} · {{ $v->brand }} {{ $v->model }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Conductor</label>
            <select wire:model.live="operatorFilter" class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs font-bold outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085]">
                <option value="">Todos</option>
                @foreach($operators as $o)
                <option value="{{ $o->id }}">{{ $o->name }}</option>
                @endforeach
            </select>
        </div>
        {{-- <div class="flex items-end">
            <button wire:click="generate" class="w-full flex items-center justify-center gap-1.5 bg-[#E72085] hover:bg-pink-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Generar
            </button>
        </div> --}}
    </div>

    {{-- CHART --}}
    @if(count($reportData) > 5)
    <div class="bg-white rounded-xl border border-slate-100 p-4" wire:ignore>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">
            Tendencia · {{ $totals['count'] ?? count($reportData) }} registros
        </p>
        <div class="relative" style="height: 288px;">
            <canvas id="reportChart"></canvas>
        </div>
    </div>
    @endif

    @push('scripts')
    <script>
    document.addEventListener('livewire:init', () => {
        let chart = null;

        function renderChart(data) {
            const el = document.getElementById('reportChart');
            if (!el) {
                if (chart) { chart.destroy(); chart = null; }
                return;
            }
            if (chart) { chart.destroy(); }

            const labels = data.map(r => r.date || r.date);
            const values = data.map(r => {
                @switch($reportType)
                    @case('routes') return parseFloat(r.planned_km) || 0; @break
                    @case('fuel') return parseFloat(r.liters) || 0; @break
                    @case('maintenance') return parseFloat(r.cost) || 0; @break
                    @case('incidents') return parseFloat(r.cost) || 0; @break
                    @default return 0;
                @endswitch
            });

            chart = new Chart(el, {
                type: 'bar',
                data: {
                    labels: labels.slice(0, 30),
                    datasets: [{
                        label: @switch($reportType)
                            @case('routes') 'Km Planeados' @break
                            @case('fuel') 'Litros' @break
                            @case('maintenance') 'Costo ($)' @break
                            @case('incidents') 'Costo ($)' @break
                            @default ''
                        @endswitch,
                        data: values.slice(0, 30),
                        backgroundColor: '#E7208520',
                        borderColor: '#E72085',
                        borderWidth: 1.5,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { ticks: { font: { size: 9 }, maxRotation: 45 } },
                        y: { beginAtZero: true, ticks: { font: { size: 9 } } }
                    }
                }
            });
        }

        const data = @js($reportData);
        if (data.length > 5) renderChart(data);

        Livewire.on('chart:refresh', (event) => {
            const fresh = event?.data ?? [];
            if (fresh.length > 5) {
                renderChart(fresh);
            } else if (chart) {
                chart.destroy();
                chart = null;
            }
        });
    });
    </script>
    @endpush

    {{-- TOTALS --}}
    @if(count($totals) > 0)
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
        @foreach($totals as $key => $val)
        <div class="bg-white rounded-xl border border-slate-100 p-4">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ str_replace('_', ' ', $key) }}</p>
            <p class="text-lg font-black text-slate-800 mt-1">
                @if(str_contains($key, 'amount') || str_contains($key, 'cost') || $key === 'avg_price' || $key === 'cost_total')
                    ${{ number_format($val, 2) }}
                @elseif(str_contains($key, 'km'))
                    {{ number_format($val, 1) }} km
                @elseif($key === 'liters')
                    {{ number_format($val, 2) }} L
                @else
                    {{ $val }}
                @endif
            </p>
        </div>
        @endforeach
    </div>
    @endif

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-gray-600">
            <thead>
                <tr class="bg-slate-50 text-gray-400 font-bold uppercase tracking-wider">
                    @if($reportType === 'routes')
                    <th class="px-4 py-3 rounded-l-lg">Fecha</th>
                    <th class="px-4 py-3">Cliente</th>
                    <th class="px-4 py-3">Conductor</th>
                    <th class="px-4 py-3">Unidad</th>
                    <th class="px-4 py-3">Ciudad</th>
                    <th class="px-4 py-3 text-right">Km Plan</th>
                    <th class="px-4 py-3 text-right">Km Real</th>
                    <th class="px-4 py-3 rounded-r-lg text-center">Estado</th>
                    @elseif($reportType === 'fuel')
                    <th class="px-4 py-3 rounded-l-lg">Fecha</th>
                    <th class="px-4 py-3">Vehículo</th>
                    <th class="px-4 py-3">Conductor</th>
                    <th class="px-4 py-3">Ruta</th>
                    <th class="px-4 py-3 text-right">Litros</th>
                    <th class="px-4 py-3 text-right">Monto</th>
                    <th class="px-4 py-3 text-right">Precio/L</th>
                    <th class="px-4 py-3">Método</th>
                    <th class="px-4 py-3 text-right">Odómetro</th>
                    @elseif($reportType === 'maintenance')
                    <th class="px-4 py-3 rounded-l-lg">Fecha</th>
                    <th class="px-4 py-3">Vehículo</th>
                    <th class="px-4 py-3">Tipo</th>
                    <th class="px-4 py-3">Categoría</th>
                    <th class="px-4 py-3">Descripción</th>
                    <th class="px-4 py-3 text-right">Costo</th>
                    <th class="px-4 py-3">Taller</th>
                    @elseif($reportType === 'incidents')
                    <th class="px-4 py-3 rounded-l-lg">Fecha</th>
                    <th class="px-4 py-3">Vehículo</th>
                    <th class="px-4 py-3">Conductor</th>
                    <th class="px-4 py-3">Tipo</th>
                    <th class="px-4 py-3">Descripción</th>
                    <th class="px-4 py-3">Severidad</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3 text-right">Costo</th>
                    <th class="px-4 py-3">Ubicación</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reportData as $row)
                <tr class="hover:bg-slate-50/50">
                    @if($reportType === 'routes')
                    <td class="px-4 py-3 whitespace-nowrap">{{ $row['date'] }}</td>
                    <td class="px-4 py-3 font-bold text-slate-800">{{ $row['client'] }}</td>
                    <td class="px-4 py-3">{{ $row['driver'] }}</td>
                    <td class="px-4 py-3">{{ $row['vehicle'] }}</td>
                    <td class="px-4 py-3">{{ $row['city'] }}</td>
                    <td class="px-4 py-3 text-right">{{ number_format((float)($row['planned_km'] ?? 0), 1) }}</td>
                    <td class="px-4 py-3 text-right">{{ number_format((float)($row['actual_km'] ?? 0), 1) }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold
                            @switch($row['status'])
                                @case(\App\Domains\Route\Enums\RouteStatusEnum::Completada->value) bg-green-100 text-green-800 @break
                                @case(\App\Domains\Route\Enums\RouteStatusEnum::EnTransito->value) bg-amber-100 text-amber-800 @break
                                @case(\App\Domains\Route\Enums\RouteStatusEnum::Instalando->value) bg-indigo-100 text-indigo-800 @break
                                @default bg-slate-100 text-slate-600
                            @endswitch">{{ $row['status'] }}</span>
                    </td>
                    @elseif($reportType === 'fuel')
                    <td class="px-4 py-3 whitespace-nowrap">{{ $row['date'] }}</td>
                    <td class="px-4 py-3 font-bold text-slate-800">{{ $row['vehicle'] }}</td>
                    <td class="px-4 py-3">{{ $row['driver'] }}</td>
                    <td class="px-4 py-3 text-slate-500 max-w-[120px] truncate">{{ $row['route_client'] }}</td>
                    <td class="px-4 py-3 text-right">{{ number_format((float)($row['liters'] ?? 0), 2) }}</td>
                    <td class="px-4 py-3 text-right">${{ number_format((float)($row['amount'] ?? 0), 2) }}</td>
                    <td class="px-4 py-3 text-right">${{ number_format((float)($row['price_per_liter'] ?? 0), 2) }}</td>
                    <td class="px-4 py-3">{{ $row['payment_method'] }}</td>
                    <td class="px-4 py-3 text-right">{{ $row['odometer'] ? number_format((float)$row['odometer']) . ' km' : '—' }}</td>
                    @elseif($reportType === 'maintenance')
                    <td class="px-4 py-3 whitespace-nowrap">{{ $row['date'] }}</td>
                    <td class="px-4 py-3 font-bold text-slate-800">{{ $row['vehicle'] }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $row['type'] === 'Preventivo' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800' }}">{{ $row['type'] }}</span>
                    </td>
                    <td class="px-4 py-3">{{ $row['category'] }}</td>
                    <td class="px-4 py-3 max-w-[200px] truncate">{{ $row['description'] }}</td>
                    <td class="px-4 py-3 text-right">${{ number_format((float)($row['cost'] ?? 0), 2) }}</td>
                    <td class="px-4 py-3">{{ $row['workshop'] }}</td>
                    @elseif($reportType === 'incidents')
                    <td class="px-4 py-3 whitespace-nowrap">{{ $row['date'] }} {{ $row['time'] }}</td>
                    <td class="px-4 py-3 font-bold text-slate-800">{{ $row['vehicle'] }}</td>
                    <td class="px-4 py-3">{{ $row['driver'] }}</td>
                    <td class="px-4 py-3">{{ $row['type'] }}</td>
                    <td class="px-4 py-3 max-w-[200px] truncate" title="{{ $row['description'] }}">{{ $row['description'] }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold whitespace-nowrap
                            @switch($row['severity'])
                                @case('Alta') bg-red-100 text-red-800 @break
                                @case('Media') bg-amber-100 text-amber-800 @break
                                @case('Baja') bg-blue-100 text-blue-800 @break
                                @case('Crítica') bg-purple-100 text-purple-800 @break
                                @default bg-slate-100 text-slate-600
                            @endswitch">{{ $row['severity'] }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold whitespace-nowrap
                            @switch($row['status'])
                                @case('Resuelta') bg-green-100 text-green-800 @break
                                @case('Cerrada') bg-gray-100 text-gray-600 @break
                                @case('Atendida') bg-amber-100 text-amber-800 @break
                                @default bg-yellow-100 text-yellow-800
                            @endswitch">{{ $row['status'] }}</span>
                    </td>
                    <td class="px-4 py-3 text-right">{{ $row['cost'] > 0 ? '$'.number_format($row['cost'], 2) : '—' }}</td>
                    <td class="px-4 py-3">{{ $row['location'] }}</td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="10" class="text-center py-12 text-gray-400 text-sm font-bold">Sin datos para el período seleccionado</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
