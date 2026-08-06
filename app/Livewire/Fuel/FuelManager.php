<?php

namespace App\Livewire\Fuel;

use App\Domains\Catalog\Models\Catalog;
use App\Domains\Fuel\DTO\RefuelData;
use App\Domains\Fuel\Models\FuelCard;
use App\Domains\Fuel\Models\FuelStation;
use App\Domains\Fuel\Services\FuelService;
use App\Domains\Route\Models\Route;
use App\Domains\User\Models\User;
use App\Domains\Vehicle\Services\VehicleService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts::app')]
class FuelManager extends Component
{
    use WithFileUploads;
    use WithPagination;

    public bool $showForm = false;

    public bool $showDetail = false;

    public ?int $editId = null;

    public $detailRecord = null;

    public string $filterSearch = '';

    public string $filterVehicleId = '';

    public string $filterStatus = '';

    #[Rule('required|exists:vehicles,id')]
    public string $vehicle_id = '';

    #[Rule('required|regex:/^\d+(\.\d{1,3})?$/u|min:0.1')]
    public string $liters = '';

    #[Rule('required|regex:/^\d+(\.\d{1,2})?$/u|min:0.1')]
    public string $amount = '';

    #[Rule('required')]
    public string $payment_method = 'Tarjeta';

    #[Rule('required|integer|min:0')]
    public string $odometer = '';

    #[Rule('required|exists:users,id')]
    public string $driver_id = '';

    #[Rule('nullable|regex:/^[A-Za-z0-9\-\.\/]+$/u')]
    public string $folio = '';

    #[Rule('nullable|integer|exists:fuel_stations,id')]
    public string $fuel_station_id = '';

    #[Rule('nullable|integer|exists:fuel_cards,id')]
    public string $fuel_card_id = '';

    #[Rule('required|string')]
    public string $status = 'pendiente';

    #[Rule('required|date')]
    public string $date = '';

    #[Rule('nullable|image|max:5120')]
    public $ticket_photo = null;

    public string $existing_ticket_photo = '';

    public array $authorizedFuel = [];

    public string $selectedWeek = '';

    public bool $showRouteDetail = false;

    public ?array $selectedRouteDetail = null;

    public int $unitPage = 1;

    public int $routePage = 1;

    protected const UNIT_PER_PAGE = 8;

    protected const ROUTE_PER_PAGE = 10;

    public function mount(VehicleService $vehicleService)
    {
        $this->selectedWeek = now()->startOfWeek()->toDateString();
        $this->loadAuthorizedFuel($vehicleService);
    }

    public function loadAuthorizedFuel(VehicleService $vehicleService)
    {
        $this->authorizedFuel = $vehicleService->pluckAuthorizedFuel();
    }

    public function updateAuthorizedFuel(int $vehicleId, float $value, VehicleService $vehicleService)
    {
        $vehicleService->updateAuthorizedFuel($vehicleId, $value);
        $this->authorizedFuel[$vehicleId] = $value;
        $this->dispatch('swal:success', title: 'Actualizado', message: 'Límite autorizado actualizado.');
    }

    public function updatedSelectedWeek()
    {
        $this->unitPage = 1;
        $this->routePage = 1;
        $this->resetPage();
    }

    public function previousUnitPage()
    {
        $this->unitPage = max(1, $this->unitPage - 1);
    }

    public function nextUnitPage()
    {
        $this->unitPage++;
    }

    public function previousRoutePage()
    {
        $this->routePage = max(1, $this->routePage - 1);
    }

    public function nextRoutePage()
    {
        $this->routePage++;
    }

    public function resetForm()
    {
        $this->showForm = false;
        $this->editId = null;
        $this->vehicle_id = '';
        $this->liters = '';
        $this->amount = '';
        $this->payment_method = 'Tarjeta';
        $this->odometer = '';
        $this->driver_id = '';
        $this->folio = '';
        $this->fuel_station_id = '';
        $this->fuel_card_id = '';
        $this->status = 'pendiente';
        $this->date = now()->toDateString();
        $this->ticket_photo = null;
        $this->existing_ticket_photo = '';
    }

