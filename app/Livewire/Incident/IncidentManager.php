<?php

namespace App\Livewire\Incident;

use App\Domains\Incident\Services\IncidentService;
use App\Domains\Incident\DTO\IncidentData;
use App\Domains\Incident\Models\Incident;
use App\Domains\Vehicle\Services\VehicleService;
use App\Domains\Operator\Services\OperatorService;
use App\Domains\Catalog\Models\Catalog;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts::app')]
class IncidentManager extends Component
{
    use WithFileUploads;

    public bool $showForm = false;
    public bool $showDetail = false;
    public ?int $editId = null;
    public $detailRecord = null;

    public string $filterStatus = '';
    public string $filterSeverity = '';
    public string $filterVehicleId = '';
    public string $filterDateFrom = '';
    public string $filterDateTo = '';

    #[Rule('required|exists:vehicles,id')]
    public string $vehicle_id = '';
    #[Rule('required|exists:operators,id')]
    public string $driver_id = '';
    #[Rule('required|date')]
    public string $date = '';
    #[Rule('nullable|regex:/^\d{2}:\d{2}$/u')]
    public string $time = '';
    #[Rule('nullable|regex:/^[\pL\s0-9]+$/u')]
    public string $type = '';
    #[Rule('nullable|regex:/^[\pL\s0-9\.\,\-\#]+$/u')]
    public string $location = '';
    #[Rule('required|regex:/^[\pL\s0-9\.\,\-\/\#\(\)]+$/u')]
    public string $description = '';
    #[Rule('required')]
    public string $severity = 'Baja';
    #[Rule('nullable|regex:/^\d+(\.\d{1,2})?$/u|min:0')]
    public string $cost = '';
    public bool $involves_third_party = false;
    #[Rule('nullable|regex:/^[\pL\s0-9\.\,\-\/\#\(\)]+$/u')]
    public string $third_party_info = '';
    public $photo = null;

    public function mount()
    {
        $this->date = now()->toDateString();
        $this->time = now()->format('H:i');
    }

    public function resetForm()
    {
        $this->showForm = false;
        $this->editId = null;
        $this->vehicle_id = '';
        $this->driver_id = '';
        $this->date = now()->toDateString();
        $this->time = now()->format('H:i');
        $this->type = '';
        $this->location = '';
        $this->description = '';
        $this->severity = 'Baja';
        $this->cost = '';
        $this->involves_third_party = false;
        $this->third_party_info = '';
        $this->photo = null;
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id, IncidentService $service)
    {
        $inc = $service->getById($id);
        $this->editId = $inc->id;
        $this->vehicle_id = (string) $inc->vehicle_id;
        $this->driver_id = (string) $inc->driver_id;
        $this->date = $inc->date?->toDateString() ?? now()->toDateString();
        $this->time = $inc->time ?? '';
        $this->type = $inc->type ?? '';
        $this->location = $inc->location ?? '';
        $this->description = $inc->description;
        $this->severity = $inc->severity?->value ?? $inc->severity;
        $this->cost = $inc->cost ? (string) $inc->cost : '';
        $this->involves_third_party = $inc->involves_third_party ?? false;
        $this->third_party_info = $inc->third_party_data['info'] ?? '';
        $this->showForm = true;
    }

    public function save(IncidentService $service, OperatorService $operatorService)
    {
        $this->validate();

        $driver = $operatorService->getById((int) $this->driver_id);

        $photoPath = null;
        if ($this->photo) {
            $photoPath = $this->photo->store('incidents');
        }

        $data = [
            'date' => $this->date,
            'time' => $this->time ?: now()->format('H:i'),
            'vehicle_id' => $this->vehicle_id,
            'driver_id' => $this->driver_id,
            'driver_name' => $driver->name,
            'type' => $this->type ?: null,
            'location' => $this->location ?: null,
            'description' => $this->description,
            'severity' => $this->severity,
            'status' => 'Reportada',
            'cost' => $this->cost !== '' ? (float) $this->cost : null,
            'involves_third_party' => $this->involves_third_party,
            'third_party_data' => $this->involves_third_party && $this->third_party_info
                ? ['info' => $this->third_party_info] : null,
            'photo' => $photoPath,
        ];

        if ($this->editId) {
            $service->update($this->editId, $data);
            $this->dispatch('swal:success', title: 'Actualizada', message: 'Incidencia actualizada correctamente.');
        } else {
            $dto = IncidentData::fromArray($data);
            $service->create($dto);
            $this->dispatch('swal:success', title: 'Reportada', message: 'Incidencia registrada correctamente.');
        }

        $this->resetForm();
    }

    public function changeStatus(int $id, string $status, IncidentService $service)
    {
        $this->validate(['status' => 'required|string']);

        $data = ['status' => $status];
        if ($status === 'Resuelta' || $status === 'Cerrada') {
            $data['resolved_at'] = now();
            $data['resolved_by'] = auth()->id();
        }
        $service->update($id, $data);
        $this->dispatch('swal:success', title: 'Actualizada', message: "Incidencia marcada como: $status");
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', title: 'Eliminar', text: '¿Eliminar esta incidencia?', callback: 'deleteIncident', params: ['id' => $id]);
    }

    #[On('deleteIncident')]
    public function deleteIncident(int $id, IncidentService $service)
    {
        $service->delete($id);
        $this->dispatch('swal:success', title: 'Eliminada');
    }

    public function showExpediente(int $id, IncidentService $service)
    {
        $this->detailRecord = $service->getById($id);
        $this->showDetail = true;
    }

    public function resetDetail()
    {
        $this->showDetail = false;
        $this->detailRecord = null;
    }

    public function render(VehicleService $vehicleService, OperatorService $operatorService, IncidentService $service)
    {
        $all = Incident::with(['vehicle', 'driver'])->latest('date')->get();

        $stats = [
            'total' => $all->count(),
            'reportadas' => $all->where('status', 'Reportada')->count(),
            'atendidas' => $all->where('status', 'Atendida')->count(),
            'resueltas' => $all->where('status', 'Resuelta')->count(),
            'cerradas' => $all->where('status', 'Cerrada')->count(),
        ];

        return view('livewire.incident-manager', [
            'incidents' => $service->getAllFiltered([
                'status' => $this->filterStatus,
                'severity' => $this->filterSeverity,
                'vehicle_id' => $this->filterVehicleId,
                'date_from' => $this->filterDateFrom,
                'date_to' => $this->filterDateTo,
            ], 20),
            'stats' => $stats,
            'vehicles' => $vehicleService->getAll(),
            'operators' => $operatorService->getAll(),
            'incidentStatuses' => Catalog::byGroup('EstatusIncidente')->get(),
            'incidentSeverities' => Catalog::byGroup('SeveridadIncidencia')->get(),
            'incidentTypes' => Catalog::byGroup('TipoIncidente')->get(),
        ]);
    }
}
