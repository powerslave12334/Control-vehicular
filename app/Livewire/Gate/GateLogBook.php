<?php

namespace App\Livewire\Gate;

use App\Domains\Catalog\Models\Catalog;
use App\Domains\Gate\Models\GateLog;
use App\Domains\Gate\Services\GateService;
use App\Domains\Vehicle\Services\VehicleService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts::app')]
class GateLogBook extends Component
{
    use WithPagination;

    public string $filterSearch = '';

    public string $filterType = '';

    public string $filterVehicleId = '';

    public string $filterDateFrom = '';

    public string $filterDateTo = '';

    public string $filterFuel = '';

    public string $filterSpare = '';

    public bool $showDetail = false;

    public $detailRecord = null;

    public function updatedFilterSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function updatedFilterVehicleId()
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

    public function updatedFilterFuel()
    {
        $this->resetPage();
    }

    public function updatedFilterSpare()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['filterSearch', 'filterType', 'filterVehicleId', 'filterDateFrom', 'filterDateTo', 'filterFuel', 'filterSpare']);
        $this->resetPage();
    }

    public function openDetail(int $id, GateService $service)
    {
        $this->detailRecord = $service->getById($id);
        $this->showDetail = true;
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->detailRecord = null;
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', title: 'Eliminar', text: '¿Eliminar este registro?', callback: 'deleteGateLog', params: ['id' => $id]);
    }

    #[On('deleteGateLog')]
    public function deleteGateLog(int $id, GateService $service)
    {
        $service->delete($id);
        $this->dispatch('swal:success', title: 'Eliminado');
    }

    public function exportCsv(GateService $service)
    {
        $logs = $service->getAllFiltered([
            'search' => trim($this->filterSearch),
            'type' => $this->filterType,
            'vehicle_id' => $this->filterVehicleId,
            'date_from' => $this->filterDateFrom,
            'date_to' => $this->filterDateTo,
            'fuel_level' => $this->filterFuel !== '' ? (int) $this->filterFuel : '',
            'has_spare_tire' => $this->filterSpare !== '' ? (bool) $this->filterSpare : '',
        ], 100000);

        if ($logs->isEmpty()) {
            $this->dispatch('swal:error', title: 'Sin datos', message: 'No hay registros que coincidan con los filtros.');

            return;
        }

        $checklistLabels = GateManager::CHECKLIST_LABELS;

        $headers = ['Fecha', 'Hora', 'Sentido', 'Folio', 'Placa', 'Conductor', 'Kilometraje', 'Gasolina', 'Llanta refacción', 'Estado', 'Confirmado', 'Check list', 'Observaciones', 'Registrado por'];

        $callback = function () use ($logs, $headers, $checklistLabels) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, $headers);

            foreach ($logs as $log) {
                $checklist = collect($log->checklist ?? [])
                    ->map(fn ($key) => $checklistLabels[$key] ?? $key)
                    ->join(' | ');

                fputcsv($output, [
                    $log->logged_at?->format('d/m/Y') ?? '—',
                    $log->logged_at?->format('H:i') ?? '—',
                    $log->type->value === 'entry' ? 'Entrada' : 'Salida',
                    $log->route_folio ?? '—',
                    $log->vehicle?->plate ?? '—',
                    $log->driver_name ?? '—',
                    $log->initial_odometer !== null ? number_format($log->initial_odometer, 2) : '—',
                    $log->fuel_level !== null ? $log->fuel_level.'%' : '—',
                    $log->has_spare_tire ? 'Sí' : 'No',
                    $log->vehicle_condition ?? '—',
                    $log->confirmed ? 'Sí' : 'No',
                    $checklist ?: '—',
                    $log->notes ?? '—',
                    $log->creator?->name ?? '—',
                ]);
            }
            fclose($output);
        };

        $filename = 'bitacora-porteria-'.now()->format('Ymd-His').'.csv';

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function render(GateService $gateService, VehicleService $vehicleService)
    {
        $logs = $gateService->getAllFiltered([
            'search' => trim($this->filterSearch),
            'type' => $this->filterType,
            'vehicle_id' => $this->filterVehicleId,
            'date_from' => $this->filterDateFrom,
            'date_to' => $this->filterDateTo,
            'fuel_level' => $this->filterFuel !== '' ? (int) $this->filterFuel : '',
            'has_spare_tire' => $this->filterSpare !== '' ? (bool) $this->filterSpare : '',
        ], 15);

        return view('livewire.gate-log-book', [
            'logs' => $logs,
            'vehicles' => $vehicleService->getAll(),
            'fuelLevels' => Catalog::byGroup('NivelCombustible')->orderBy('value', 'desc')->get(),
            'checklistLabels' => GateManager::CHECKLIST_LABELS,
        ]);
    }
}
