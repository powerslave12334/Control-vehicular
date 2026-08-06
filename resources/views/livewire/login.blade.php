<div class="min-h-screen bg-gradient-to-r from-[#E72085] to-[#FF69B4] flex items-center justify-center p-4 sm:p-6">

    <div class="w-[788px] max-w-full min-h-[480px] bg-white rounded-[30px] shadow-[0_30px_60px_-12px_rgba(120,0,60,0.55)] overflow-hidden flex flex-col md:flex-row">

        <div class="relative md:w-1/2 flex flex-col items-center justify-center text-center px-8 py-10 md:py-0 bg-gradient-to-br from-[#E72085] to-[#FF69B4] overflow-hidden">
            <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(closest-side, rgba(255,255,255,0.28), transparent 70%);"></div>

            <div class="relative z-10">
                <img src="{{ asset('img/logo.png') }}" alt="Agua Inmaculada"
                    class="mx-auto h-16 sm:h-20 w-auto object-contain drop-shadow-[0_10px_25px_rgba(120,0,60,0.45)]">

                <h1 class="mt-6 text-xl sm:text-2xl text-white font-black uppercase tracking-[0.18em]">Control Vehicular</h1>
                <div class="mx-auto mt-3 h-px w-12 bg-white/50"></div>
                <p class="mt-3 text-white/75 text-sm font-medium hidden sm:block">Gestión y monitoreo de la flota de Agua Inmaculada.</p>
            </div>
        </div>

        <div class="md:w-1/2 flex items-center justify-center px-6 sm:px-8 py-8">
            <div class="w-full max-w-sm">

                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-[#E72085]/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#E72085]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-slate-900">Iniciar Sesión</h2>
                        <p class="text-[11px] text-slate-400 font-medium">Ingresa tus credenciales para continuar</p>
                    </div>
                </div>

                <form wire:submit="login" class="space-y-4">
                    @if($error)
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2.5 rounded-xl text-xs font-semibold flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        {{ $error }}
                    </div>
                    @endif

                    <div>
                        <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block mb-1.5">Correo Electrónico</label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                            <input type="email" wire:model="email" wire:input="clearError" placeholder="ej: alberto.mendez@aguainmaculada.com"
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-slate-50"
                                   autofocus>
                        </div>
                        @error('email') <span class="text-[10px] text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block mb-1.5">Contraseña</label>
                        <div class="relative" x-data="{ show: false }">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <input :type="show ? 'text' : 'password'" wire:model="password" wire:input="clearError" placeholder="••••••••"
                                   class="w-full pl-10 pr-10 py-3 rounded-xl border border-slate-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] bg-slate-50">
                            <button type="button" @click="show = !show" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 rounded-md focus:outline-none focus:ring-2 focus:ring-[#E72085]/30">
                                <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                    <path d="m6.34 7.66 11.32 11.32"/>
                                    <line x1="1" y1="1" x2="23" y2="23"/>
                                </svg>
                            </button>
                        </div>
                        @error('password') <span class="text-[10px] text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-[#E72085] to-[#FF69B4] text-white font-bold py-3.5 rounded-xl text-sm uppercase tracking-wider transition-all hover:from-[#C9106A] hover:to-[#FF69B4] hover:shadow-lg hover:shadow-[#E72085]/30 shadow-md shadow-[#E72085]/20">
                        Acceder al Sistema
                    </button>
                </form>

                <details class="mt-5 group">
                    <summary class="text-[10px] text-slate-400 font-semibold cursor-pointer hover:text-slate-600 transition-colors list-none flex items-center gap-1 justify-center">
                        <span>Credenciales de prueba</span>
                        <span class="text-slate-300 group-open:rotate-180 transition-transform">▼</span>
                    </summary>
                    <div class="mt-3 p-3 bg-slate-50 rounded-xl border border-slate-100 text-[10px] text-slate-500 space-y-1.5 max-h-48 overflow-y-auto">
                        @foreach($testUsers as $u)
                        <div class="flex justify-between font-mono">
                            <span class="font-bold text-slate-700">{{ $u['email'] }}</span>
                            <span class="text-slate-400">/{{ $u['password'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </details>

                <div class="flex items-center justify-center gap-2 mt-4 text-[10px] text-slate-400 font-medium">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <span>Entorno simulado · Sin autenticación real</span>
                </div>
            </div>
        </div>
    </div>
</div>
