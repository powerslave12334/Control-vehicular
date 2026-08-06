<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Agua Inmaculada') }} - Control de Flota</title>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="min-h-screen bg-[#e3e5e9] font-sans text-slate-800 flex flex-col md:flex-row selection:bg-[#F71F96]/20 selection:text-[#F71F96]">

    @auth
        {{-- SIDEBAR DESKTOP --}}
        <aside class="hidden md:flex w-64 bg-white border-r border-slate-200 flex-col shadow-xs sticky top-0 h-screen">
            <div class="p-1 border-b border-slate-100 shrink-0" style="justify-items: center;">
                <img src="{{ asset('img/logo.png') }}" alt="Agua Inmaculada"
                    class="h-15 w-auto object-contain">
                {{-- <p class="mt-1.5 text-[10px] text-slate-500 font-bold uppercase tracking-wider">Control de Flota</p> --}}
            </div>

            <div class="px-4 py-3 border-t border-slate-100 flex-1 overflow-y-auto min-h-0">
                @php $userModules = Auth::user()->modules->keyBy('id'); @endphp
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider px-4 pt-2 pb-2">Navegación Admin</p>
                <nav class="space-y-0.5">
                    @if($userModules->has('dashboard'))
                        <x-nav-item :route="'dashboard'" :label="'Dashboard / Resumen'" :icon="'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'" />
                    @endif

                    @if($userModules->has('vehicles') || $userModules->has('maintenance') || $userModules->has('fuel'))
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-4 pt-3 pb-1">Vehículos</p>
                    @endif
                    @if($userModules->has('vehicles'))
                        <x-nav-item :route="'vehicles'" :label="'Vehículos'" :icon="'M13 10V3L4 14h7v7l9-11h-7z'" />
                    @endif
                    @if($userModules->has('maintenance'))
                        <x-nav-item :route="'maintenance'" :label="'Mantenimiento'" :icon="'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'" />
                    @endif
                    @if($userModules->has('fuel'))
                        <x-nav-item :route="'fuel'" :label="'Combustible'" :icon="'M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z'" />
                    @endif

                    @if($userModules->has('routes') || $userModules->has('geolocation') || $userModules->has('calendar'))
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-4 pt-3 pb-1">Rutas</p>
                    @endif
                    @if($userModules->has('routes'))
                        <x-nav-item :route="'routes'" :label="'Rutas'" :icon="'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'" />
                    @endif
                    @if($userModules->has('geolocation'))
                        <x-nav-item :route="'geolocation'" :label="'Geolocalización'" :icon="'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'" />
                    @endif
                    @if($userModules->has('calendar'))
                        <x-nav-item :route="'calendar'" :label="'Calendario'" :icon="'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'" />
                    @endif

                    @if($userModules->has('operators') || $userModules->has('expenses') || $userModules->has('incidents') || $userModules->has('evidence') || $userModules->has('gate') || $userModules->has('bitacora'))
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-4 pt-3 pb-1">Operaciones</p>
                    @endif
                    @if($userModules->has('operators'))
                        <x-nav-item :route="'operators'" :label="'Operadores'" :icon="'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'" />
                    @endif
                    @if($userModules->has('expenses'))
                        <x-nav-item :route="'expenses'" :label="'Gastos'" :icon="'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'" />
                    @endif
                    @if($userModules->has('incidents'))
                        <x-nav-item :route="'incidents'" :label="'Incidencias'" :icon="'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z'" />
                    @endif
                    @if($userModules->has('evidence'))
                        <x-nav-item :route="'evidence'" :label="'Evidencias'" :icon="'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'" />
                    @endif
                    @if($userModules->has('gate'))
                        <x-nav-item :route="'gate.dashboard'" :label="'Vigilancia'" :icon="'M13 10V3L4 14h7v7l9-11h-7z'" />
                    @endif
                    @if($userModules->has('bitacora'))
                        <x-nav-item :route="'gate.logs'" :label="'Bitácora'" :icon="'M4 4h16v16H4zM8 8h8M8 12h8M8 16h5'" />
                    @endif

                    @if($userModules->has('reports') || $userModules->has('users') || $userModules->has('security') || $userModules->has('catalogs') || $userModules->has('notifications'))
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-4 pt-3 pb-1">Administración</p>
                    @endif
                    @if($userModules->has('reports'))
                        <x-nav-item :route="'reports'" :label="'Reportes'" :icon="'M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'" />
                    @endif
                    @if($userModules->has('users'))
                        <x-nav-item :route="'users'" :label="'Usuarios'" :icon="'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'" />
                    @endif
                    @if($userModules->has('security'))
                        <x-nav-item :route="'security'" :label="'Seguridad OWASP'" :icon="'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'" />
                    @endif
                    @if($userModules->has('catalogs'))
                        <x-nav-item :route="'catalogs'" :label="'Catálogos'" :icon="'M4 6h16M4 10h16M4 14h16M4 18h16'" />
                    @endif
                    @if($userModules->has('notifications'))
                        <x-nav-item :route="'notifications'" :label="'Notificaciones'" :icon="'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'" />
                    @endif
                </nav>
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50/70 space-y-3 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-[#F71F96] flex items-center justify-center text-white font-black text-xs shadow-inner">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black text-slate-900 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider truncate">{{ Auth::user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 rounded-xl text-[10px] font-extrabold text-slate-500 hover:text-red-600 hover:bg-red-50 uppercase tracking-wider transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </aside>

        {{-- MOBILE HEADER --}}
        <header class="md:hidden bg-white border-b border-slate-200 shadow-xs px-4 py-3 flex flex-col gap-3 sticky top-0 z-30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('img/logo.png') }}" alt="Agua Inmaculada"
                        class="h-8 w-auto object-contain">
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 transition" title="Cerrar sesión">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
            <div class="flex overflow-x-auto gap-1.5 pb-1 scrollbar-hide -mx-1 px-1" x-data="{ active: '{{ request()->routeIs('dashboard') ? 'dashboard' : '' }}' }">
                @php
                    $mobileModules = $userModules;
                    $mobileNav = collect([
                        ['route' => 'dashboard', 'label' => 'Dashboard', 'module' => 'dashboard'],
                        ['route' => 'vehicles', 'label' => 'Vehículos', 'module' => 'vehicles'],
                        ['route' => 'routes', 'label' => 'Rutas', 'module' => 'routes'],
                        ['route' => 'operators', 'label' => 'Operadores', 'module' => 'operators'],
                        ['route' => 'fuel', 'label' => 'Combustible', 'module' => 'fuel'],
                        ['route' => 'maintenance', 'label' => 'Mantenimiento', 'module' => 'maintenance'],
                        ['route' => 'incidents', 'label' => 'Incidencias', 'module' => 'incidents'],
                        ['route' => 'expenses', 'label' => 'Gastos', 'module' => 'expenses'],
                        ['route' => 'evidence', 'label' => 'Evidencias', 'module' => 'evidence'],
                        ['route' => 'gate.dashboard', 'label' => 'Portería', 'module' => 'gate'],
                        ['route' => 'gate.logs', 'label' => 'Bitácora', 'module' => 'bitacora'],
                        ['route' => 'reports', 'label' => 'Reportes', 'module' => 'reports'],
                        ['route' => 'geolocation', 'label' => 'Geolocalización', 'module' => 'geolocation'],
                        ['route' => 'calendar', 'label' => 'Calendario', 'module' => 'calendar'],
                        ['route' => 'users', 'label' => 'Usuarios', 'module' => 'users'],
                        ['route' => 'security', 'label' => 'Seguridad', 'module' => 'security'],
                        ['route' => 'catalogs', 'label' => 'Catálogos', 'module' => 'catalogs'],
                    ])->filter(fn($item) => $mobileModules->has($item['module']));
                @endphp
                @foreach($mobileNav as $item)
                    <a href="{{ route($item['route']) }}"
                       class="whitespace-nowrap px-3 py-1.5 rounded-lg text-[10px] font-bold transition
                              {{ request()->routeIs($item['route']) ? 'bg-[#F71F96] text-white' : 'bg-white text-slate-600 border border-slate-200' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </header>
    @endauth

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
        @auth
            <header class="hidden md:flex h-16 bg-white border-b border-slate-200 px-8 items-center justify-between sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <h2 class="font-black text-base text-slate-800 tracking-tight">Monitoreo de Flota & Control en Tiempo Real</h2>
                    <div class="h-6 w-px bg-slate-200"></div>
                    <div class="flex items-center gap-2 px-3 py-1 bg-[#1F96F7]/10 text-[#1F96F7] border border-[#1F96F7]/20 rounded-full text-[10px] font-extrabold tracking-wider uppercase">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        OWASP Top 10 Secured
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <livewire:notification.notification-bell wire:key="notification-bell" />
                    <div class="bg-slate-50 border border-slate-100 px-3 py-1 rounded-lg flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-xs font-mono font-bold text-slate-700" x-data x-init="setInterval(() => $el.textContent = new Date().toLocaleTimeString('es-MX'), 1000)">--:--:--</span>
                    </div>
                    <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                        <div class="text-right">
                            <p class="text-xs font-black text-slate-900 leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ Auth::user()->role }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-slate-100 border-2 border-[#1F96F7] flex items-center justify-center font-black text-xs text-[#1F96F7] shadow-inner">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    </div>
                </div>
            </header>
        @endauth

        <main class="flex-grow p-4 md:p-8 space-y-8 overflow-y-auto max-w-7xl w-full mx-auto">
            {{ $slot }}
        </main>

        @auth
            <footer class="bg-white border-t border-slate-200 py-6 px-8 mt-12">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-400 font-semibold uppercase tracking-wider">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#F71F96]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>© 2026 Agua Inmaculada S.A. de C.V. Todos los derechos reservados.</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-slate-400">OWASP Top 10 • CSP • HttpOnly • Anti-CSRF Activo</span>
                        <span class="text-emerald-500 font-extrabold">• Servidor Seguro de Producción</span>
                    </div>
                </div>
            </footer>
        @endauth
    </div>

    <script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('swal:confirm', (data) => {
            Swal.fire({
                title: data.title ?? '¿Estás seguro?',
                text: data.text ?? 'Esta acción no se puede deshacer.',
                icon: data.icon ?? 'warning',
                showCancelButton: true,
                confirmButtonColor: '#F71F96',
                cancelButtonColor: '#64748b',
                confirmButtonText: data.confirmText ?? 'Sí, confirmar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch(data.callback, data.params ?? {});
                }
            });
        });

        Livewire.on('swal:success', (data) => {
            Swal.fire({ icon: 'success', title: data.title ?? 'Éxito', text: data.message ?? '', timer: 2000, showConfirmButton: false });
        });

        Livewire.on('swal:error', (data) => {
            Swal.fire({ icon: 'error', title: data.title ?? 'Error', text: data.message ?? '', timer: 3000, showConfirmButton: false });
        });

        Livewire.on('swal:prompt', (data) => {
            Swal.fire({
                title: data.title ?? 'Ingresa el motivo',
                text: data.text ?? '',
                input: 'textarea',
                inputPlaceholder: 'Motivo...',
                showCancelButton: true,
                confirmButtonColor: '#F71F96',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage('Este campo es requerido');
                    }
                    return value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch(data.callback, { id: data.params?.id, reason: result.value });
                }
            });
        });
    });
    </script>
    @stack('scripts')
</body>
</html>