    public function clearFilters()
    {
        $this->filterSearch = '';
        $this->filterVehicleId = '';
        $this->filterStatus = '';
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

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id, FuelService $service)
    {
        $r = $service->getById($id);
        $this->editId = $r->id;
        $this->vehicle_id = (string) $r->vehicle_id;
        $this->liters = (string) $r->liters;
        $this->amount = (string) $r->amount;
        $this->payment_method = $r->payment_method?->value ?? $r->getRawOriginal('payment_method');
        $this->odometer = (string) ($r->odometer ?? '');
        $this->driver_id = (string) ($r->driver_id ?? '');
        $this->folio = $r->folio ?? '';
        $this->fuel_station_id = $r->fuel_station_id ? (string) $r->fuel_station_id : '';
        $this->fuel_card_id = $r->fuel_card_id ? (string) $r->fuel_card_id : '';
        $this->status = $r->status ?? 'pendiente';
        $this->date = $r->date?->toDateString() ?? now()->toDateString();
        $this->ticket_photo = null;
        $this->existing_ticket_photo = $r->ticket_photo ?? '';
        $this->showForm = true;
    }

    public function save(FuelService $service)
    {
        $this->validate();

        $driver = User::find((int) $this->driver_id);

        $ticketPhotoPath = $this->existing_ticket_photo ?: null;
        if ($this->ticket_photo) {
            $ticketPhotoPath = $this->ticket_photo->store('fuel/tickets', 'public');
        }

        $data = [
            'date' => $this->date,
            'vehicle_id' => $this->vehicle_id,
            'route_id' => null,
            'driver_id' => $this->driver_id,
            'driver_name' => $driver?->name ?: 'Operador',
            'liters' => $this->liters,
            'amount' => $this->amount,
            'price_per_liter' => round((float) $this->amount / (float) $this->liters, 2),
            'payment_method' => $this->payment_method,
            'odometer' => $this->odometer,
            'folio' => $this->folio ?: null,
            'fuel_station_id' => $this->fuel_station_id !== '' ? (int) $this->fuel_station_id : null,
            'fuel_card_id' => $this->fuel_card_id !== '' ? (int) $this->fuel_card_id : null,
            'status' => $this->status,
            'ticket_photo' => $ticketPhotoPath,
        ];

        $dto = RefuelData::fromArray($data);

        if ($this->editId) {
            $service->update($this->editId, $dto);
            $this->dispatch('swal:success', title: 'Actualizado', message: 'Carga de combustible actualizada.');
        } else {
            $service->create($dto);
            $this->dispatch('swal:success', title: 'Registrado', message: 'Carga de combustible registrada.');
        }

        $this->resetForm();
    }

    public function approve(int $id, FuelService $service)
    {
        try {
            $service->approve($id, auth()->id());
            $this->dispatch('swal:success', title: 'Aprobado', message: 'Carga aprobada correctamente.');
        } catch (\DomainException $e) {
            $this->dispatch('swal:error', title: 'Error', message: $e->getMessage());
        }
    }

    public function confirmReject(int $id)
    {
        $this->dispatch('swal:prompt',
            title: 'Rechazar carga',
            text: 'Indica el motivo del rechazo:',
            callback: 'rejectRefuel',
            params: ['id' => $id],
        );
    }

