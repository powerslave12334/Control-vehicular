<?php

namespace App\Livewire\Maintenance;

use App\Domains\Catalog\Models\Catalog;
use App\Domains\Maintenance\DTO\MaintenanceData;
use App\Domains\Maintenance\Models\Maintenance;
use App\Domains\Maintenance\Services\MaintenanceService;
use App\Domains\Vehicle\Services\VehicleService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts::app')]
class MaintenanceManager extends Component
{
    use WithFileUploads;
    use WithPagination;

    public bool $showForm = false;

    public bool $showDetail = false;

    public ?int $editId = null;

    public $detailRecord = null;

    public string $filterSearch = '';

    public string $filterType = '';

    public string $filterStatus = '';

    public string $filterVehicleId = '';

    #[Rule('required|exists:vehicles,id')]
    public string $vehicle_id = '';

    #[Rule('required')]
    public string $type = 'Preventivo';

    #[Rule('required|regex:/^[\pL\s0-9\.\,\-\/\#\(\)]+$/u')]
    public string $description = '';

    #[Rule('required|regex:/^\d+(\.\d{1,2})?$/u|min:0')]
    public string $cost = '';

    #[Rule('required|regex:/^[\pL\s0-9\.\,\-\#\(\)]+$/u')]
    public string $workshop = '';

    #[Rule('nullable|integer|min:0')]
    public string $odometer = '';

    #[Rule('required|date')]
    public string $date = '';

    #[Rule('nullable|regex:/^[\pL\s0-9\.\-\/\(\)]+$/u')]
    public string $category = '';

    #[Rule('nullable|date')]
    public string $scheduled_date = '';

    #[Rule('nullable|date')]
    public string $start_date = '';

    #[Rule('nullable|date')]
    public string $end_date = '';

    #[Rule('required|string')]
    public string $status = 'programado';

    #[Rule('nullable|image|max:5120')]
    public $evidence = null;

    public string $existing_evidence = '';

    public function resetForm()
    {
        $this->showForm = false;
        $this->editId = null;
        $this->vehicle_id = '';
        $this->type = 'Preventivo';
        $this->description = '';
        $this->cost = '';
        $this->workshop = '';
        $this->odometer = '';
        $this->date = now()->toDateString();
        $this->category = '';
        $this->scheduled_date = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->status = 'programado';
        $this->evidence = null;
        $this->existing_evidence = '';
    }

    public function clearFilters()
    {
        $this->filterSearch = '';
        $this->filterType = '';
        $this->filterStatus = '';
        $this->filterVehicleId = '';
        $this->resetPage();
    }

    public function updatedFilterSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterVehicleId()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id, MaintenanceService $service)
    {
        $m = $service->getById($id);
        $this->editId = $m->id;
        $this->vehicle_id = (string) $m->vehicle_id;
        $this->type = $m->type?->value ?? $m->type;
        $this->description = $m->description;
        $this->cost = (string) $m->cost;
        $this->workshop = $m->workshop;
        $this->odometer = (string) ($m->odometer ?? '');
        $this->date = $m->date?->toDateString() ?? now()->toDateString();
        $this->category = $m->category ?? '';
        $this->scheduled_date = $m->scheduled_date?->toDateString() ?? '';
        $this->start_date = $m->start_date?->toDateString() ?? '';
        $this->end_date = $m->end_date?->toDateString() ?? '';
        $this->status = $m->status ?? 'programado';
        $this->evidence = null;
        $this->existing_evidence = $m->evidence ?? '';
        $this->showForm = true;
    }

    public function save(MaintenanceService $service)
    {
        $this->validate();

        $evidencePath = $this->existing_evidence ?: null;
        if ($this->evidence) {
            $evidencePath = $this->evidence->store('maintenance');
        }

        if ($this->status === 'en_progreso' && ! $this->start_date) {
            $this->start_date = now()->toDateString();
        }

        if ($this->status === 'completado' && ! $this->end_date) {
            $this->end_date = now()->toDateString();
        }

        $data = [
            'date' => $this->date,
            'vehicle_id' => $this->vehicle_id,
            'type' => $this->type,
            'category' => $this->category,
            'description' => $this->description,
            'cost' => $this->cost,
            'workshop' => $this->workshop,
            'odometer' => $this->odometer ?: null,
            'scheduled_date' => $this->scheduled_date ?: null,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'status' => $this->status,
            'evidence' => $evidencePath,
        ];

        $dto = MaintenanceData::fromArray($data);

        if ($this->editId) {
            $service->update($this->editId, $dto);
            $this->dispatch('swal:success', title: 'Actualizado', message: 'Mantenimiento actualizado.');
        } else {
            $service->create($dto);
            $this->dispatch('swal:success', title: 'Registrado', message: 'Mantenimiento registrado.');
        }

        $this->resetForm();
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', title: 'Eliminar', text: '¿Eliminar este registro de mantenimiento?', callback: 'deleteMaintenance', params: ['id' => $id]);
    }

    #[On('deleteMaintenance')]
    public function deleteMaintenance(int $id, MaintenanceService $service)
    {
        $service->delete($id);
        $this->dispatch('swal:success', title: 'Eliminado');
    }

    public function showExpediente(int $id, MaintenanceService $service)
    {
        $this->detailRecord = $service->getById($id)->load(['vehicle', 'maintenanceWorkshop']);
        $this->showDetail = true;
    }

    public function resetDetail()
    {
        $this->showDetail = false;
        $this->detailRecord = null;
    }

    public function render(VehicleService $vehicleService, MaintenanceService $service)
    {
        $all = Maintenance::with(['vehicle'])->get();

        $stats = [
            'total' => $all->count(),
            'programado' => $all->where('status', 'programado')->count(),
            'en_progreso' => $all->where('status', 'en_progreso')->count(),
            'completado' => $all->where('status', 'completado')->count(),
        ];

        $records = $service->getAllFiltered([
            'search' => trim($this->filterSearch),
            'type' => $this->filterType,
            'status' => $this->filterStatus,
            'vehicle_id' => $this->filterVehicleId,
        ], 10);

        return view('livewire.maintenance-manager', [
            'records' => $records,
            'stats' => $stats,
            'vehicles' => $vehicleService->getAll(),
            'maintenanceTypes' => Catalog::byGroup('TipoMantenimiento')->get(),
            'maintenanceStatuses' => Catalog::byGroup('EstatusMantenimiento')->get(),
        ]);
    }
}
