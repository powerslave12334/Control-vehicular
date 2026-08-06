<?php

namespace App\Livewire\Driver;

use App\Domains\Catalog\Models\Catalog;
use App\Domains\Fuel\Models\Refuel;
use App\Domains\Incident\Models\Incident;
use App\Domains\Route\Models\Route;
use App\Domains\Route\Models\RouteStep;
use App\Domains\Route\Models\ExtraordinaryMovement;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts::driver')]
class DriverRoute extends Component
{
    use WithFileUploads;

    public Route $route;
    public array $routeInfo = [];
    public array $steps = [];
    public array $extraMovements = [];
    public array $incidents = [];

    public ?string $photosStepId = null;
    public $photo;

    public string $em_type = '';
    public string $em_description = '';
    public $em_photo;

    public string $inc_description = '';
    public string $inc_severity = 'Baja';

    public ?RouteStep $currentStep = null;
    public string $formOdometer = '';
    public string $formFuelLevel = '';
    public string $formObservations = '';
    public $formPhoto;
    public ?float $currentLat = null;
    public ?float $currentLng = null;

    public array $refuels = [];
    public bool $showRefuelForm = false;
    public string $refuelOdometer = '';
    public string $refuelAmount = '';
    public string $refuelLiters = '';
    public string $refuelFuelLevel = '';
    public $refuelTicketPhoto;

    public bool $showTripSummary = false;
    public ?array $tripSummary = null;

    public function mount(string $routeId)
    {
        $userId = Auth::id();

        $this->route = Route::with('vehicle', 'driver')
            ->where('id', $routeId)
            ->where('driver_id', $userId)
            ->firstOrFail();

        $this->routeInfo = $this->route->toArray();
        $this->loadSteps();
        $this->loadExtraMovements();
        $this->loadIncidents();
        $this->loadRefuels();
        $this->findCurrentStep();

        if (!$this->currentStep && count($this->steps) > 0) {
            $this->computeTripSummary();
        }
    }

    public function loadSteps()
    {
        $stepOrder = ['departurePlant', 'arrivalClient', 'startInstallation', 'endInstallation', 'returnToPlant', 'arrivalPlant'];
        $this->steps = RouteStep::where('route_id', $this->route->id)
            ->orderByRaw('FIELD(step_type, "' . implode('","', $stepOrder) . '")')
            ->get()
            ->toArray();
    }

    public function findCurrentStep()
    {
        $stepOrder = ['departurePlant', 'arrivalClient', 'startInstallation', 'endInstallation', 'returnToPlant', 'arrivalPlant'];
        $this->currentStep = RouteStep::where('route_id', $this->route->id)
            ->whereNull('photo')
            ->orderByRaw('FIELD(step_type, "' . implode('","', $stepOrder) . '")')
            ->first();
    }

    public function loadExtraMovements()
    {
        $this->extraMovements = ExtraordinaryMovement::where('route_id', $this->route->id)
            ->orderBy('timestamp', 'desc')
            ->get()
            ->toArray();
    }

