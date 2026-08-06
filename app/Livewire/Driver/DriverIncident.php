<?php

namespace App\Livewire\Driver;

use App\Domains\Incident\Services\IncidentService;
use App\Domains\Incident\DTO\IncidentData;
use App\Domains\Route\Services\RouteService;
use App\Domains\Catalog\Models\Catalog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts::driver')]
class DriverIncident extends Component
{
    public Route $route;

    #[Rule('required|regex:/^[\pL\s0-9\.\,\-\/\#\(\)]+$/u|max:255')]
    public string $title = '';
    #[Rule('required|regex:/^[\pL\s0-9\.\,\-\/\#\(\)]+$/u')]
    public string $description = '';
    #[Rule('required')]
    public string $severity = 'Baja';

    public function mount(string $routeId, RouteService $routeService)
    {
        $userId = Auth::id();
        $this->route = $routeService->getById((int) $routeId);
        abort_if($this->route->driver_id !== $userId, 403);
    }

    public function save(IncidentService $service)
    {
        $this->validate();

        $dto = IncidentData::fromArray([
            'date' => now()->toDateString(),
            'time' => now()->format('H:i'),
            'route_id' => $this->route->id,
            'vehicle_id' => $this->route->vehicle_id,
            'driver_id' => $this->route->driver_id,
            'driver_name' => $this->route->driver_name ?? Auth::user()->name,
            'title' => $this->title,
            'description' => $this->description,
            'severity' => $this->severity,
            'status' => 'Reportada',
        ]);

        $service->create($dto);

        $this->dispatch('swal:success',
            title: 'Incidencia reportada',
            message: 'Tu supervisor será notificado.',
        );

        return $this->redirect(route('driver.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.driver-incident', [
            'incidentSeverities' => Catalog::byGroup('SeveridadIncidencia')->get(),
        ]);
    }
}
