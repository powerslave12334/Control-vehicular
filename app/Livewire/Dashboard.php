<?php

namespace App\Livewire;

use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Route\Models\Route;
use App\Domains\Fuel\Models\Refuel;
use App\Domains\Maintenance\Models\Maintenance;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::app')]
class Dashboard extends Component
{
    public array $kpi = [];
    public array $fleetPieData = [];
    public array $kmChartData = [];
    public array $fuelChartData = [];
    public array $stats = [];

    public function mount()
    {
        $vehicles = Vehicle::all();
        $routes = Route::all();
        $weekStart = now()->startOfWeek()->toDateString();
        $weekEnd = now()->endOfWeek()->toDateString();
        $refuels = Refuel::whereDate('date', '>=', $weekStart)
            ->whereDate('date', '<=', $weekEnd)
            ->get();
        $maintenances = Maintenance::all();

        $totalVehicles = $vehicles->count();
        $activeCount = $vehicles->where('status', 'Activa')->count();
        $maintenanceCount = $vehicles->where('status', 'En mantenimiento')->count();
        $outOfServiceCount = $vehicles->where('status', 'Fuera de servicio')->count();

        $totalPlannedKm = (float) $routes->sum('planned_km');
        $totalActualKm = (float) $routes->sum('actual_km');

        $totalFuelCost = (float) $refuels->sum('amount');
        $totalLitersConsumed = (float) $refuels->sum('liters');

        $totalWeeklyAuthorizedFuel = (float) $vehicles->sum('authorized_fuel');
        $totalMaintenanceCost = (float) $maintenances->sum('cost');

        $kmPlannedVsReal = $totalPlannedKm > 0 ? ($totalActualKm / $totalPlannedKm) * 100 : 0;
        $fuelEfficiency = $totalLitersConsumed > 0 ? $totalActualKm / $totalLitersConsumed : 0;
        $authorizedVsConsumed = $totalWeeklyAuthorizedFuel > 0 ? ($totalLitersConsumed / $totalWeeklyAuthorizedFuel) * 100 : 0;
        $costPerKm = $totalActualKm > 0 ? $totalFuelCost / $totalActualKm : 0;
        $fleetAvailability = $totalVehicles > 0 ? ($activeCount / $totalVehicles) * 100 : 0;
        $completedInstallations = $routes->where('status', 'Completada')->count();
        $totalInstallations = $routes->count();
        $installationCompliance = $totalInstallations > 0 ? ($completedInstallations / $totalInstallations) * 100 : 0;

        $this->kpi = [
            'kmPlannedVsReal' => round($kmPlannedVsReal, 1),
            'totalPlannedKm' => $totalPlannedKm,
            'totalActualKm' => $totalActualKm,
            'fuelEfficiency' => round($fuelEfficiency, 2),
            'totalLitersConsumed' => $totalLitersConsumed,
            'totalFuelCost' => $totalFuelCost,
            'authorizedVsConsumed' => round($authorizedVsConsumed, 1),
            'totalWeeklyAuthorizedFuel' => $totalWeeklyAuthorizedFuel,
            'costPerKm' => round($costPerKm, 2),
        ];

        $this->fleetPieData = array_values(array_filter([
            ['name' => 'Activas', 'value' => $activeCount, 'color' => '#10b981'],
            ['name' => 'En mantenimiento', 'value' => $maintenanceCount, 'color' => '#f59e0b'],
            ['name' => 'Fuera de servicio', 'value' => $outOfServiceCount, 'color' => '#ef4444'],
        ], fn($d) => $d['value'] > 0));

        $this->kmChartData = $routes
            ->sortByDesc('date')
            ->map(fn($r) => [
                'name' => mb_substr($r->client_name, 0, 12),
                'Planeados' => (float) $r->planned_km,
                'Reales' => (float) $r->actual_km,
                'diff' => (float) $r->actual_km - (float) $r->planned_km,
                'pct' => $r->planned_km > 0 ? round(((float) $r->actual_km / (float) $r->planned_km) * 100) : 0,
                'city' => $r->city,
            ])->values()->toArray();

        $this->fuelChartData = $vehicles->map(fn($v) => [
            'name' => $v->plate,
            'Consumido' => (float) $refuels->where('vehicle_id', $v->id)->sum('liters'),
            'Autorizado' => (float) ($v->authorized_fuel ?? 100),
            'model' => "{$v->brand} {$v->model}",
        ])->values()->toArray();

        $this->stats = [
            'totalVehicles' => $totalVehicles,
            'installationCompliance' => round($installationCompliance, 1),
            'completedInstallations' => $completedInstallations,
            'totalInstallations' => $totalInstallations,
            'fleetAvailability' => round($fleetAvailability, 1),
            'activeCount' => $activeCount,
            'maintenanceCount' => $maintenanceCount,
            'inMaintenancePct' => $totalVehicles > 0 ? round(($maintenanceCount / $totalVehicles) * 100, 1) : 0,
            'outOfServiceCount' => $outOfServiceCount,
            'totalFuelCost' => $totalFuelCost,
            'totalMaintenanceCost' => $totalMaintenanceCost,
            'totalCost' => $totalFuelCost + $totalMaintenanceCost,
        ];
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
