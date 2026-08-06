<?php

namespace App\Livewire\Geolocation;

use App\Domains\Route\Services\RouteService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::app')]
class Geolocation extends Component
{
    public ?int $selectedRouteId = null;
    public array $routePoints = [];
    public array $routeSummary = [];

    public function mount(RouteService $service)
    {
        $routes = $service->getAll();
        if ($routes->count() > 0) {
            $this->selectedRouteId = $routes->first()->id;
            $this->loadRoute($service);
        }
    }

    public function selectRoute(int $routeId, RouteService $service)
    {
        $this->selectedRouteId = $routeId;
        $this->loadRoute($service);
        $this->dispatch('geolocation:update', points: $this->routePoints, origin: $this->routeSummary['origin'], destination: $this->routeSummary['destination']);
    }

    public function loadRoute(RouteService $service)
    {
        $route = $service->getById($this->selectedRouteId);
        if (!$route) return;

        $this->routeSummary = [
            'client' => $route->client_name,
            'city' => $route->city,
            'driver' => $route->driver_name,
            'plate' => $route->vehicle_plate,
            'status' => $route->status?->value ?? $route->status,
            'planned' => $route->planned_km,
            'actual' => $route->actual_km,
            'origin' => $route->origin ?? '',
            'destination' => $route->destination ?? '',
        ];

        $this->routePoints = $route->steps->map(function ($step) {
            $color = match($step->step_type) {
                'departurePlant' => '#E72085',
                'arrivalClient' => '#008FD3',
                'startInstallation' => '#F59E0B',
                'endInstallation' => '#10B981',
                'refuel' => '#8B5CF6',
                'arrivalPlant' => '#059669',
                default => '#64748b',
            };

            $label = match($step->step_type) {
                'departurePlant' => 'Salida Planta',
                'arrivalClient' => 'Llegada Cliente',
                'startInstallation' => 'Inicio Instalación',
                'endInstallation' => 'Fin Instalación',
                'returnToPlant' => 'Regreso a Planta',
                'refuel' => 'Carga Combustible',
                'arrivalPlant' => 'Regreso Planta',
                default => $step->step_type,
            };

            return [
                'lat' => $step->latitude ? (float) $step->latitude : null,
                'lng' => $step->longitude ? (float) $step->longitude : null,
                'label' => $label,
                'type' => $step->step_type,
                'color' => $color,
                'subtitle' => $step->odometer ? number_format($step->odometer) . ' km' : '',
                'timestamp' => $step->timestamp?->format('d/m/Y H:i') ?? '',
                'observations' => $step->observations ?? '',
            ];
        })->filter(fn($p) => $p['lat'] !== null && $p['lng'] !== null)->values()->toArray();
    }

    public function render(RouteService $service)
    {
        return view('livewire.geolocation', [
            'routes' => $service->getAll(),
            'googleMapsApiKey' => config('services.google.maps_api_key'),
        ]);
    }
}
