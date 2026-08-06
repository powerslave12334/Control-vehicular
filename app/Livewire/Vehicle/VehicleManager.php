<?php

namespace App\Livewire\Vehicle;

use App\Domains\Vehicle\Services\VehicleService;
use App\Domains\Vehicle\DTO\VehicleData;
use App\Domains\User\Models\User;
use App\Domains\Catalog\Models\Catalog;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts::app')]
class VehicleManager extends Component
{
    public bool $showForm = false;
    public bool $showDetail = false;
    public ?int $editId = null;
    public $detailVehicle = null;

    public string $search = '';
    public string $filterStatus = '';
    public string $filterFuelType = '';
    public string $filterVehicleType = '';
    public string $filterGps = '';
    public string $filterDriverId = '';

    #[Rule('required|regex:/^[\pL\s0-9]+$/u')]
    public string $brand = '';
    #[Rule('required|regex:/^[\pL\s0-9]+$/u')]
    public string $model = '';
    #[Rule('required|integer|min:2000|max:2099')]
    public string $year = '';
    #[Rule('required|regex:/^[A-Za-z0-9\-]+$/u')]
    public string $plate = '';
    #[Rule('required')]
    public string $fuel_type = 'Gasolina';
    #[Rule('required')]
    public string $cargo_capacity = '1.0 Toneladas';
    #[Rule('required|regex:/^\d+(\.\d{1,2})?$/u|min:0')]
    public string $tank_capacity = '';
    #[Rule('boolean')]
    public bool $gps_installed = true;
    #[Rule('required|regex:/^[A-Za-z0-9]+$/u')]
    public string $vin = '';
    #[Rule('nullable|regex:/^[\pL\s0-9]+$/u')]
    public string $color = '';
    #[Rule('nullable|regex:/^[\pL\s0-9]+$/u')]
    public string $engine = '';
    #[Rule('nullable|date')]
    public string $acquisition_date = '';
    #[Rule('nullable|regex:/^\d+(\.\d{1,2})?$/u|min:0')]
    public string $acquisition_cost = '';
    #[Rule('nullable|string')]
    public string $vehicle_type = '';
    #[Rule('nullable|integer|exists:users,id')]
    public string $assigned_driver_id = '';
    #[Rule('required|integer|min:0')]
    public string $current_odometer = '1000';
    #[Rule('required|regex:/^\d+(\.\d{1,2})?$/u|min:0')]
    public string $authorized_fuel = '100';
    #[Rule('nullable|regex:/^[\pL\s0-9\.\,\-\/\#\(\)]+$/u')]
    public string $notes = '';

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->showForm = false;
        $this->editId = null;
        $this->brand = '';
        $this->model = '';
        $this->year = '';
        $this->plate = '';
        $this->fuel_type = 'Gasolina';
        $this->cargo_capacity = '1.0 Toneladas';
        $this->tank_capacity = '';
        $this->gps_installed = true;
        $this->vin = '';
        $this->color = '';
        $this->engine = '';
        $this->acquisition_date = '';
        $this->acquisition_cost = '';
        $this->vehicle_type = '';
        $this->assigned_driver_id = '';
        $this->current_odometer = '1000';
        $this->authorized_fuel = '100';
        $this->notes = '';
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id, VehicleService $service)
    {
        $v = $service->getById($id);
        $this->editId = $v->id;
        $this->brand = $v->brand;
        $this->model = $v->model;
        $this->year = (string) $v->year;
        $this->plate = $v->plate;
        $this->fuel_type = $v->fuel_type;
        $this->cargo_capacity = $v->cargo_capacity;
        $this->tank_capacity = (string) $v->tank_capacity;
        $this->gps_installed = $v->gps_installed;
        $this->vin = $v->vin;
        $this->color = $v->color ?? '';
        $this->engine = $v->engine ?? '';
        $this->acquisition_date = $v->acquisition_date?->toDateString() ?? '';
        $this->acquisition_cost = $v->acquisition_cost ? (string) $v->acquisition_cost : '';
        $this->vehicle_type = (string) ($v->vehicle_type ?? '');
        $this->assigned_driver_id = (string) ($v->assigned_driver_id ?? '');
        $this->current_odometer = (string) $v->current_odometer;
        $this->authorized_fuel = (string) $v->authorized_fuel;
        $this->notes = $v->notes ?? '';
        $this->showForm = true;
    }

    public function save(VehicleService $service)
    {
        $this->validate();

        $data = [
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => (int) $this->year,
            'plate' => strtoupper($this->plate),
            'fuel_type' => $this->fuel_type,
            'cargo_capacity' => $this->cargo_capacity,
            'tank_capacity' => (float) $this->tank_capacity,
            'gps_installed' => $this->gps_installed,
            'vin' => strtoupper($this->vin),
            'color' => $this->color ?: null,
            'engine' => $this->engine ?: null,
            'acquisition_date' => $this->acquisition_date ?: null,
            'acquisition_cost' => $this->acquisition_cost !== '' ? (float) $this->acquisition_cost : null,
            'vehicle_type' => $this->vehicle_type ?: null,
            'assigned_driver_id' => $this->assigned_driver_id !== '' ? (int) $this->assigned_driver_id : null,
            'current_odometer' => (int) $this->current_odometer,
            'authorized_fuel' => (float) $this->authorized_fuel,
            'notes' => $this->notes,
        ];

        if ($this->editId) {
            $dto = VehicleData::fromArray($data);
            $service->update($this->editId, $dto);
            $this->dispatch('swal:success', title: 'Actualizado', message: 'Vehículo actualizado correctamente.');
        } else {
            $data['status'] = 'Activa';
            $data['last_maintenance'] = now()->toDateString();
            $data['next_maintenance'] = now()->addDays(90)->toDateString();
            $data['incidents_count'] = 0;
            $dto = VehicleData::fromArray($data);
            $service->create($dto);
            $this->dispatch('swal:success', title: 'Creado', message: 'Vehículo creado correctamente.');
        }

        $this->resetForm();
    }

    public function changeStatus(int $id, string $status, VehicleService $service)
    {
        $v = $service->getById($id);
        $v->update(['status' => $status]);
        $this->dispatch('swal:success', title: 'Estado actualizado', message: "Vehículo ahora está: $status");
    }

    public function showExpediente(int $id, VehicleService $service)
    {
        $this->detailVehicle = $service->getById($id)->load(['assignedDriver']);
        $this->showDetail = true;
    }

    public function resetDetail()
    {
        $this->showDetail = false;
        $this->detailVehicle = null;
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm',
            title: 'Eliminar vehículo',
            text: '¿Estás seguro? Esta acción no se puede deshacer.',
            callback: 'deleteVehicle',
            params: ['id' => $id],
        );
    }

    #[On('deleteVehicle')]
    public function deleteVehicle(int $id, VehicleService $service)
    {
        $service->delete($id);
        $this->dispatch('swal:success', title: 'Eliminado', message: 'Vehículo eliminado correctamente.');
    }

    public function render(VehicleService $service)
    {
        return view('livewire.vehicle-manager', [
            'vehicles' => $service->getAllFiltered([
                'search' => $this->search,
                'status' => $this->filterStatus,
                'fuel_type' => $this->filterFuelType,
                'vehicle_type' => $this->filterVehicleType,
                'gps_installed' => $this->filterGps,
                'assigned_driver_id' => $this->filterDriverId,
            ], 6),
            'drivers' => User::where('role', 'Chofer')->orderBy('name')->get(),
            'fuelTypes' => Catalog::byGroup('TipoCombustible')->get(),
            'cargoCapacities' => Catalog::byGroup('CapacidadCarga')->get(),
            'vehicleTypes' => Catalog::byGroup('TipoVehiculo')->get(),
        ]);
    }
}
