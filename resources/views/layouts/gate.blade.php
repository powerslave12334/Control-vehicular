<!DOCTYPE html>
<html lang="es" class="overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portería - {{ config('app.name', 'Agua Inmaculada') }}</title>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            --font-mono: 'JetBrains Mono', ui-monospace, 'SF Mono', 'Cascadia Mono', Menlo, monospace;
        }

        @keyframes gateFlash {
            0% { background-color: #fef3c7; }
            100% { background-color: transparent; }
        }
        .gate-flash { animation: gateFlash 1.6s ease-out; }
        @media (prefers-reduced-motion: reduce) {
            .gate-flash { animation: none; }
        }
    </style>
</head>
<body class="min-h-screen bg-[#e3e5e9] font-sans text-slate-800 selection:bg-[#E72085]/20 selection:text-[#E72085]">

    @auth
            <header class="bg-white border-b border-slate-200 shadow-xs px-4 py-3 sticky top-0 z-30">
                <div class="flex items-center justify-between max-w-7xl mx-auto">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-[#E72085] flex items-center justify-center text-white font-black text-lg shadow-sm">P</div>
                        <div>
                            <span class="text-sm font-black text-slate-900 tracking-tight">Portería</span>
                            <p class="text-[10px] text-slate-400 uppercase font-semibold tracking-wider">{{ Auth::user()->name }}</p>
                        </div>
                    </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('gate.dashboard') }}" class="p-1.5 text-slate-400 hover:text-emerald-600 transition" title="Inicio">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 transition" title="Cerrar sesión">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </header>
    @endauth

    <main class="mx-auto p-4 md:p-6 space-y-6">
        {{ $slot }}
    </main>

    <script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('swal:confirm', (data) => {
            Swal.fire({
                title: data.title ?? '¿Estás seguro?',
                text: data.text ?? 'Esta acción no se puede deshacer.',
                icon: data.icon ?? 'warning',
                showCancelButton: true,
                confirmButtonColor: '#059669',
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
    });
    </script>
    @stack('scripts')
</body>
</html>
