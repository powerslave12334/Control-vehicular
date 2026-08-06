<?php

namespace App\Livewire\Reports;

use App\Domains\Fuel\Models\Refuel;
use App\Domains\Incident\Models\Incident;
use App\Domains\Maintenance\Models\Maintenance;
use App\Domains\Route\Models\Route;
use App\Domains\User\Models\User;
use App\Domains\Vehicle\Models\Vehicle;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::app')]
class Reports extends Component
{
    public string $reportType = 'routes';

    public string $dateFrom = '';

    public string $dateTo = '';

    public ?int $vehicleFilter = null;

    public ?int $operatorFilter = null;

    public array $reportData = [];

    public array $totals = [];

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->toDateString();
        $this->dateTo = now()->toDateString();
        $this->generate();
    }

    public function updatedReportType()
    {
        $this->generate();
    }

    public function updatedDateFrom()
    {
        $this->generate();
    }

    public function updatedDateTo()
    {
        $this->generate();
    }

    public function updatedVehicleFilter()
    {
        $this->generate();
    }

    public function updatedOperatorFilter()
    {
        $this->generate();
    }

    public function generate()
    {
        $from = $this->dateFrom ?: now()->startOfMonth()->toDateString();
        $to = $this->dateTo ?: now()->toDateString();

        match ($this->reportType) {
            'routes' => $this->generateRoutes($from, $to),
            'fuel' => $this->generateFuel($from, $to),
            'maintenance' => $this->generateMaintenance($from, $to),
            'incidents' => $this->generateIncidents($from, $to),
            default => $this->generateRoutes($from, $to),
        };

        $this->dispatch('chart:refresh', data: $this->reportData);
    }

    private function generateRoutes(string $from, string $to): void
    {
        $routes = Route::whereBetween('date', [$from, $to])
            ->when($this->vehicleFilter, fn ($q) => $q->where('vehicle_id', $this->vehicleFilter))
            ->when($this->operatorFilter, fn ($q) => $q->where('driver_id', $this->operatorFilter))
            ->with('driver', 'vehicle')
            ->orderBy('date', 'desc')
            ->get();

        $this->reportData = $routes->map(fn ($r) => [
            'date' => $r->date?->format('d/m/Y') ?? '—',
            'client' => $r->client_name,
            'driver' => $r->driver_name ?? '—',
            'vehicle' => $r->vehicle_plate ?? '—',
            'planned_km' => $r->planned_km,
            'actual_km' => $r->actual_km,
            'status' => $r->status?->value ?? $r->status,
            'city' => $r->city,
        ])->toArray();

        $this->totals = [
            'count' => $routes->count(),
            'planned_km' => $routes->sum('planned_km'),
            'actual_km' => $routes->sum('actual_km'),
            'completadas' => $routes->filter(fn ($r) => ($r->status?->value ?? $r->status) === 'Completada')->count(),
        ];
    }

    private function generateFuel(string $from, string $to): void
    {
        $refuels = Refuel::whereBetween('date', [$from, $to])
            ->when($this->vehicleFilter, fn ($q) => $q->where('vehicle_id', $this->vehicleFilter))
            ->when($this->operatorFilter, fn ($q) => $q->where('driver_id', $this->operatorFilter))
            ->with('vehicle', 'driver', 'route')
            ->orderBy('date', 'desc')
            ->get();

        $this->reportData = $refuels->map(fn ($r) => [
            'date' => $r->date?->format('d/m/Y') ?? '—',
            'vehicle' => $r->vehicle?->plate ?? '—',
            'driver' => $r->driver_name ?? '—',
            'route_client' => $r->route?->client_name ?? '—',
            'liters' => $r->liters,
            'amount' => $r->amount,
            'price_per_liter' => $r->price_per_liter,
            'payment_method' => $r->payment_method?->value ?? $r->payment_method,
            'odometer' => $r->odometer,
        ])->toArray();

        $totalLiters = $refuels->sum('liters');
        $totalAmount = $refuels->sum('amount');

        $this->totals = [
            'count' => $refuels->count(),
            'liters' => $totalLiters,
            'amount' => $totalAmount,
            'avg_price' => $totalLiters > 0 ? $totalAmount / $totalLiters : 0,
        ];
    }

    private function generateMaintenance(string $from, string $to): void
    {
        $items = Maintenance::whereBetween('date', [$from, $to])
            ->when($this->vehicleFilter, fn ($q) => $q->where('vehicle_id', $this->vehicleFilter))
            ->with('vehicle')
            ->orderBy('date', 'desc')
            ->get();

        $this->reportData = $items->map(fn ($m) => [
            'date' => $m->date?->format('d/m/Y') ?? '—',
            'vehicle' => $m->vehicle?->plate ?? '—',
            'type' => $m->type?->value ?? $m->type,
            'description' => $m->description ?? '—',
            'cost' => $m->cost ?? 0,
            'workshop' => $m->workshop ?? '—',
            'category' => $m->category?->value ?? $m->category,
        ])->toArray();

        $this->totals = [
            'count' => $items->count(),
            'cost' => $items->sum('cost'),
            'preventivos' => $items->filter(fn ($m) => ($m->type?->value ?? $m->type) === 'Preventivo')->count(),
            'correctivos' => $items->filter(fn ($m) => ($m->type?->value ?? $m->type) === 'Correctivo')->count(),
        ];
    }

    private function generateIncidents(string $from, string $to): void
    {
        $items = Incident::whereBetween('date', [$from, $to])
            ->when($this->vehicleFilter, fn ($q) => $q->where('vehicle_id', $this->vehicleFilter))
            ->when($this->operatorFilter, fn ($q) => $q->where('driver_id', $this->operatorFilter))
            ->with('vehicle', 'driver')
            ->orderBy('date', 'desc')
            ->get();

        $this->reportData = $items->map(fn ($i) => [
            'date' => $i->date?->format('d/m/Y') ?? '—',
            'time' => $i->time ?? '',
            'vehicle' => $i->vehicle?->plate ?? '—',
            'driver' => $i->driver_name ?? '—',
            'type' => $i->type ?? '—',
            'description' => $i->description,
            'severity' => $i->severity?->value ?? $i->severity,
            'status' => $i->status?->value ?? $i->status,
            'cost' => $i->cost ?? 0,
            'location' => $i->location ?? '—',
        ])->toArray();

        $all = collect($this->reportData);

        $this->totals = [
            'count' => $items->count(),
            'reportadas' => $all->where('status', 'Reportada')->count(),
            'atendidas' => $all->where('status', 'Atendida')->count(),
            'resueltas' => $all->where('status', 'Resuelta')->count(),
            'cost_total' => $items->sum('cost'),
        ];
    }

    public function exportCsv()
    {
        $this->generate();

        if (empty($this->reportData)) {
            $this->dispatch('swal:error', title: 'Sin datos', message: 'No hay datos para exportar.');

            return;
        }

        $headers = match ($this->reportType) {
            'routes' => ['Fecha', 'Cliente', 'Conductor', 'Unidad', 'Km Plan', 'Km Real', 'Estado', 'Ciudad'],
            'fuel' => ['Fecha', 'Vehículo', 'Conductor', 'Ruta', 'Litros', 'Monto', 'Precio/L', 'Método Pago', 'Odómetro'],
            'maintenance' => ['Fecha', 'Vehículo', 'Tipo', 'Categoría', 'Descripción', 'Costo', 'Taller'],
            'incidents' => ['Fecha', 'Hora', 'Vehículo', 'Conductor', 'Tipo', 'Descripción', 'Severidad', 'Estado', 'Costo', 'Ubicación'],
            default => [],
        };

        $callback = function () use ($headers) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, $headers);

            foreach ($this->reportData as $row) {
                $line = match ($this->reportType) {
                    'routes' => [
                        $row['date'], $row['client'], $row['driver'], $row['vehicle'],
                        $row['planned_km'], $row['actual_km'], $row['status'], $row['city'],
                    ],
                    'fuel' => [
                        $row['date'], $row['vehicle'], $row['driver'], $row['route_client'],
                        $row['liters'], $row['amount'], $row['price_per_liter'],
                        $row['payment_method'], $row['odometer'],
                    ],
                    'maintenance' => [
                        $row['date'], $row['vehicle'], $row['type'], $row['category'],
                        $row['description'], $row['cost'], $row['workshop'],
                    ],
                    'incidents' => [
                        $row['date'], $row['time'], $row['vehicle'], $row['driver'],
                        $row['type'], $row['description'], $row['severity'],
                        $row['status'], $row['cost'], $row['location'],
                    ],
                    default => [],
                };
                fputcsv($output, $line);
            }
            fclose($output);
        };

        $filename = 'reporte-'.$this->reportType.'-'.now()->format('Ymd-His').'.csv';

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function render()
    {
        return view('livewire.reports', [
            'vehicles' => Vehicle::orderBy('plate')->get(['id', 'brand', 'model', 'plate']),
            'operators' => User::where('role', 'Chofer')->orderBy('name')->get(['id', 'name']),
        ]);
    }
}