    #[On('rejectRefuel')]
    public function rejectRefuel(int $id, ?string $reason, FuelService $service)
    {
        if (! $reason) {
            $this->dispatch('swal:error', title: 'Error', message: 'Debes indicar un motivo de rechazo.');

            return;
        }
        try {
            $service->reject($id, $reason);
            $this->dispatch('swal:success', title: 'Rechazado', message: 'Carga rechazada correctamente.');
        } catch (\DomainException $e) {
            $this->dispatch('swal:error', title: 'Error', message: $e->getMessage());
        }
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm',
            title: 'Eliminar carga',
            text: '¿Eliminar este registro de combustible?',
            callback: 'deleteRefuel',
            params: ['id' => $id],
        );
    }

    #[On('deleteRefuel')]
    public function deleteRefuel(int $id, FuelService $service)
    {
        $service->delete($id);
        $this->dispatch('swal:success', title: 'Eliminado', message: 'Carga de combustible eliminada.');
    }

    public function showExpediente(int $id, FuelService $service)
    {
        $this->detailRecord = $service->getById($id);
        $this->showDetail = true;
    }

    public function resetDetail()
    {
        $this->showDetail = false;
        $this->detailRecord = null;
    }

    public function showRouteDetail(int $routeId)
    {
        $route = Route::with(['steps', 'refuels', 'vehicle'])->find($routeId);
        if (! $route) {
            return;
        }

        $stepOrder = ['departurePlant', 'arrivalClient', 'startInstallation', 'endInstallation', 'returnToPlant', 'arrivalPlant'];

        $dep = $route->steps->firstWhere('step_type', 'departurePlant');
        $arr = $route->steps->firstWhere('step_type', 'arrivalPlant');
        $distance = ($dep && $arr && $dep->odometer && $arr->odometer)
            ? $arr->odometer - $dep->odometer
            : 0;
        $refuelLiters = (float) $route->refuels->sum('liters');
        $refuelAmount = (float) $route->refuels->sum('amount');
        $tankCapacity = $route->vehicle?->tank_capacity ? (float) $route->vehicle->tank_capacity : 0;

        $stepLabels = [
            'departurePlant' => 'Salida Planta', 'arrivalClient' => 'Llegada Cliente',
            'startInstallation' => 'Inicio Instalación', 'endInstallation' => 'Fin Instalación',
            'returnToPlant' => 'Regreso a Planta', 'arrivalPlant' => 'Llegada a Planta',
        ];
        $levelMap = ['1/4' => 0.25, '1/2' => 0.5, '3/4' => 0.75, 'Lleno' => 1.0];

        $steps = [];
        foreach ($stepOrder as $type) {
            $s = $route->steps->firstWhere('step_type', $type);
            if (! $s) {
                continue;
            }
            $steps[] = [
                'label' => $stepLabels[$type] ?? $type,
                'type' => $type,
                'odometer' => $s->odometer,
                'fuel_level' => $s->fuel_level,
                'has_photo' => ! is_null($s->photo),
                'time' => $s->timestamp?->format('H:i') ?? '—',
            ];
        }

        $segments = [];
        for ($i = 0; $i < count($steps) - 1; $i++) {
            $a = $steps[$i];
            $b = $steps[$i + 1];
            $segDist = ($a['odometer'] && $b['odometer']) ? $b['odometer'] - $a['odometer'] : null;
            $fuelDelta = null;
            if ($a['fuel_level'] && $b['fuel_level'] && isset($levelMap[$a['fuel_level']], $levelMap[$b['fuel_level']])) {
                $fuelDelta = ($levelMap[$a['fuel_level']] - $levelMap[$b['fuel_level']]) * $tankCapacity;
            }
            $segments[] = [
                'from' => $a['label'],
                'to' => $b['label'],
                'distance' => $segDist,
                'fuel_delta' => $fuelDelta !== null ? round($fuelDelta, 1) : null,
                'km_per_liter' => $segDist && $fuelDelta !== null && $fuelDelta > 0 ? round($segDist / $fuelDelta, 1) : null,
            ];
        }

        $reconciliation = null;
        $initialLevel = $dep?->fuel_level;
        $finalLevel = $arr?->fuel_level;
        if ($initialLevel && $finalLevel && $tankCapacity > 0 && isset($levelMap[$initialLevel], $levelMap[$finalLevel])) {
            $initialLiters = $levelMap[$initialLevel] * $tankCapacity;
            $finalLiters = $levelMap[$finalLevel] * $tankCapacity;
            $consumedLiters = $initialLiters + $refuelLiters - $finalLiters;
            $reconciliation = [
                'initial_liters' => round($initialLiters, 1),
                'refueled_liters' => $refuelLiters,
                'expected_liters' => round($initialLiters + $refuelLiters, 1),
                'final_liters' => round($finalLiters, 1),
                'consumed_liters' => max(round($consumedLiters, 1), 0),
            ];
        }

        $this->selectedRouteDetail = [
            'id' => $route->id,
            'client' => $route->client_name,
            'date' => $route->date?->format('d/m/Y') ?? '—',
            'vehicle_plate' => $route->vehicle?->plate ?? '—',
            'driver' => $route->driver_name,
            'distance' => $distance,
            'liters' => $refuelLiters,
            'amount' => $refuelAmount,
            'km_per_liter' => $distance > 0 && $refuelLiters > 0 ? round($distance / $refuelLiters, 1) : null,
            'cost_per_km' => $distance > 0 && $refuelAmount > 0 ? round($refuelAmount / $distance, 2) : null,
            'tank_capacity' => $tankCapacity,
            'steps' => $steps,
            'segments' => $segments,
            'reconciliation' => $reconciliation,
        ];
        $this->showRouteDetail = true;
    }

    public function resetRouteDetail()
    {
        $this->showRouteDetail = false;
        $this->selectedRouteDetail = null;
    }

    public function render(VehicleService $vehicleService, FuelService $service)
    {
        $weekStart = Carbon::parse($this->selectedWeek)->startOfWeek();
        $weekEnd = (clone $weekStart)->endOfWeek();

        $vehicles = $vehicleService->getAll();
        $vehicleIds = $vehicles->pluck('id');

        $allRoutes = Route::whereIn('vehicle_id', $vehicleIds)
            ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->with(['steps', 'refuels' => function ($q) use ($weekStart, $weekEnd) {
                $q->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()]);
            }])
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy('vehicle_id');

        $fuelStats = $vehicles->map(function ($v) use ($weekStart, $weekEnd, $allRoutes) {
            $refuelQuery = $v->refuels()
                ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()]);
            $consumed = (float) $refuelQuery->sum('liters');
            $totalAmount = (float) $refuelQuery->sum('amount');

            $vehicleRoutes = $allRoutes->get($v->id, collect());
            $totalDistance = 0;
            $realConsumed = 0;
            $tankCap = (float) ($v->tank_capacity ?? 0);

            foreach ($vehicleRoutes as $r) {
                $dep = $r->steps->firstWhere('step_type', 'departurePlant');
                $arr = $r->steps->firstWhere('step_type', 'arrivalPlant');
                $d = ($dep && $arr && $dep->odometer && $arr->odometer) ? $arr->odometer - $dep->odometer : 0;
                $totalDistance += $d;

                $maps = ['1/4' => 0.25, '1/2' => 0.5, '3/4' => 0.75, 'Lleno' => 1.0];
                $initialPct = $dep?->fuel_level ? ($maps[$dep->fuel_level] ?? null) : null;
                $finalPct = $arr?->fuel_level ? ($maps[$arr->fuel_level] ?? null) : null;

                if ($initialPct !== null && $finalPct !== null && $tankCap > 0) {
                    $initialLiters = $initialPct * $tankCap;
                    $finalLiters = $finalPct * $tankCap;
                    $refuelLiters = (float) $r->refuels->sum('liters');
                    $realConsumed += max($initialLiters + $refuelLiters - $finalLiters, 0);
                }
            }

            $suggested = $v->authorized_fuel ?? 0;
            if ($suggested <= 0 && $totalDistance > 0) {
                $historicalReal = 0;
                $count = 0;
                for ($i = 1; $i <= 4; $i++) {
                    $ws = now()->subWeeks($i)->startOfWeek();
                    $we = (clone $ws)->endOfWeek();
                    $weekRoutes = Route::where('vehicle_id', $v->id)
                        ->whereBetween('date', [$ws->toDateString(), $we->toDateString()])
                        ->with(['steps', 'refuels'])
                        ->get();
                    $weekReal = 0;
                    foreach ($weekRoutes as $wr) {
                        $d = $wr->steps->firstWhere('step_type', 'departurePlant');
                        $a = $wr->steps->firstWhere('step_type', 'arrivalPlant');
                        $ip = $d?->fuel_level ? ($maps[$d->fuel_level] ?? null) : null;
                        $fp = $a?->fuel_level ? ($maps[$a->fuel_level] ?? null) : null;
                        if ($ip !== null && $fp !== null && $tankCap > 0) {
                            $weekReal += max($ip * $tankCap + (float) $wr->refuels->sum('liters') - $fp * $tankCap, 0);
                        }
                    }
                    if ($weekReal > 0) {
                        $historicalReal += $weekReal;
                        $count++;
                    }
                }
                $suggested = $count > 0 ? round($historicalReal / $count, 1) : round($totalDistance / 6, 1);
            }

            return [
                'id' => $v->id,
                'plate' => $v->plate,
                'consumed' => $consumed,
                'real_consumed' => $realConsumed,
                'authorized' => (float) ($v->authorized_fuel ?? 0),
                'suggested' => $suggested,
                'status' => $v->status,
                'total_distance' => $totalDistance,
                'total_amount' => $totalAmount,
                'km_per_liter' => $totalDistance > 0 && $consumed > 0 ? round($totalDistance / $consumed, 1) : null,
                'real_km_per_liter' => $totalDistance > 0 && $realConsumed > 0 ? round($totalDistance / $realConsumed, 1) : null,
                'cost_per_km' => $totalDistance > 0 && $totalAmount > 0 ? round($totalAmount / $totalDistance, 2) : null,
            ];
        });

        $maxFuel = max($fuelStats->max('real_consumed'), $fuelStats->max('authorized'), 1);

        $routesWithRefuels = Route::whereHas('refuels', function ($q) use ($weekStart, $weekEnd) {
            $q->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()]);
        })->with(['refuels' => function ($q) use ($weekStart, $weekEnd) {
            $q->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()]);
        }, 'steps', 'vehicle'])->orderBy('date', 'desc')->get();

        $levelMap = ['1/4' => 0.25, '1/2' => 0.5, '3/4' => 0.75, 'Lleno' => 1.0];
        $routeConsumption = $routesWithRefuels->map(function ($r) use ($levelMap) {
            $dep = $r->steps->firstWhere('step_type', 'departurePlant');
            $arr = $r->steps->firstWhere('step_type', 'arrivalPlant');
            $distance = ($dep && $arr && $dep->odometer && $arr->odometer)
                ? $arr->odometer - $dep->odometer
                : 0;
            $liters = (float) $r->refuels->sum('liters');
            $amount = (float) $r->refuels->sum('amount');

            $fuelInitial = $dep?->fuel_level;
            $fuelFinal = $arr?->fuel_level;
            $realConsumed = null;
            $tankCap = $r->vehicle?->tank_capacity ? (float) $r->vehicle->tank_capacity : 0;

            if ($fuelInitial && $fuelFinal && isset($levelMap[$fuelInitial], $levelMap[$fuelFinal]) && $tankCap > 0) {
                $realConsumed = round(max($levelMap[$fuelInitial] * $tankCap + $liters - $levelMap[$fuelFinal] * $tankCap, 0), 1);
            }

            return [
                'id' => $r->id,
                'client' => $r->client_name,
                'date' => $r->date?->format('d/m/Y') ?? '—',
                'vehicle_plate' => $r->vehicle?->plate ?? '—',
                'driver_name' => $r->driver_name,
                'distance' => $distance,
                'liters' => $liters,
                'amount' => $amount,
                'km_per_liter' => $distance > 0 && $liters > 0 ? round($distance / $liters, 2) : null,
                'real_km_per_liter' => $distance > 0 && $realConsumed ? round($distance / $realConsumed, 2) : null,
                'cost_per_km' => $distance > 0 && $amount > 0 ? round($amount / $distance, 2) : null,
                'refuel_count' => $r->refuels->count(),
                'fuel_initial' => $fuelInitial,
                'fuel_final' => $fuelFinal,
                'real_consumed' => $realConsumed,
            ];
        });

        $weeks = [];
        for ($i = 0; $i < 8; $i++) {
            $w = now()->subWeeks($i)->startOfWeek();
            $weeks[$w->toDateString()] = 'Semana del '.$w->format('d/m/Y');
        }
        $weeks[now()->startOfWeek()->toDateString()] = 'Esta semana';

        $refuels = $service->getAllFiltered([
            'date_from' => $weekStart->toDateString(),
            'date_to' => $weekEnd->toDateString(),
            'vehicle_id' => $this->filterVehicleId,
            'status' => $this->filterStatus,
            'search' => trim($this->filterSearch),
        ], 10);

        $fuelSummary = [
            'liters' => (float) $fuelStats->sum(fn ($fs) => $fs['real_consumed'] > 0 ? $fs['real_consumed'] : $fs['consumed']),
            'amount' => (float) $fuelStats->sum('total_amount'),
            'distance' => (float) $fuelStats->sum('total_distance'),
            'authorized' => (float) $fuelStats->sum('authorized'),
        ];

        $unitTotal = $fuelStats->count();
        $unitLastPage = max(1, (int) ceil($unitTotal / self::UNIT_PER_PAGE));
        $this->unitPage = min($this->unitPage, $unitLastPage);
        $unitFirstItem = $unitTotal > 0 ? ($this->unitPage - 1) * self::UNIT_PER_PAGE + 1 : 0;
        $unitLastItem = min($this->unitPage * self::UNIT_PER_PAGE, $unitTotal);

        $routeTotal = $routeConsumption->count();
        $routeLastPage = max(1, (int) ceil($routeTotal / self::ROUTE_PER_PAGE));
        $this->routePage = min($this->routePage, $routeLastPage);
        $routeFirstItem = $routeTotal > 0 ? ($this->routePage - 1) * self::ROUTE_PER_PAGE + 1 : 0;
        $routeLastItem = min($this->routePage * self::ROUTE_PER_PAGE, $routeTotal);

        return view('livewire.fuel-manager', [
            'vehicles' => $vehicles,
            'operators' => User::whereIn('role', ['Operador', 'Chofer'])->orderBy('name')->get(),
            'refuels' => $refuels,
            'fuelStats' => $fuelStats->slice(($this->unitPage - 1) * self::UNIT_PER_PAGE, self::UNIT_PER_PAGE)->values(),
            'fuelSummary' => $fuelSummary,
            'unitTotal' => $unitTotal,
            'unitFirstItem' => $unitFirstItem,
            'unitLastItem' => $unitLastItem,
            'unitPage' => $this->unitPage,
            'unitLastPage' => $unitLastPage,
            'maxFuel' => $maxFuel,
            'routeConsumption' => $routeConsumption,
            'routeConsumptionPage' => $routeConsumption->slice(($this->routePage - 1) * self::ROUTE_PER_PAGE, self::ROUTE_PER_PAGE)->values(),
            'routeTotal' => $routeTotal,
            'routeFirstItem' => $routeFirstItem,
            'routeLastItem' => $routeLastItem,
            'routePage' => $this->routePage,
            'routeLastPage' => $routeLastPage,
            'weeks' => $weeks,
            'weekLabel' => $weekStart->format('d/m/Y').' — '.$weekEnd->format('d/m/Y'),
            'fuelStations' => FuelStation::orderBy('name')->get(),
            'fuelCards' => FuelCard::orderBy('card_number')->get(),
            'paymentMethods' => Catalog::byGroup('MetodoPago')->get(),
            'fuelStatuses' => Catalog::byGroup('EstatusCombustible')->get(),
        ]);
    }
}
