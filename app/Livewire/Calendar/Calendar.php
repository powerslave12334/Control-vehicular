<?php

namespace App\Livewire\Calendar;

use App\Domains\Catalog\Models\Catalog;
use App\Domains\Route\Services\RouteService;
use App\Domains\User\Models\User;
use App\Domains\Vehicle\Services\VehicleService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::app')]
class Calendar extends Component
{
    public string $view = 'semana';

    public string $weekStart = '';

    public array $weekDays = [];

    public string $monthStart = '';

    public array $monthCells = [];

    public array $calendarData = [];

    public array $weekSummary = [];

    public array $monthlyData = [];

    public array $monthSummary = [];

    public string $filterSearch = '';

    public string $filterStatus = '';

    public string $filterVehicleId = '';

    public string $filterOperatorId = '';

    public array $operatorsList = [];

    public bool $showDetail = false;

    public $detailRecord = null;

    public function mount(RouteService $routeService)
    {
        $this->weekStart = now()->startOfWeek()->toDateString();
        $this->monthStart = now()->startOfMonth()->toDateString();
        $this->loadWeek($routeService);
    }

    public function switchView(string $view, RouteService $routeService)
    {
        $this->view = $view;
        if ($view === 'mes') {
            $this->loadMonth($routeService);
        } else {
            $this->loadWeek($routeService);
        }
    }

    public function previous(RouteService $routeService)
    {
        if ($this->view === 'mes') {
            $this->monthStart = Carbon::parse($this->monthStart)->subMonth()->toDateString();
            $this->loadMonth($routeService);
        } else {
            $this->weekStart = Carbon::parse($this->weekStart)->subWeek()->toDateString();
            $this->loadWeek($routeService);
        }
    }

    public function next(RouteService $routeService)
    {
        if ($this->view === 'mes') {
            $this->monthStart = Carbon::parse($this->monthStart)->addMonth()->toDateString();
            $this->loadMonth($routeService);
        } else {
            $this->weekStart = Carbon::parse($this->weekStart)->addWeek()->toDateString();
            $this->loadWeek($routeService);
        }
    }

    public function today(RouteService $routeService)
    {
        if ($this->view === 'mes') {
            $this->monthStart = now()->startOfMonth()->toDateString();
            $this->loadMonth($routeService);
        } else {
            $this->weekStart = now()->startOfWeek()->toDateString();
            $this->loadWeek($routeService);
        }
    }

    public function loadWeek(RouteService $routeService)
    {
        $start = Carbon::parse($this->weekStart);
        $end = $start->copy()->endOfWeek();

        $this->weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $this->weekDays[] = $start->copy()->addDays($i);
        }

        $this->operatorsList = $this->loadOperators();

        $routes = $this->applyFilters($routeService->getByDateRange($start->toDateString(), $end->toDateString()));

        $this->calendarData = [];

        foreach ($routes as $r) {
            $driverId = $r->driver_id;
            $dayIndex = Carbon::parse($r->date)->dayOfWeek;

            $this->calendarData[$driverId][$dayIndex][] = [
                'id' => $r->id,
                'code' => $r->code,
                'client' => $r->client_name,
                'city' => $r->city,
                'plate' => $r->vehicle_plate,
                'status' => $r->status?->value ?? $r->status,
                'planned_km' => $r->planned_km,
            ];
        }

