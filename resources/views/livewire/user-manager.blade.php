<div>
    <div class="bg-white rounded-3xl shadow-xs border border-slate-200 p-6 md:p-8 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-black text-slate-800">Usuarios del Sistema</h2>
                <p class="text-xs text-gray-400 font-bold mt-0.5">{{ $users->total() }} usuarios</p>
            </div>
            <button wire:click="create"
                class="flex items-center gap-1.5 bg-[#E72085] hover:bg-[#E72085]/95 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-md shadow-[#E72085]/15">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Nuevo Usuario
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead>
                    <tr class="bg-slate-50 text-gray-400 font-bold uppercase tracking-wider">
                        <th class="px-4 py-3 rounded-l-lg">Usuario</th>
                        <th class="px-4 py-3">Rol</th>
                        <th class="px-4 py-3">Teléfono</th>
                        <th class="px-4 py-3">Documento</th>
                        <th class="px-4 py-3">Licencia</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-center rounded-r-lg">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $u)
                    <tr class="hover:bg-slate-50/50 transition cursor-pointer"
                        wire:click="showExpediente({{ $u->id }})">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#E72085] text-white flex items-center justify-center text-xs font-black">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-xs">{{ $u->name }}</p>
                                    <p class="text-gray-400 text-[10px]">{{ $u->email }}</p>
                                </div>
                            </div>
        </div>
        </td>
        <td class="px-4 py-3">
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">{{ $u->role }}</span>
        </td>
        <td class="px-4 py-3 text-gray-400">{{ $u->phone ?? '—' }}</td>
        <td class="px-4 py-3">
            @if($u->document_type && $u->document_number)
            <span class="font-bold text-gray-700">{{ $u->document_type }} {{ $u->document_number }}</span>
            @else
            <span class="text-gray-400">—</span>
            @endif
        </td>
        <td class="px-4 py-3">
            @if($u->license_type)
            <span class="font-bold text-gray-700">{{ $u->license_type }}</span>
            @else
            <span class="text-gray-400">—</span>
            @endif
        </td>
        <td class="px-4 py-3 text-center">
            <span
                class="px-2 py-0.5 rounded-full text-[10px] font-bold
                            {{ $u->status === 'Activo' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                {{ $u->status }}
            </span>
        </td>
        <td class="px-4 py-3" wire:click.stop>
            <div class="flex items-center justify-center gap-2">
                <button wire:click="edit({{ $u->id }})"
                    class="p-1.5 rounded-lg text-[#008FD3] hover:bg-blue-50 transition" title="Editar">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                </button>
                <button wire:click="toggleStatus({{ $u->id }})"
                    class="p-1.5 rounded-lg text-amber-500 hover:bg-amber-50 transition" title="Cambiar estado">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </button>
                <button wire:click="confirmDelete({{ $u->id }})"
                    class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 transition" title="Eliminar">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                </button>
            </div>
        </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center py-12 text-gray-400 text-sm font-bold">No hay usuarios registrados</td>
        </tr>
        @endforelse
        </tbody>
        </table>
    </div>

    <div class="px-1">
        {{ $users->links() }}
    </div>
</div>

<div x-data="{ show: @entangle('showForm') }" x-show="show" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition.opacity>
    <div class="absolute inset-0 bg-black/40" x-on:click="show = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto"
        x-on:click.stop>
        <button x-on:click="show = false"
            class="absolute top-3 right-3 p-1.5 rounded-lg hover:bg-slate-100 text-gray-400 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
        <h3 class="text-sm font-black text-gray-800 mb-4">{{ $editId ? 'Editar' : 'Nuevo' }} Usuario</h3>
        <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Nombre</label>
                <input wire:model="name"
                    class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                @error('name') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Email</label>
                <input wire:model="email" type="email"
                    class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                @error('email') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Contraseña
                    {{ $editId ? '(dejar vacío para mantener)' : '' }}</label>
                <input wire:model="password" type="password"
                    class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                @error('password') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Rol</label>
                <select wire:model.live="role"
                    class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @foreach($userRoles as $ur)
                    <option value="{{ $ur->value }}">{{ $ur->label }}</option>
                    @endforeach
                </select>
                @error('role') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
            </div>

            @if(in_array($role, $adminRoles))
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Estado</label>
                <select wire:model="status"
                    class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @foreach($userStatuses as $us)
                    <option value="{{ $us->value }}">{{ $us->label }}</option>
                    @endforeach
                </select>
                @error('status') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Teléfono</label>
                <input wire:model="phone"
                    class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                @error('phone') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block">Módulos visibles</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($allModules as $module)
                    <label
                        class="flex items-center gap-2 p-2 rounded-lg border transition cursor-pointer
                                {{ in_array($module->id, $selectedModules) ? 'border-[#E72085] bg-[#E72085]/5' : 'border-gray-200 hover:border-gray-300' }}">
                        <input type="checkbox" value="{{ $module->id }}" wire:model="selectedModules"
                            class="rounded border-gray-300 text-[#E72085] focus:ring-[#E72085]">
                        <span class="text-[10px] font-bold text-gray-700">{{ $module->name }}</span>
                    </label>
                    @endforeach
                </div>
                @error('selectedModules') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
            </div>
            @elseif(in_array($role, $driverRoles))
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Estado</label>
                <select wire:model="status"
                    class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    @foreach($userStatuses as $us)
                    <option value="{{ $us->value }}">{{ $us->label }}</option>
                    @endforeach
                </select>
                @error('status') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Teléfono</label>
                <input wire:model="phone"
                    class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                @error('phone') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Tipo de Documento</label>
                <select wire:model="document_type"
                    class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    <option value="">Seleccionar</option>
                    @foreach($documentTypes as $dt)
                    <option value="{{ $dt->value }}">{{ $dt->label }}</option>
                    @endforeach
                </select>
                @error('document_type') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Número de Documento</label>
                <input wire:model="document_number"
                    class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                @error('document_number') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Tipo de Licencia</label>
                <select wire:model="license_type"
                    class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                    <option value="">Seleccionar</option>
                    @foreach($licenseTypes as $lt)
                    <option value="{{ $lt->value }}">{{ $lt->label }}</option>
                    @endforeach
                </select>
                @error('license_type') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Contacto de Emergencia</label>
                <input wire:model="emergency_contact"
                    class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                @error('emergency_contact') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Tel. Emergencia</label>
                <input wire:model="emergency_phone"
                    class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none">
                @error('emergency_phone') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Dirección</label>
                <textarea wire:model="address" rows="2"
                    class="w-full bg-white border border-gray-200 rounded-lg p-2 text-xs focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none"></textarea>
                @error('address') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
            </div>
            @endif

            <div class="md:col-span-2 flex gap-2">
                <button type="submit"
                    class="px-6 py-2 rounded-xl bg-[#E72085] text-white text-xs font-black uppercase tracking-wider hover:bg-[#d01c73] transition shadow-md shadow-[#E72085]/15">{{ $editId ? 'Actualizar' : 'Guardar' }}</button>
                <button type="button" x-on:click="show = false"
                    class="px-6 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs font-black hover:bg-slate-50 transition">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<div x-data="{ show: @entangle('showDetail') }" x-show="show" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition.opacity>
    <div class="absolute inset-0 bg-black/40" x-on:click="show = false"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6" x-on:click.stop>
        <button x-on:click="show = false"
            class="absolute top-3 right-3 p-1.5 rounded-lg hover:bg-slate-100 text-gray-400 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
        @if($detailRecord)
        <div class="text-center mb-6">
            <div
                class="w-16 h-16 rounded-full bg-[#E72085] text-white flex items-center justify-center text-lg font-black mx-auto mb-2">
                {{ strtoupper(substr($detailRecord->name, 0, 2)) }}
            </div>
            <h3 class="text-lg font-black text-gray-800">{{ $detailRecord->name }}</h3>
            <p class="text-xs text-gray-400">{{ $detailRecord->email }}</p>
        </div>
        <div class="grid grid-cols-2 gap-3 text-xs">
            <div class="bg-slate-50 p-3 rounded-xl">
                <p class="text-[10px] font-bold text-gray-400 uppercase">Rol</p>
                <p class="font-bold text-gray-800 mt-0.5">{{ $detailRecord->role }}</p>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl">
                <p class="text-[10px] font-bold text-gray-400 uppercase">Teléfono</p>
                <p class="font-bold text-gray-800 mt-0.5">{{ $detailRecord->phone ?? '—' }}</p>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl">
                <p class="text-[10px] font-bold text-gray-400 uppercase">Estado</p>
                <p class="font-bold text-gray-800 mt-0.5">{{ $detailRecord->status }}</p>
            </div>
            @if(in_array($detailRecord->role, $driverRoles))
            <div class="bg-slate-50 p-3 rounded-xl">
                <p class="text-[10px] font-bold text-gray-400 uppercase">Documento</p>
                <p class="font-bold text-gray-800 mt-0.5">{{ $detailRecord->document_type }}
                    {{ $detailRecord->document_number }}</p>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl">
                <p class="text-[10px] font-bold text-gray-400 uppercase">Licencia</p>
                <p class="font-bold text-gray-800 mt-0.5">{{ $detailRecord->license_type ?? '—' }}</p>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl">
                <p class="text-[10px] font-bold text-gray-400 uppercase">Contacto Emergencia</p>
                <p class="font-bold text-gray-800 mt-0.5">{{ $detailRecord->emergency_contact ?? '—' }}</p>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl">
                <p class="text-[10px] font-bold text-gray-400 uppercase">Tel. Emergencia</p>
                <p class="font-bold text-gray-800 mt-0.5">{{ $detailRecord->emergency_phone ?? '—' }}</p>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl col-span-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase">Dirección</p>
                <p class="font-bold text-gray-800 mt-0.5">{{ $detailRecord->address ?? '—' }}</p>
            </div>
            @endif
            <div class="bg-slate-50 p-3 rounded-xl col-span-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase">Registrado</p>
                <p class="font-bold text-gray-800 mt-0.5">{{ $detailRecord->created_at->format('d/m/Y H:i') }}</p>
            </div>
            @if($detailRecord->modules && $detailRecord->modules->count())
            <div class="bg-slate-50 p-3 rounded-xl col-span-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase">Módulos Asignados</p>
                <div class="flex flex-wrap gap-1 mt-1">
                    @foreach($detailRecord->modules as $module)
                    <span
                        class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800">{{ $module->name }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
</div>
