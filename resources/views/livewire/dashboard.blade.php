
<div id="dashboard-stats" class="space-y-6">

    {{-- KPI TOP ROW --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- Km Planeados vs Reales --}}
        <div
            class="bg-white rounded-2xl shadow-xs border-l-4 border-[#F71F96] border-y border-r border-slate-200/80 p-5 relative overflow-hidden group hover:shadow-md transition-all">
            <div
                class="absolute top-0 right-0 w-24 h-24 -mr-6 -mt-6 bg-[#F71F96]/10 rounded-full group-hover:scale-110 transition-transform">
            </div>
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block">KPI
                        Operativo</span>
                    <h3 class="text-sm font-black text-slate-700 mt-1 uppercase tracking-tight">Km Planeados vs Reales
                    </h3>
                    <p class="text-3xl font-black text-slate-950 mt-2">{{ number_format($kpi['kmPlannedVsReal'], 1) }}%
                    </p>
                    <span class="text-xs font-mono text-slate-400 block mt-1">(Km Reales / Km Planeados) × 100</span>
                </div>
                <div class="p-2.5 bg-[#F71F96]/10 rounded-xl text-[#F71F96] shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                        <polyline points="17 6 23 6 23 12" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 relative z-10">
                <div class="flex justify-between text-xs text-slate-500 font-bold mb-1">
                    <span>Reales: {{ number_format($kpi['totalActualKm']) }} km</span>
                    <span>Planes: {{ number_format($kpi['totalPlannedKm']) }} km</span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-[#F71F96] h-full rounded-full transition-all duration-500"
                        style="width: {{ min($kpi['kmPlannedVsReal'], 100) }}%"></div>
                </div>
            </div>
        </div>

        {{-- Rendimiento Promedio --}}
        <div
            class="bg-white rounded-2xl shadow-xs border-l-4 border-[#1F96F7] border-y border-r border-slate-200/80 p-5 relative overflow-hidden group hover:shadow-md transition-all">
            <div
                class="absolute top-0 right-0 w-24 h-24 -mr-6 -mt-6 bg-[#1F96F7]/10 rounded-full group-hover:scale-110 transition-transform">
            </div>
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <span
                        class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block">Rendimiento</span>
                    <h3 class="text-sm font-black text-slate-700 mt-1 uppercase tracking-tight">Rendimiento Promedio
                    </h3>
                    <p class="text-3xl font-black text-slate-950 mt-2">
                        {{ $kpi['fuelEfficiency'] > 0 ? number_format($kpi['fuelEfficiency'], 2) . ' km/L' : 'N/D' }}
                    </p>
                    <span class="text-xs font-mono text-slate-400 block mt-1">Km Reales / Litros Consumidos</span>
                </div>
                <div class="p-2.5 bg-[#1F96F7]/10 rounded-xl text-[#1F96F7] shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 relative z-10">
                <div class="flex justify-between text-xs text-slate-500 font-bold mb-1">
                    <span>Consumo: {{ number_format($kpi['totalLitersConsumed']) }} L</span>
                    <span>Costo: ${{ number_format($kpi['totalFuelCost']) }}</span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-[#1F96F7] h-full rounded-full transition-all duration-500"
                        style="width: {{ min($kpi['fuelEfficiency'] * 10, 100) }}%"></div>
                </div>
            </div>
        </div>

        {{-- Combustible Consumido --}}
        <div
            class="bg-white rounded-2xl shadow-xs border-l-4 border-[#F71F96] border-y border-r border-slate-200/80 p-5 relative overflow-hidden group hover:shadow-md transition-all">
            <div
                class="absolute top-0 right-0 w-24 h-24 -mr-6 -mt-6 bg-[#F71F96]/10 rounded-full group-hover:scale-110 transition-transform">
            </div>
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <span
                        class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block">Presupuesto</span>
                    <h3 class="text-sm font-black text-slate-700 mt-1 uppercase tracking-tight">Combustible Consumido
                    </h3>
                    <p class="text-3xl font-black text-slate-950 mt-2">
                        {{ number_format($kpi['authorizedVsConsumed'], 1) }}%</p>
                    <span class="text-xs font-mono text-slate-400 block mt-1">Consumo Real / Autorizado × 100</span>
                </div>
                <div class="p-2.5 bg-[#F71F96]/10 rounded-xl text-[#F71F96] shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 15v2m0 0v3m0-3h3m-3 0H9m3.5-11.5a9 9 0 1 0 0 17 9 9 0 0 0 0-17z" />
                        <path d="M12 6v6l3 2" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 relative z-10">
                <div class="flex justify-between text-xs text-slate-500 font-bold mb-1">
                    <span>Real: {{ number_format($kpi['totalLitersConsumed']) }} L</span>
                    <span>Autorizado: {{ number_format($kpi['totalWeeklyAuthorizedFuel']) }} L</span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500 {{ $kpi['authorizedVsConsumed'] > 100 ? 'bg-red-500' : 'bg-emerald-500' }}"
                        style="width: {{ min($kpi['authorizedVsConsumed'], 100) }}%"></div>
                </div>
            </div>
        </div>

        {{-- Costo por Kilómetro --}}
        <div
            class="bg-gradient-to-br from-[#F71F96] to-[#B90F6C] rounded-2xl shadow-lg shadow-[#F71F96]/20 p-5 relative overflow-hidden group hover:shadow-xl transition-all text-white">
            <div
                class="absolute top-0 right-0 w-24 h-24 -mr-6 -mt-6 bg-white/10 rounded-full group-hover:scale-110 transition-transform">
            </div>
            <div class="flex items-start justify-between relative z-10">
                <div>
                    <span class="text-xs font-bold text-white/80 uppercase tracking-wider block">Eficiencia de
                        Costo</span>
                    <h3 class="text-sm font-black text-white mt-1 uppercase tracking-tight">Costo por Kilómetro</h3>
                    <p class="text-3xl font-black text-white mt-2">${{ number_format($kpi['costPerKm'], 2) }} <span
                            class="text-xs font-semibold text-white/70">MXN/Km</span></p>
                    <span class="text-xs font-mono text-white/80 block mt-1">Gasto Combustible / Km Reales</span>
                </div>
                <div class="p-2.5 bg-white/15 rounded-xl text-white shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23" />
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 relative z-10">
                <div class="flex justify-between text-xs text-white/90 font-bold mb-1">
                    <span>Gasto Comb.: ${{ number_format($kpi['totalFuelCost']) }}</span>
                    <span>Km Reales: {{ number_format($kpi['totalActualKm']) }}</span>
                </div>
                <div class="w-full bg-white/20 h-2 rounded-full overflow-hidden">
                    <div class="bg-white h-full rounded-full transition-all duration-500"
                        style="width: {{ min(($kpi['costPerKm'] / 50) * 100, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- FLOTA + BOTTOM KPIs ROW --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Fleet Distribution Pie --}}
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-slate-700 uppercase tracking-tight">Distribución de Flota</h3>
                <span class="text-xs font-bold text-slate-400">{{ $stats['totalVehicles'] }} unidades</span>
            </div>
            @if(count($fleetPieData) > 0)
            <div class="relative h-72 w-72 max-w-full mx-auto">
                <canvas id="fleetChart"></canvas>
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none"
                    style="margin-top: 8px;">
                    <div class="text-center">
                        <span class="text-slate-900 font-black"
                            style="font-size: 26px;">{{ $stats['totalVehicles'] }}</span>
                        <br>
                        <span class="text-slate-400" style="font-size: 12px;">Total</span>
                    </div>
                </div>
            </div>
            @else
            <p class="text-xs text-slate-400 text-center py-12">Sin datos</p>
            @endif
        </div>
        

        {{-- BOTTOM KPIs CONTAINER --}}
        <div class="grid grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-5 hover:shadow-md transition-all">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-[#1FF780]/15 text-[#0A9A54] rounded-xl shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Productividad</h4>
                        <h3 class="text-sm font-black text-slate-700 uppercase tracking-tight">Cumplimiento de
                            Instalaciones</h3>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span
                        class="text-3xl font-black text-slate-950">{{ number_format($stats['installationCompliance'], 1) }}%</span>
                    <span class="text-xs text-[#0A9A54] font-bold">(Meta: 90%)</span>
                </div>
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between text-xs text-slate-500 font-semibold">
                        <span>Realizadas:</span><span
                            class="font-bold text-slate-800">{{ $stats['completedInstallations'] }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-slate-500 font-semibold">
                        <span>Programadas:</span><span
                            class="font-bold text-slate-800">{{ $stats['totalInstallations'] }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-5 hover:shadow-md transition-all">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-[#1F96F7]/10 text-[#1F96F7] rounded-xl shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Estado de Flota
                        </h4>
                        <h3 class="text-sm font-black text-slate-700 uppercase tracking-tight">Disponibilidad de Flota
                        </h3>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span
                        class="text-3xl font-black text-slate-950">{{ number_format($stats['fleetAvailability'], 1) }}%</span>
                    <span
                        class="text-xs text-[#1F96F7] font-bold">({{ $stats['activeCount'] }}/{{ $stats['totalVehicles'] }}
                        Unidades)</span>
                </div>
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between text-xs text-slate-500 font-semibold">
                        <span>En mantenimiento:</span><span
                            class="font-bold text-amber-600">{{ number_format($stats['inMaintenancePct'], 1) }}%</span>
                    </div>
                    <div class="flex justify-between text-xs text-slate-500 font-semibold">
                        <span>Fuera de servicio:</span><span
                            class="font-bold text-red-500">{{ $stats['outOfServiceCount'] }} unidades</span>
                    </div>
                </div>
            </div>

             <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-5 hover:shadow-md transition-all">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-[#F71F96]/10 text-[#F71F96] rounded-xl shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Finanzas Flota</h4>
                        <h3 class="text-sm font-black text-slate-700 uppercase tracking-tight">Costos Operativos</h3>
                    </div>
                </div>

                <div class="flex items-baseline gap-2 mb-2">
                    <span class="text-3xl font-black text-slate-950">${{ number_format($stats['totalCost']) }}</span>
                    <span class="text-xs text-[#F71F96] font-bold">MXN</span>
                </div>
                
                <div class="grid grid-cols-2 gap-2 text-xs font-bold">
                    <div class="flex items-center gap-1.5">
                        <div class="w-2 h-2 rounded-full bg-[#F71F96] shrink-0"></div>
                        <span>Combustible: <span
                                class="text-slate-800">${{ number_format($stats['totalFuelCost']) }}</span></span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-2 h-2 rounded-full bg-[#1F96F7] shrink-0"></div>
                        <span>Mantenimiento: <span
                                class="text-slate-800">${{ number_format($stats['totalMaintenanceCost']) }}</span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHARTS ROW (final) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Planned vs Actual Km Bar Chart --}}
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-slate-700 uppercase tracking-tight">Km por Ruta</h3>
                <span class="text-xs font-bold text-slate-400">{{ count($kmChartData) }} rutas</span>
            </div>
            @if(count($kmChartData) > 0)
            <div class="h-72">
                <canvas id="kmChart"></canvas>
            </div>
            @else
            <p class="text-xs text-slate-400 text-center py-12">Sin datos de rutas</p>
            @endif
        </div>

        {{-- Fuel Chart --}}
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-slate-700 uppercase tracking-tight">Combustible por Unidad</h3>
                <span class="text-xs font-bold text-slate-400">{{ count($fuelChartData) }} vehículos</span>
            </div>
            @if(count($fuelChartData) > 0)
            <div class="h-72">
                <canvas id="fuelChart"></canvas>
            </div>
            @else
            <p class="text-xs text-slate-400 text-center py-12">Sin datos de combustible</p>
            @endif
        </div>
    </div>

</div>

@push('scripts')
<script>
    function initDashboardCharts() {
        if (typeof Chart === 'undefined') {
            return setTimeout(initDashboardCharts, 30);
        }

        const fleetPieData = @js($fleetPieData);
        const kmChartData = @js($kmChartData);
        const fuelChartData = @js($fuelChartData);
        const stats = @js($stats);

        // Fleet Pie
        const fleetCtx = document.getElementById('fleetChart');
        if (fleetCtx && fleetPieData.length > 0) {
            new Chart(fleetCtx, {
                type: 'doughnut',
                data: {
                    labels: fleetPieData.map(d => d.name),
                    datasets: [{
                        data: fleetPieData.map(d => d.value),
                        backgroundColor: fleetPieData.map(d => d.color),
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 12,
                                    weight: '600'
                                },
                                boxWidth: 12,
                                padding: 12,
                                color: '#475569'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (ctx) {
                                    return ctx.parsed + ' vehículo' + (ctx.parsed !== 1 ? 's' : '');
                                },
                                afterLabel: function (ctx) {
                                    return ((ctx.parsed / stats.totalVehicles) * 100).toFixed(1) +
                                        '% del total';
                                }
                            }
                        }
                    },
                    cutout: '60%',
                }
            });
        }

        // Km Bar Chart
        const kmCtx = document.getElementById('kmChart');
        if (kmCtx && kmChartData.length > 0) {
            new Chart(kmCtx, {
                type: 'bar',
                data: {
                    labels: kmChartData.map(d => d.name),
                    datasets: [{
                            label: 'Planeados',
                            data: kmChartData.map(d => d.Planeados),
                            backgroundColor: '#1F96F7',
                            borderRadius: 4,
                            barPercentage: 0.7,
                        },
                        {
                            label: 'Reales',
                            data: kmChartData.map(d => d.Reales),
                            backgroundColor: '#F71F96',
                            borderRadius: 4,
                            barPercentage: 0.7,
                        },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function (items) {
                                    return items[0].label;
                                },
                                afterBody: function (items) {
                                    const d = kmChartData[items[0].dataIndex];
                                    let lines = [];
                                    if (d.city) lines.push('Destino: ' + d.city);
                                    lines.push('Diferencia: ' + (d.diff > 0 ? '+' : '') + d.diff +
                                        ' km');
                                    lines.push('Cumplimiento: ' + d.pct + '%');
                                    return lines;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    datasets: {
                        bar: {
                            maxBarThickness: 28
                        },
                    }
                }
            });
        }

        // Fuel Bar Chart
        const fuelCtx = document.getElementById('fuelChart');
        if (fuelCtx && fuelChartData.length > 0) {
            new Chart(fuelCtx, {
                type: 'bar',
                data: {
                    labels: fuelChartData.map(d => d.name),
                    datasets: [{
                            label: 'Consumido',
                            data: fuelChartData.map(d => d.Consumido),
                            backgroundColor: '#F71F96',
                            borderRadius: 4,
                            barPercentage: 0.7,
                        },
                        {
                            label: 'Autorizado',
                            data: fuelChartData.map(d => d.Autorizado),
                            backgroundColor: '#1F96F7',
                            borderRadius: 4,
                            barPercentage: 0.7,
                        },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function (items) {
                                    return items[0].label;
                                },
                                afterBody: function (items) {
                                    const d = fuelChartData[items[0].dataIndex];
                                    let lines = [];
                                    if (d.model) lines.push(d.model);
                                    const consumed = d.Consumido;
                                    const authorized = d.Autorizado;
                                    const pct = authorized > 0 ? (consumed / authorized) * 100 : 0;
                                    lines.push('Uso: ' + pct.toFixed(1) + '% ' + (pct > 100 ?
                                        '(Excedido)' : '(Dentro del límite)'));
                                    return lines;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    datasets: {
                        bar: {
                            maxBarThickness: 28
                        },
                    }
                }
            });

            const chart = Chart.getChart(fuelCtx);
            if (chart) {
                const originalDraw = chart.draw;
                chart.draw = function () {
                    originalDraw.call(this);
                    const ctx = this.ctx;
                    this.data.datasets.forEach((ds, dsIdx) => {
                        const meta = this.getDatasetMeta(dsIdx);
                        const color = dsIdx === 0 ? '#F71F96' : '#1F96F7';
                        meta.data.forEach((bar, idx) => {
                            const value = ds.data[idx];
                            ctx.fillStyle = color;
                            ctx.font = '700 11px sans-serif';
                            ctx.textAlign = 'center';
                            ctx.fillText(value, bar.x, bar.y - 6);
                        });
                    });
                };
                chart.draw();
            }
        }
    }

    initDashboardCharts();

</script>
@endpush
