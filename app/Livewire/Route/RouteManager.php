<?php

namespace App\Livewire\Route;

use App\Domains\Catalog\Models\Catalog;
use App\Domains\Route\DTO\RouteData;
use App\Domains\Route\Models\RouteStep;
use App\Domains\Route\Services\RouteService;
use App\Domains\User\Models\User;
use App\Domains\Vehicle\Services\VehicleService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts::app')]
class RouteManager extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public bool $showDetail = false;

    public ?int $editId = null;

    public $detailRecord = null;

    public array $detailRouteSteps = [];

    public array $detailDestinations = [];

    public string $detailOrigin = '';

    public string $detailDestination = '';

    public string $filterSearch = '';

    public string $filterVehicleId = '';

    public string $filterStatus = '';

    public string $filterDateFrom = '';

    public string $filterDateTo = '';

    #[Rule('required|exists:users,id')]
    public string $driver_id = '';

    #[Rule('nullable|exists:users,id')]
    public string $assistant_id = '';

    #[Rule('required|exists:vehicles,id')]
    public string $vehicle_id = '';

    #[Rule('required|regex:/^[\pL\s0-9\.]+$/u')]
    public string $client_name = '';

    #[Rule('required|regex:/^\d+(\.\d{1,3})?$/u|min:1')]
    public string $planned_km = '';

    #[Rule('required|date')]
    public string $date = '';

    #[Rule('nullable|regex:/^[A-Za-z0-9\-\.\/]+$/u')]
    public string $code = '';

    #[Rule('nullable|regex:/^[\pL\s0-9\.\,\-\/\#\(\)]+$/u')]
    public string $description = '';

    #[Rule('nullable|regex:/^[\pL\s0-9\.\,\-]+$/u')]
    public string $origin = '';

    #[Rule('nullable|regex:/^[\pL\s0-9\.\,\-]+$/u')]
    public string $destination = '';

    #[Rule('nullable|array')]
    #[Rule(['destinations.*' => 'nullable|string|max:255'])]
    public array $destinations = [];

    #[Rule('nullable|regex:/^\d+(\.\d{1,3})?$/u|min:0')]
    public string $distance_km = '';

    #[Rule('nullable|regex:/^\d+(\.\d{1,2})?$/u|min:0')]
    public string $estimated_duration = '';

    #[Rule('nullable|regex:/^\d+(\.\d{1,3})?$/u|min:0')]
    public string $actual_km = '';

    #[Rule('required|string')]
    public string $status = 'Programada';

    public function resetForm()
    {
        $this->showForm = false;
        $this->editId = null;
        $this->driver_id = '';
        $this->assistant_id = '';
        $this->vehicle_id = '';
        $this->client_name = '';
        $this->planned_km = '';
        $this->date = now()->toDateString();
        $this->code = '';
        $this->description = '';
        $this->origin = 'Emilio Carranza 7, Agrícola Ignacio Zaragoza, 72710 Heroica Puebla de Zaragoza, Pue.';
        $this->destination = '';
        $this->destinations = [];
        $this->distance_km = '';
        $this->estimated_duration = '';
        $this->actual_km = '';
        $this->status = 'Programada';
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function clearFilters()
    {
        $this->filterSearch = '';
        $this->filterVehicleId = '';
        $this->filterStatus = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
        $this->resetPage();
    }

    public function updatedFilterSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterVehicleId()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterDateFrom()
    {
        $this->resetPage();
    }

    public function updatedFilterDateTo()
    {
        $this->resetPage();
    }

    public function addDestination()
    {
        $this->destinations[] = '';
        $this->dispatch('form:destinations-changed');
    }

    public function removeDestination(int $index)
    {
        unset($this->destinations[$index]);
        $this->destinations = array_values($this->destinations);
        $this->dispatch('form:destinations-changed');
    }

    public function edit(int $id, RouteService $service)
    {
        $r = $service->getById($id);
        $this->editId = $r->id;
        $this->driver_id = (string) $r->driver_id;
        $this->assistant_id = $r->assistant_id ? (string) $r->assistant_id : '';
        $this->vehicle_id = (string) $r->vehicle_id;
        $this->client_name = $r->client_name ?? '';
        $this->planned_km = (string) $r->planned_km;
        $this->date = $r->date?->toDateString() ?? now()->toDateString();
        $this->code = $r->code ?? '';
        $this->description = $r->description ?? '';
        $this->origin = $r->origin ?? '';
        $this->destination = $r->destination ?? '';
        $this->destinations = $r->destinations ?? [];
        $this->distance_km = $r->distance_km ? (string) $r->distance_km : '';
        $this->estimated_duration = $r->estimated_duration ? (string) (float) $r->estimated_duration : '';
        $this->actual_km = $r->actual_km ? (string) $r->actual_km : '';
        $this->status = $r->status?->value ?? 'Programada';
        $this->showForm = true;
    }

    public function save(RouteService $service, VehicleService $vehicleService)
    {
        $this->validate();

        $driver = User::findOrFail((int) $this->driver_id);
        $vehicle = $vehicleService->getById((int) $this->vehicle_id);
        $assistant = $this->assistant_id ? User::find((int) $this->assistant_id) : null;

        if ($this->editId) {
            $existing = $service->getById($this->editId);
            $currentStatus = $existing->status?->value ?? $existing->status;

            if (in_array($currentStatus, ['Completada', 'Cancelada'])) {
                $this->dispatch('swal:error', title: 'Error', message: 'No se puede modificar una ruta completada o cancelada.');

                return;
            }

            if (in_array($currentStatus, ['En tránsito', 'Instalando'])) {
                $this->status = $currentStatus;
            } elseif (! in_array($this->status, ['Programada', 'Asignada'])) {
                $this->dispatch('swal:error', title: 'Error', message: 'Los estados En tránsito, Instalando, Completada y Cancelada se gestionan con los botones Iniciar/Completar/Cancelar.');

                return;
            }
        }

        $destinations = array_values(array_filter(
            $this->destinations,
            fn ($d) => is_string($d) && trim($d) !== ''
        ));

        $data = [
            'date' => $this->date,
            'week' => 'Semana '.Carbon::parse($this->date)->week,
            'driver_id' => $this->driver_id,
            'driver_name' => $driver->name,
            'assistant_id' => $assistant?->id,
            'assistant_name' => $assistant?->name,
            'vehicle_id' => $this->vehicle_id,
            'vehicle_plate' => $vehicle->plate,
            'client_name' => $this->client_name,
            'planned_km' => $this->planned_km,
            'code' => $this->code ?: null,
            'description' => $this->description ?: null,
            'origin' => $this->origin ?: null,
            'destination' => $destinations ? end($destinations) : ($this->destination ?: null),
            'destinations' => $destinations ?: null,
            'distance_km' => $this->distance_km !== '' ? (float) $this->distance_km : null,
            'estimated_duration' => $this->estimated_duration !== '' ? (float) $this->estimated_duration : null,
            'actual_km' => $this->actual_km !== '' ? (float) $this->actual_km : null,
            'status' => $this->status,
        ];

        if ($this->editId) {
            $dto = RouteData::fromArray($data);
            $service->update($this->editId, $dto);
            $this->dispatch('swal:success', title: 'Actualizada', message: 'Ruta actualizada correctamente.');
        } else {
            if (! isset($data['actual_km'])) {
                $data['actual_km'] = 0;
            }
            $dto = RouteData::fromArray($data);
            $route = $service->create($dto);
            $stepTypes = ['departurePlant', 'arrivalClient', 'startInstallation', 'endInstallation', 'returnToPlant', 'arrivalPlant'];
            foreach ($stepTypes as $type) {
                RouteStep::create(['route_id' => $route->id, 'step_type' => $type, 'timestamp' => now()]);
            }
            $this->dispatch('swal:success', title: 'Creada', message: 'Ruta creada correctamente.');
        }

        $this->resetForm();
    }

    public function confirmStart(int $id)
    {
        $this->dispatch('swal:prompt',
            title: 'Iniciar ruta',
            text: 'Indica el kilometraje inicial del odómetro para iniciar la ruta:',
            inputType: 'number',
            inputAttributes: 'step="0.1" min="0"',
            callback: 'startRoute',
            params: ['id' => $id],
        );
    }

    #[On('startRoute')]
    public function startRoute(int $id, ?string $reason, RouteService $service)
    {
        if (! $reason || (float) $reason < 0) {
            $this->dispatch('swal:error', title: 'Error', message: 'Debes indicar un kilometraje inicial válido.');

            return;
        }

        try {
            $service->start($id, (float) $reason);
            $this->dispatch('swal:success', title: 'Iniciada', message: 'Ruta iniciada correctamente.');
        } catch (\RuntimeException $e) {
            $this->dispatch('swal:error', title: 'Error', message: $e->getMessage());
        }
    }

    public function confirmFinish(int $id)
    {
        $this->dispatch('swal:prompt',
            title: 'Completar ruta',
            text: 'Indica el kilometraje final del odómetro para completar la ruta:',
            inputType: 'number',
            inputAttributes: 'step="0.1" min="0"',
            callback: 'finishRoute',
            params: ['id' => $id],
        );
    }

    #[On('finishRoute')]
    public function finishRoute(int $id, ?string $reason, RouteService $service)
    {
        if (! $reason || (float) $reason <= 0) {
            $this->dispatch('swal:error', title: 'Error', message: 'Debes indicar un kilometraje final válido.');

            return;
        }

        try {
            $service->finish($id, (float) $reason);
            $this->dispatch('swal:success', title: 'Completada', message: 'Ruta completada correctamente.');
        } catch (\RuntimeException $e) {
            $this->dispatch('swal:error', title: 'Error', message: $e->getMessage());
        }
    }

    public function confirmCancel(int $id)
    {
        $this->dispatch('swal:prompt',
            title: 'Cancelar ruta',
            text: 'Indica el motivo de la cancelación:',
            callback: 'cancelRoute',
            params: ['id' => $id],
        );
    }

    #[On('cancelRoute')]
    public function cancelRoute(int $id, ?string $reason, RouteService $service)
    {
        if (! $reason) {
            $this->dispatch('swal:error', title: 'Error', message: 'Debes indicar un motivo de cancelación.');

            return;
        }

        try {
            $service->cancel($id, $reason);
            $this->dispatch('swal:success', title: 'Cancelada', message: 'Ruta cancelada correctamente.');
        } catch (\RuntimeException $e) {
            $this->dispatch('swal:error', title: 'Error', message: $e->getMessage());
        }
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', title: 'Eliminar ruta', text: '¿Estás seguro?', callback: 'deleteRoute', params: ['id' => $id]);
    }

    #[On('deleteRoute')]
    public function deleteRoute(int $id, RouteService $service)
    {
        $service->delete($id);
        $this->dispatch('swal:success', title: 'Eliminada', message: 'Ruta eliminada correctamente.');
    }

    public function showExpediente(int $id, RouteService $service)
    {
        $this->detailRecord = $service->getById($id);
        $this->detailOrigin = $this->detailRecord->origin ?? '';
        $this->detailDestination = $this->detailRecord->destination ?? '';
        $this->detailDestinations = array_values(array_filter(
            $this->detailRecord->destinations ?? [],
            fn ($d) => is_string($d) && trim($d) !== ''
        ));
        if (empty($this->detailDestinations) && $this->detailDestination) {
            $this->detailDestinations = [$this->detailDestination];
        }
        $this->detailRouteSteps = $this->detailRecord->steps
            ->filter(fn ($s) => $s->latitude && $s->longitude)
            ->map(fn ($s) => [
                'lat' => (float) $s->latitude,
                'lng' => (float) $s->longitude,
                'label' => $s->description ?? $s->step_type,
                'type' => $s->step_type,
            ])
            ->values()
            ->toArray();
        $this->showDetail = true;
        $this->dispatch('route:detail-loaded',
            origin: $this->detailOrigin,
            destinations: $this->detailDestinations,
            steps: $this->detailRouteSteps,
        );
    }

    public function addAuthorizedExpense(int $id)
    {
        $this->dispatch('swal:prompt',
            title: 'Agregar gasto autorizado',
            text: 'Indica el monto adicional a autorizar para esta ruta ($):',
            inputType: 'number',
            inputAttributes: 'step="0.01" min="0.01"',
            callback: 'updateAuthorizedExpense',
            params: ['id' => $id],
        );
    }

    #[On('updateAuthorizedExpense')]
    public function updateAuthorizedExpense(int $id, ?string $reason, RouteService $service)
    {
        if (! $reason || (float) $reason <= 0) {
            $this->dispatch('swal:error', title: 'Error', message: 'Debes indicar un monto válido mayor a 0.');

            return;
        }

        $route = $service->getById($id);
        $route->load('vehicle');
        if ($route->vehicle) {
            $route->vehicle->increment('authorized_expense', (float) $reason);
            $this->dispatch('swal:success', title: 'Actualizado', message: 'Saldo agregado a la tarjeta del vehículo correctamente.');
        } else {
            $this->dispatch('swal:error', title: 'Error', message: 'La ruta no tiene un vehículo asignado.');
        }
    }

    public function resetDetail()
    {
        $this->showDetail = false;
        $this->detailRecord = null;
    }

    public function render(VehicleService $vehicleService, RouteService $service)
    {
        $routes = $service->getAllFiltered([
            'search' => trim($this->filterSearch),
            'vehicle_id' => $this->filterVehicleId,
            'status' => $this->filterStatus,
            'date_from' => $this->filterDateFrom,
            'date_to' => $this->filterDateTo,
        ], 15);

        $routes->getCollection()->loadSum('expenses', 'amount');

        return view('livewire.route-manager', [
            'routes' => $routes,
            'vehicles' => $vehicleService->getAll(),
            'drivers' => User::where('role', 'Chofer')->orderBy('name')->get(['id', 'name']),
            'assistants' => User::whereIn('role', ['Chofer', 'Operador', 'Instalador'])->orderBy('name')->get(['id', 'name']),
            'routeStatuses' => Catalog::byGroup('EstatusRuta')->get(),
            'googleMapsApiKey' => config('services.google.maps_api_key'),
        ]);
    }
}