        $this->weekSummary = $this->summarize($routes);
    }

    public function loadMonth(RouteService $routeService)
    {
        $first = Carbon::parse($this->monthStart)->startOfMonth();
        $gridStart = $first->copy()->startOfWeek();
        $gridEnd = $first->copy()->endOfMonth()->endOfWeek();

        $this->monthCells = [];
        for ($d = $gridStart->copy(); $d->lte($gridEnd); $d->addDay()) {
            $this->monthCells[] = $d->copy();
        }

        $this->operatorsList = $this->loadOperators();

        $routes = $this->applyFilters($routeService->getByDateRange($gridStart->toDateString(), $gridEnd->toDateString()));

        $this->monthlyData = [];

        foreach ($routes as $r) {
            $dateKey = Carbon::parse($r->date)->toDateString();

            $this->monthlyData[$dateKey][] = [
                'id' => $r->id,
                'code' => $r->code,
                'client' => $r->client_name,
                'plate' => $r->vehicle_plate,
                'driver' => $r->driver_name,
                'status' => $r->status?->value ?? $r->status,
                'planned_km' => $r->planned_km,
            ];
        }

        $monthRoutes = $routes->filter(fn ($r) => Carbon::parse($r->date)->isSameMonth($first));

        $this->monthSummary = $this->summarize($monthRoutes);
    }

    protected function loadOperators(): array
    {
        return User::whereIn('role', ['Chofer', 'Operador', 'Instalador'])
            ->orderBy('name')
            ->get(['id', 'name'])
            ->toArray();
    }

    protected function summarize(Collection $routes): array
    {
        return [
            'routes' => $routes->count(),
            'km' => (float) $routes->sum('planned_km'),
            'active' => $routes->filter(fn ($r) => in_array($r->status?->value ?? $r->status, ['En tránsito', 'Instalando']))->count(),
            'completed' => $routes->filter(fn ($r) => ($r->status?->value ?? $r->status) === 'Completada')->count(),
            'cancelled' => $routes->filter(fn ($r) => ($r->status?->value ?? $r->status) === 'Cancelada')->count(),
        ];
    }

    public function updatedFilterSearch()
    {
        $this->reload(app(RouteService::class));
    }

    public function updatedFilterStatus()
    {
        $this->reload(app(RouteService::class));
    }

    public function updatedFilterVehicleId()
    {
        $this->reload(app(RouteService::class));
    }

    public function updatedFilterOperatorId()
    {
        $this->reload(app(RouteService::class));
    }

    public function clearFilters()
    {
        $this->filterSearch = '';
        $this->filterStatus = '';
        $this->filterVehicleId = '';
        $this->filterOperatorId = '';
        $this->reload(app(RouteService::class));
    }

    protected function reload(RouteService $routeService)
    {
        if ($this->view === 'mes') {
            $this->loadMonth($routeService);
        } else {
            $this->loadWeek($routeService);
        }
    }

    protected function applyFilters(Collection $routes): Collection
    {
        if ($this->filterOperatorId !== '') {
            $routes = $routes->filter(fn ($r) => (string) $r->driver_id === $this->filterOperatorId);
        }

        if ($this->filterVehicleId !== '') {
            $routes = $routes->filter(fn ($r) => (string) $r->vehicle_id === $this->filterVehicleId);
        }

        if ($this->filterStatus !== '') {
            $routes = $routes->filter(fn ($r) => ($r->status?->value ?? $r->status) === $this->filterStatus);
        }

        if (trim($this->filterSearch) !== '') {
            $search = mb_strtolower(trim($this->filterSearch));
            $routes = $routes->filter(function ($r) use ($search) {
                $haystack = mb_strtolower(implode(' ', array_filter([
                    $r->client_name,
                    $r->code,
                    $r->vehicle_plate,
                    $r->driver_name,
                ])));

                return str_contains($haystack, $search);
            });
        }

        return $routes;
    }

    public function showExpediente(int $id, RouteService $service)
    {
        $this->detailRecord = $service->getById($id);
        $this->showDetail = true;
    }

    public function resetDetail()
    {
        $this->showDetail = false;
        $this->detailRecord = null;
    }

    public function render()
    {
        return view('livewire.calendar', [
            'weekRange' => Carbon::parse($this->weekStart)->format('d/m/Y').' — '.Carbon::parse($this->weekStart)->copy()->endOfWeek()->format('d/m/Y'),
            'monthRange' => ucfirst(Carbon::parse($this->monthStart)->locale('es')->isoFormat('MMMM YYYY')),
            'vehicles' => app(VehicleService::class)->getAll(),
            'routeStatuses' => Catalog::byGroup('EstatusRuta')->get(),
        ]);
    }
}
