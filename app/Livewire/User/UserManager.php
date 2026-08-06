<?php

namespace App\Livewire\User;

use App\Domains\User\Models\User;
use App\Models\Module;
use App\Domains\Catalog\Models\Catalog;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts::app')]
class UserManager extends Component
{
    public bool $showForm = false;
    public bool $showDetail = false;
    public ?int $editId = null;
    public $detailRecord = null;

    public array $adminRoles = [
        'Administrador del sistema', 'Dirección', 'Calidad', 'Logística', 'Jefe de Distribución', 'vigilancia',
    ];

    public array $driverRoles = [
        'Chofer', 'Instalador', 'Operador',
    ];

    public array $vigilanciaModules = [
        'gate', 'bitacora',
    ];

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'Operador';
    public string $status = 'Activo';
    public string $phone = '';
    public string $document_type = '';
    public string $document_number = '';
    public string $license_type = '';
    public string $emergency_contact = '';
    public string $emergency_phone = '';
    public string $address = '';

    public array $selectedModules = [];

    public function resetForm()
    {
        $this->showForm = false;
        $this->editId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'Operador';
        $this->status = 'Activo';
        $this->phone = '';
        $this->document_type = '';
        $this->document_number = '';
        $this->license_type = '';
        $this->emergency_contact = '';
        $this->emergency_phone = '';
        $this->address = '';
        $this->selectedModules = [];
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function updatedRole($value)
    {
        if ($value === 'vigilancia' && $this->editId === null) {
            $this->selectedModules = $this->vigilanciaModules;
        }
    }

    public function edit(int $id)
    {
        $u = User::findOrFail($id);
        $this->editId = $u->id;
        $this->name = $u->name;
        $this->email = $u->email;
        $this->password = '';
        $this->role = $u->role;
        $this->status = $u->status->value;
        $this->phone = $u->phone ?? '';
        $this->document_type = $u->document_type ?? '';
        $this->document_number = $u->document_number ?? '';
        $this->license_type = $u->license_type ?? '';
        $this->emergency_contact = $u->emergency_contact ?? '';
        $this->emergency_phone = $u->emergency_phone ?? '';
        $this->address = $u->address ?? '';
        $this->selectedModules = $u->modules->pluck('id')->toArray();
        $this->showForm = true;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|regex:/^[\pL\s0-9\.]+$/u|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . ($this->editId ?? 'NULL'),
            'role' => 'required|string',
            'status' => 'required|string',
            'phone' => 'nullable|regex:/^[\d\s\-\(\)\+]+$/u',
        ];
        if (!$this->editId || $this->password !== '') {
            $rules['password'] = 'required|string|min:6';
        }
        if (in_array($this->role, $this->driverRoles)) {
            $rules['document_type'] = 'required|string';
            $rules['document_number'] = 'required|string';
            $rules['license_type'] = 'required|string';
            $rules['emergency_contact'] = 'required|string';
            $rules['emergency_phone'] = 'required|string';
            $rules['address'] = 'required|string';
        }
        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
            'phone' => $this->phone,
        ];
        if (in_array($this->role, $this->driverRoles)) {
            $data['document_type'] = $this->document_type;
            $data['document_number'] = $this->document_number;
            $data['license_type'] = $this->license_type;
            $data['emergency_contact'] = $this->emergency_contact;
            $data['emergency_phone'] = $this->emergency_phone;
            $data['address'] = $this->address;
        }
        if ($this->password !== '') {
            $data['password'] = bcrypt($this->password);
        }

        if ($this->editId) {
            $user = User::findOrFail($this->editId);
            $user->update($data);
            $user->modules()->sync(in_array($this->role, $this->adminRoles) ? $this->selectedModules : []);
            $this->dispatch('swal:success', title: 'Actualizado', message: 'Usuario actualizado correctamente.');
        } else {
            $user = User::create($data);
            $user->modules()->sync(in_array($this->role, $this->adminRoles) ? $this->selectedModules : []);
            $this->dispatch('swal:success', title: 'Creado', message: 'Usuario creado correctamente.');
        }

        $this->resetForm();
    }

    public function toggleStatus(int $id)
    {
        $u = User::findOrFail($id);
        $newStatus = $u->status === 'Activo' ? 'Inactivo' : 'Activo';
        $u->update(['status' => $newStatus]);
        $this->dispatch('swal:success', title: 'Estado actualizado', message: "Usuario ahora está: $newStatus");
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', title: 'Eliminar usuario', text: '¿Estás seguro?', callback: 'deleteUser', params: ['id' => $id]);
    }

    #[On('deleteUser')]
    public function deleteUser(int $id)
    {
        User::findOrFail($id)->delete();
        $this->dispatch('swal:success', title: 'Eliminado', message: 'Usuario eliminado correctamente.');
    }

    public function showExpediente(int $id)
    {
        $this->detailRecord = User::with('modules')->findOrFail($id);
        $this->showDetail = true;
    }

    public function resetDetail()
    {
        $this->showDetail = false;
        $this->detailRecord = null;
    }

    public function render()
    {
        return view('livewire.user-manager', [
            'users' => User::orderBy('name')->paginate(20),
            'allModules' => Module::orderBy('name')->get(),
            'userRoles' => Catalog::byGroup('RolUsuario')->get(),
            'userStatuses' => Catalog::byGroup('EstadoUsuario')->get(),
            'documentTypes' => Catalog::byGroup('TipoDocumento')->get(),
            'licenseTypes' => Catalog::byGroup('TipoLicencia')->get(),
        ]);
    }
}
