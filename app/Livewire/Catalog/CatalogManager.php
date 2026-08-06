<?php

namespace App\Livewire\Catalog;

use App\Domains\Catalog\Models\Catalog;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts::app')]
class CatalogManager extends Component
{
    public bool $showForm = false;
    public ?int $editId = null;
    public string $filterGroup = '';

    #[Rule('required|regex:/^[\pL\s0-9\-_]+$/u|max:255')]
    public string $group = '';
    #[Rule('required|regex:/^[\pL\s0-9\-_]+$/u|max:255')]
    public string $value = '';
    #[Rule('required|regex:/^[\pL\s0-9\.\,\-\/\(\)]+$/u|max:255')]
    public string $label = '';

    public function resetForm()
    {
        $this->showForm = false;
        $this->editId = null;
        $this->group = '';
        $this->value = '';
        $this->label = '';
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $c = Catalog::findOrFail($id);
        $this->editId = $c->id;
        $this->group = $c->group;
        $this->value = $c->value;
        $this->label = $c->label;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'group' => $this->group,
            'value' => $this->value,
            'label' => $this->label,
        ];

        if ($this->editId) {
            Catalog::findOrFail($this->editId)->update($data);
            $this->dispatch('swal:success', title: 'Actualizado', message: 'Catálogo actualizado correctamente.');
        } else {
            Catalog::create($data);
            $this->dispatch('swal:success', title: 'Creado', message: 'Catálogo creado correctamente.');
        }

        $this->resetForm();
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm',
            title: 'Eliminar catálogo',
            text: '¿Estás seguro? Esta acción no se puede deshacer.',
            callback: 'deleteCatalog',
            params: ['id' => $id],
        );
    }

    #[On('deleteCatalog')]
    public function deleteCatalog(int $id)
    {
        Catalog::findOrFail($id)->delete();
        $this->dispatch('swal:success', title: 'Eliminado', message: 'Catálogo eliminado correctamente.');
    }

    public function render()
    {
        $query = Catalog::query();
        if ($this->filterGroup) {
            $query->where('group', $this->filterGroup);
        }
        $catalogs = $query->orderBy('group')->orderBy('label')->get()->groupBy('group');
        $groups = Catalog::select('group')->distinct()->pluck('group');

        $groupLabels = [
            'fuelType' => 'Tipo de Combustible',
            'gpsInstalled' => 'GPS Instalado',
            'licenseType' => 'Tipo de Licencia',
            'operatorStatus' => 'Estado del Operador',
            'routeStatus' => 'Estado de Ruta',
            'maintenanceType' => 'Tipo de Mantenimiento',
            'fuelLevel' => 'Nivel de Combustible',
            'paymentMethod' => 'Método de Pago',
            'extraMovementType' => 'Tipo de Movimiento Extra',
            'incidentSeverity' => 'Severidad de Incidente',
            'userStatus' => 'Estado de Usuario',
        ];

        return view('livewire.catalog-manager', compact('catalogs', 'groups', 'groupLabels'));
    }
}
