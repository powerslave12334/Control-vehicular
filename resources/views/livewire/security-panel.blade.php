<div>
    <div class="bg-white rounded-3xl shadow-xs border border-slate-200 p-6 md:p-8 space-y-6">
        <div x-data="{ tab: '{{ $activeTab }}' }">
            <div class="flex gap-1 mb-6 border-b border-slate-200">
                <button @click="tab = 'seguridad'" :class="tab === 'seguridad' ? 'border-[#E72085] text-[#E72085]' : 'border-transparent text-slate-400 hover:text-slate-600'" class="px-4 py-2.5 text-xs font-black uppercase tracking-wider border-b-2 transition">
                    <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Seguridad
                </button>
                <button @click="tab = 'actividad'; $wire.loadActivityLogs()" :class="tab === 'actividad' ? 'border-[#E72085] text-[#E72085]' : 'border-transparent text-slate-400 hover:text-slate-600'" class="px-4 py-2.5 text-xs font-black uppercase tracking-wider border-b-2 transition">
                    <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Actividad
                </button>
                <button @click="tab = 'sistema'; $wire.loadSystemLogs()" :class="tab === 'sistema' ? 'border-[#E72085] text-[#E72085]' : 'border-transparent text-slate-400 hover:text-slate-600'" class="px-4 py-2.5 text-xs font-black uppercase tracking-wider border-b-2 transition">
                    <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Logs del Sistema
                </button>
            </div>

            {{-- TAB: SEGURIDAD --}}
            <div x-show="tab === 'seguridad'" x-cloak>
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <h2 class="text-lg font-black text-slate-800">Seguridad OWASP Top 10</h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="runChecks" class="px-4 py-2 rounded-xl bg-[#008FD3] text-white text-xs font-black uppercase tracking-wider hover:bg-[#007bb8] transition shadow-md">
                            <svg class="w-3.5 h-3.5 inline mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Re-escanear
                        </button>
                        <span class="rounded-full px-3 py-1 text-xs font-bold flex items-center gap-1 {{ $overallStatus === 'Seguro' ? 'bg-emerald-50 text-emerald-700' : ($overallStatus === 'Precaución' ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                            {{ $overallStatus }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($checks as $check)
                    <div class="bg-white rounded-xl border border-gray-100 p-4 hover:shadow-sm transition">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center gap-2">
                                @if($check['pass'])
                                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @else
                                <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                @endif
                                <h3 class="text-sm font-black text-slate-900">{{ $check['name'] }}</h3>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $check['badge'] === 'Activo' || $check['badge'] === 'Seguro' ? 'bg-emerald-50 text-emerald-600' : ($check['badge'] === 'Faltante' || $check['badge'] === '¡Crítico!' ? 'bg-red-50 text-red-600' : 'bg-slate-100 text-slate-500') }}">
                                {{ $check['badge'] }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 font-semibold leading-relaxed">{{ $check['detail'] }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="bg-slate-50 rounded-xl p-5 mt-4">
                    <h3 class="text-sm font-black text-gray-800 mb-4">Recomendaciones Adicionales</h3>
                    <ul class="space-y-2 text-xs text-slate-500 font-semibold">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-[#E72085] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                            <span>Usar HTTPS en producción forzado via middleware o .env FORCE_HTTPS=true.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-[#E72085] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                            <span>Implementar 2FA para cuentas administrativas.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-[#E72085] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                            <span>Rotar API keys y secrets periódicamente.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-[#E72085] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                            <span>Auditar logs de acceso regularmente con monitoreo de intentos fallidos.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-[#E72085] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                            <span>Mantener dependencias actualizadas (<code class="px-1 bg-white rounded text-[10px]">composer audit</code>).</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- TAB: ACTIVIDAD --}}
            <div x-show="tab === 'actividad'" x-cloak>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-black text-slate-800">Registro de Actividad</h2>
                        <p class="text-xs text-slate-400 font-bold mt-0.5">{{ $activityTotal }} eventos registrados</p>
                    </div>
                    <button wire:click="loadActivityLogs" class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-wider hover:bg-slate-200 transition">
                        <svg class="w-3 h-3 inline mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Recargar
                    </button>
                </div>

                <div class="flex gap-2 flex-wrap mb-4">
                    <select wire:model.live="eventFilter" class="bg-white border border-gray-200 rounded-lg p-2 text-xs outline-none focus:ring-2 focus:ring-[#E72085]/20">
                        <option value="">Todos los eventos</option>
                        <option value="created">Creado</option>
                        <option value="updated">Actualizado</option>
                        <option value="deleted">Eliminado</option>
                        <option value="restored">Restaurado</option>
                        <option value="status_changed">Cambio de estado</option>
                    </select>
                    <input wire:model.live="subjectFilter" placeholder="Buscar entidad..." class="bg-white border border-gray-200 rounded-lg p-2 text-xs outline-none focus:ring-2 focus:ring-[#E72085]/20 w-48">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-600">
                        <thead>
                            <tr class="bg-slate-50 text-gray-400 font-bold uppercase tracking-wider">
                                <th class="px-3 py-2.5 rounded-l-lg">Fecha/Hora</th>
                                <th class="px-3 py-2.5">Usuario</th>
                                <th class="px-3 py-2.5">Evento</th>
                                <th class="px-3 py-2.5">Descripción</th>
                                <th class="px-3 py-2.5 rounded-r-lg">Entidad</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($activityLogs as $log)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-3 py-2.5 whitespace-nowrap font-mono text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}</td>
                                <td class="px-3 py-2.5 font-bold text-slate-700">{{ $log->causer_name ?? '—' }}</td>
                                <td class="px-3 py-2.5">
                                    @php
                                        $eventColors = [
                                            'created' => 'bg-emerald-100 text-emerald-800',
                                            'updated' => 'bg-blue-100 text-blue-800',
                                            'deleted' => 'bg-red-100 text-red-800',
                                            'restored' => 'bg-purple-100 text-purple-800',
                                            'status_changed' => 'bg-amber-100 text-amber-800',
                                        ];
                                        $eventLabels = [
                                            'created' => 'Creado',
                                            'updated' => 'Actualizado',
                                            'deleted' => 'Eliminado',
                                            'restored' => 'Restaurado',
                                            'status_changed' => 'Cambio Estado',
                                        ];
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $eventColors[$log->event] ?? 'bg-slate-100 text-slate-600' }}">
                                        {{ $eventLabels[$log->event] ?? $log->event }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 max-w-xs truncate font-semibold text-slate-600" title="{{ $log->description }}">{{ $log->description }}</td>
                                <td class="px-3 py-2.5">
                                    @php
                                        $classParts = explode('\\', $log->subject_type);
                                        $entityName = end($classParts);
                                    @endphp
                                    <span class="text-[10px] font-bold text-slate-400">{{ $entityName }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-10 text-slate-400 text-sm font-bold">No hay actividad registrada</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @php $totalPages = max(1, (int) ceil($activityTotal / $activityPerPage)); @endphp
                @if($totalPages > 1)
                <div class="flex items-center justify-center gap-2 mt-4">
                    <button wire:click="gotoActivityPage(1)" class="px-2 py-1 rounded text-xs font-bold {{ $activityPage === 1 ? 'text-slate-300 cursor-not-allowed' : 'text-slate-500 hover:bg-slate-100' }}" {{ $activityPage === 1 ? 'disabled' : '' }}>&laquo;</button>
                    <button wire:click="gotoActivityPage({{ $activityPage - 1 }})" class="px-2 py-1 rounded text-xs font-bold {{ $activityPage <= 1 ? 'text-slate-300 cursor-not-allowed' : 'text-slate-500 hover:bg-slate-100' }}" {{ $activityPage <= 1 ? 'disabled' : '' }}>&lsaquo;</button>
                    @for($i = max(1, $activityPage - 2); $i <= min($totalPages, $activityPage + 2); $i++)
                    <button wire:click="gotoActivityPage({{ $i }})" class="px-2.5 py-1 rounded text-xs font-bold {{ $i === $activityPage ? 'bg-[#E72085] text-white' : 'text-slate-500 hover:bg-slate-100' }}">{{ $i }}</button>
                    @endfor
                    <button wire:click="gotoActivityPage({{ $activityPage + 1 }})" class="px-2 py-1 rounded text-xs font-bold {{ $activityPage >= $totalPages ? 'text-slate-300 cursor-not-allowed' : 'text-slate-500 hover:bg-slate-100' }}" {{ $activityPage >= $totalPages ? 'disabled' : '' }}>&rsaquo;</button>
                    <button wire:click="gotoActivityPage({{ $totalPages }})" class="px-2 py-1 rounded text-xs font-bold {{ $activityPage === $totalPages ? 'text-slate-300 cursor-not-allowed' : 'text-slate-500 hover:bg-slate-100' }}" {{ $activityPage === $totalPages ? 'disabled' : '' }}>&raquo;</button>
                </div>
                @endif
            </div>

            {{-- TAB: LOGS DEL SISTEMA --}}
            <div x-show="tab === 'sistema'" x-cloak>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-black text-slate-800">Logs del Sistema</h2>
                        <p class="text-xs text-slate-400 font-bold mt-0.5">
                            {{ $logFileLines !== null ? number_format($logFileLines) . ' líneas' : '—' }}
                            {{ $logFileSize !== null && $logFileSize > 0 ? '· ' . number_format($logFileSize / 1024, 1) . ' KB' : '' }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="clearLogs" class="px-3 py-1.5 rounded-xl bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-wider hover:bg-red-100 transition" onclick="return confirm('¿Vaciar el archivo laravel.log?')">Limpiar Log</button>
                        <button wire:click="loadSystemLogs" class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-wider hover:bg-slate-200 transition">Recargar</button>
                    </div>
                </div>

                <div class="flex gap-2 flex-wrap mb-4">
                    @php $levels = ['', 'ERROR', 'WARNING', 'INFO', 'DEBUG', 'CRITICAL', 'NOTICE', 'ALERT', 'EMERGENCY']; @endphp
                    @foreach($levels as $lv)
                    <button wire:click="filterByLevel('{{ $lv }}')" type="button"
                        class="px-2.5 py-1 rounded-lg text-[10px] font-bold transition
                        {{ $logLevelFilter === $lv ? 'bg-[#E72085] text-white shadow-md' : 'bg-white border border-gray-200 text-slate-500 hover:bg-slate-50' }}">
                        {{ $lv ?: 'Todos' }}
                    </button>
                    @endforeach
                </div>

                <div class="space-y-2 max-h-[600px] overflow-y-auto">
                    @forelse($systemLogs as $log)
                    <div x-data="{ expanded: false }" class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <button @click="expanded = !expanded" class="w-full px-4 py-2.5 flex items-center gap-3 hover:bg-slate-50/50 transition text-left">
                            <span class="font-mono text-[10px] text-slate-400 shrink-0 w-36">{{ $log['timestamp'] }}</span>
                            @php
                                $levelColors = [
                                    'ERROR' => 'bg-red-100 text-red-700',
                                    'CRITICAL' => 'bg-red-200 text-red-800',
                                    'WARNING' => 'bg-amber-100 text-amber-700',
                                    'NOTICE' => 'bg-blue-100 text-blue-700',
                                    'INFO' => 'bg-emerald-100 text-emerald-700',
                                    'DEBUG' => 'bg-slate-100 text-slate-600',
                                    'ALERT' => 'bg-purple-100 text-purple-700',
                                    'EMERGENCY' => 'bg-red-200 text-red-900',
                                ];
                            @endphp
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase shrink-0 {{ $levelColors[$log['level']] ?? 'bg-slate-100 text-slate-500' }}">
                                {{ $log['level'] }}
                            </span>
                            <span class="text-xs font-semibold text-slate-700 truncate flex-1">{{ $log['message'] }}</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 transition" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        @if($log['trace'])
                        <div x-show="expanded" x-collapse class="border-t border-gray-50 bg-slate-50 px-4 py-3">
                            <pre class="text-[10px] font-mono text-slate-500 leading-relaxed whitespace-pre-wrap max-h-64 overflow-y-auto">{{ $log['trace'] }}</pre>
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-10 text-slate-400 text-sm font-bold">
                        {{ $logFileLines > 0 ? 'No hay entradas para este nivel de log.' : 'El archivo de log está vacío.' }}
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