    public function loadIncidents()
    {
        $this->incidents = Incident::where('route_id', $this->route->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function loadRefuels()
    {
        $this->refuels = Refuel::where('route_id', $this->route->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function completeCurrentStep()
    {
        if (!$this->currentStep) return;

        $minOdo = $this->route->vehicle->current_odometer;

        $rules = ['formPhoto' => 'required|image|max:5120'];
        if ($this->currentStep->step_type === 'departurePlant' || $this->currentStep->step_type === 'arrivalPlant') {
            $rules['formOdometer'] = "required|numeric|min:{$minOdo}";
        }
        $rules['formFuelLevel'] = 'nullable|string|max:50';
        $rules['formObservations'] = 'nullable|string|max:500';
        $rules['currentLat'] = 'required|numeric';
        $rules['currentLng'] = 'required|numeric';
        $this->validate($rules);

        $data = [
            'timestamp' => now(),
            'fuel_level' => $this->formFuelLevel ?: null,
            'observations' => $this->formObservations ?: null,
            'latitude' => $this->currentLat,
            'longitude' => $this->currentLng,
        ];

        if ($this->currentStep->step_type === 'departurePlant' || $this->currentStep->step_type === 'arrivalPlant') {
            $data['odometer'] = (int) $this->formOdometer;
        }

        if ($this->formPhoto) {
            $data['photo'] = $this->formPhoto->store('driver/photos');
        }

        $this->currentStep->update($data);
        $this->updateRouteStatus();

        if ($this->formOdometer !== '') {
            $this->route->vehicle->update(['current_odometer' => (int) $this->formOdometer]);
        }

        $this->formOdometer = '';
        $this->formFuelLevel = '';
        $this->formObservations = '';
        $this->formPhoto = null;
        $this->loadSteps();
        $this->routeInfo = $this->route->fresh()->toArray();
        $this->findCurrentStep();

        if (!$this->currentStep) {
            $this->computeTripSummary();
            $this->dispatch('swal:success', title: 'Viaje completado', message: 'Todos los pasos han sido registrados.');
        } else {
            $this->dispatch('swal:success', title: 'Paso completado', message: 'Continúa con el siguiente paso.');
        }
    }

    private function computeTripSummary()
    {
        $dep = $this->route->steps->firstWhere('step_type', 'departurePlant');
        $arr = $this->route->steps->firstWhere('step_type', 'arrivalPlant');
        $distance = ($dep && $arr && $dep->odometer && $arr->odometer)
            ? $arr->odometer - $dep->odometer
            : 0;

        $totalLiters = (float) Refuel::where('route_id', $this->route->id)->sum('liters');
        $totalAmount = (float) Refuel::where('route_id', $this->route->id)->sum('amount');
        $kmPerLiter = $distance > 0 && $totalLiters > 0 ? round($distance / $totalLiters, 2) : null;
        $costPerKm = $distance > 0 && $totalAmount > 0 ? round($totalAmount / $distance, 2) : null;

        $this->tripSummary = [
            'distance' => $distance,
            'total_liters' => $totalLiters,
            'total_amount' => $totalAmount,
            'km_per_liter' => $kmPerLiter,
            'cost_per_km' => $costPerKm,
        ];
        $this->showTripSummary = true;
    }

    private function updateRouteStatus()
    {
        $completed = RouteStep::where('route_id', $this->route->id)
            ->whereNotNull('photo')
            ->count();
        $total = RouteStep::where('route_id', $this->route->id)->count();

        $statusMap = [
            'departurePlant' => 'En tránsito',
            'arrivalClient' => 'En tránsito',
            'startInstallation' => 'Instalando',
            'endInstallation' => 'Instalando',
            'returnToPlant' => 'En tránsito',
            'arrivalPlant' => 'Completada',
        ];

        if ($completed === $total && $total > 0) {
            $this->route->update(['status' => 'Completada']);
        } elseif ($completed > 0) {
            $lastStep = RouteStep::where('route_id', $this->route->id)
                ->whereNotNull('photo')
                ->latest('timestamp')
                ->first();
            if ($lastStep && isset($statusMap[$lastStep->step_type])) {
                $this->route->update(['status' => $statusMap[$lastStep->step_type]]);
            }
        }
    }

    public function toggleRefuelForm()
    {
        $this->showRefuelForm = !$this->showRefuelForm;
    }

    public function saveRefuel()
    {
        $minOdo = $this->route->vehicle->current_odometer;

        $this->validate([
            'refuelOdometer' => "nullable|integer|min:{$minOdo}",
            'refuelAmount' => 'required|numeric|min:0.01',
            'refuelLiters' => 'required|numeric|min:0.01',
            'refuelFuelLevel' => 'nullable|string|max:50',
            'refuelTicketPhoto' => 'nullable|image|max:5120',
        ]);

        $data = [
            'date' => now()->toDateString(),
            'route_id' => $this->route->id,
            'vehicle_id' => $this->route->vehicle_id,
            'driver_id' => Auth::id(),
            'driver_name' => Auth::user()->name,
            'liters' => (float) $this->refuelLiters,
            'amount' => (float) $this->refuelAmount,
            'price_per_liter' => (float) $this->refuelLiters > 0
                ? round((float) $this->refuelAmount / (float) $this->refuelLiters, 2)
                : 0,
            'payment_method' => 'Efectivo',
            'odometer' => $this->refuelOdometer !== '' ? (int) $this->refuelOdometer : null,
            'fuel_level' => $this->refuelFuelLevel ?: null,
        ];

        if ($this->refuelTicketPhoto) {
            $data['ticket_photo'] = $this->refuelTicketPhoto->store('driver/tickets');
        }

        Refuel::create($data);

        if ($this->refuelOdometer !== '') {
            $this->route->vehicle->update(['current_odometer' => (int) $this->refuelOdometer]);
        }

        $this->refuelOdometer = '';
        $this->refuelAmount = '';
        $this->refuelLiters = '';
        $this->refuelFuelLevel = '';
        $this->refuelTicketPhoto = null;
        $this->showRefuelForm = false;
        $this->loadRefuels();
        $this->dispatch('swal:success', title: 'Carga registrada', message: number_format((float) $data['liters'], 2) . 'L registrados correctamente.');
    }

    public function saveExtraMovement()
    {
        $this->validate([
            'em_type' => 'required|string',
            'em_description' => 'required|string',
            'em_photo' => 'nullable|image|max:5120',
        ]);

        $data = [
            'route_id' => $this->route->id,
            'type' => $this->em_type,
            'description' => $this->em_description,
            'timestamp' => now(),
        ];

        if ($this->em_photo) {
            $data['photo'] = $this->em_photo->store('driver/movements');
        }

        ExtraordinaryMovement::create($data);

        $this->em_type = '';
        $this->em_description = '';
        $this->em_photo = null;
        $this->loadExtraMovements();
        $this->dispatch('swal:success', title: 'Guardado', message: 'Movimiento extra registrado.');
    }

    public function saveIncident()
    {
        $this->validate([
            'inc_description' => 'required|string',
            'inc_severity' => 'required|string',
        ]);

        Incident::create([
            'route_id' => $this->route->id,
            'date' => now()->toDateString(),
            'time' => now()->format('H:i'),
            'vehicle_id' => $this->route->vehicle_id,
            'driver_id' => Auth::id(),
            'driver_name' => Auth::user()->name,
            'description' => $this->inc_description,
            'severity' => $this->inc_severity,
            'status' => 'Reportada',
        ]);

        $this->inc_description = '';
        $this->inc_severity = 'Baja';
        $this->loadIncidents();
        $this->dispatch('swal:success', title: 'Reportada', message: 'Falla reportada correctamente.');
    }

    public function render()
    {
        return view('livewire.driver-route', [
            'movementTypes' => Catalog::byGroup('MovimientoExtra')->get(),
            'incidentSeverities' => Catalog::byGroup('SeveridadIncidencia')->get(),
            'currentOdometer' => $this->route->vehicle->current_odometer,
            'tankCapacity' => (float) ($this->route->vehicle->tank_capacity ?? 0),
        ]);
    }
}
