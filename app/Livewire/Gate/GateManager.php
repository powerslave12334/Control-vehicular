<?php

namespace App\Livewire\Gate;

use App\Domains\Catalog\Models\Catalog;
use App\Domains\Gate\Models\GateLog;
use App\Domains\Gate\Services\GateService;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Vehicle\Services\VehicleService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts::gate')]
class GateManager extends Component
{
    use WithFileUploads;

    public const CHECKLIST_LABELS = [
        'luces' => 'Luces',
        'llantas' => 'Llantas',
        'frenos' => 'Frenos',
        'espejos' => 'Espejos',
        'carroceria' => 'Carrocería',
        'extintor' => 'Extintor',
        'triangulos' => 'Triángulos',
        'documentos' => 'Documentos',
        'cinturones' => 'Cinturones',
        'aceite' => 'Aceite',
        'liquido_frenos' => 'Líq. Frenos',
    ];

    public string $vehicle_id = '';
    public string $driver_name = '';
    public string $type = 'entry';
    public $photo;
    public $driver_photo;
    public bool $confirmed = false;
    public string $notes = '';

    public string $vehicle_plate = '';
    public string $vehicle_type_name = '';
    public string $route_folio = '';
    public string $initial_odometer = '';
    public string $fuel_level = '';
    public bool $has_spare_tire = false;
    public string $vehicle_condition = '';

    public string $entry_date = '';
    public string $entry_time = '';
    public string $exit_date = '';
    public string $exit_time = '';
    public string $signature = '';
    public array $checklistItems = [];

    public array $checklistOptions = [
        'luces' => 'Luces (delanteras, traseras, direccionales)',
        'llantas' => 'Llantas (presión y desgaste)',
        'frenos' => 'Frenos (pedal y respuesta)',
        'espejos' => 'Espejos laterales',
        'carroceria' => 'Carrocería (golpes o daños visibles)',
        'extintor' => 'Extintor (carga y vigencia)',
        'triangulos' => 'Triángulos de seguridad',
        'documentos' => 'Documentos (tarjeta circulación, seguro)',
        'cinturones' => 'Cinturones de seguridad',
        'aceite' => 'Nivel de aceite',
        'liquido_frenos' => 'Agua / Líquido de frenos',
    ];

    public function mount()
    {
        $now = now();
        $this->entry_date = $now->toDateString();
        $this->entry_time = $now->format('H:i');
        $this->exit_date = $now->toDateString();
        $this->exit_time = $now->format('H:i');
    }

    public function updatedType($value)
    {
        $now = now();
        if ($value === 'entry') {
            $this->entry_date = $now->toDateString();
            $this->entry_time = $now->format('H:i');
        } else {
            $this->exit_date = $now->toDateString();
            $this->exit_time = $now->format('H:i');
        }
    }

    public function updatedVehicleId($value)
    {
        if ($value) {
            $v = Vehicle::find($value);
            if ($v) {
                $this->vehicle_plate = $v->plate;
                $this->vehicle_type_name = $v->vehicle_type ?? '';
            }
        } else {
            $this->vehicle_plate = '';
            $this->vehicle_type_name = '';
        }
    }

    public function resetForm()
    {
        $this->vehicle_id = '';
        $this->vehicle_plate = '';
        $this->vehicle_type_name = '';
        $this->driver_name = '';
        $this->route_folio = '';
        $this->initial_odometer = '';
        $this->fuel_level = '';
        $this->has_spare_tire = false;
        $this->vehicle_condition = '';
        $this->type = 'entry';
        $this->photo = null;
        $this->driver_photo = null;
        $this->confirmed = false;
        $this->notes = '';
        $this->signature = '';
        $this->checklistItems = [];

        $now = now();
        $this->entry_date = $now->toDateString();
        $this->entry_time = $now->format('H:i');
        $this->exit_date = $now->toDateString();
        $this->exit_time = $now->format('H:i');
    }

    public function save(GateService $gateService)
    {
        $now = now();
        if ($this->type === 'entry') {
            $this->entry_date = $now->toDateString();
            $this->entry_time = $now->format('H:i');
        } else {
            $this->exit_date = $now->toDateString();
            $this->exit_time = $now->format('H:i');
        }

        $rules = [
            'vehicle_id' => 'required|exists:vehicles,id',
            'type' => 'required',
            'vehicle_condition' => 'nullable|string',
            'notes' => 'nullable|string',
            'checklistItems' => 'required|array|min:1',
        ];

        if ($this->type === 'entry') {
            $rules = array_merge($rules, [
                'driver_name' => 'required|regex:/^[\pL\s0-9]+$/u',
                'entry_date' => 'required|date',
                'entry_time' => 'required',
                'initial_odometer' => 'required|numeric|min:0',
                'fuel_level' => 'required',
                'has_spare_tire' => 'required|boolean',
                'signature' => 'required|string',
                'confirmed' => 'required|accepted',
                'route_folio' => 'nullable|string',
            ]);
        } else {
            $rules = array_merge($rules, [
                'route_folio' => 'required|string',
                'exit_date' => 'required|date',
                'exit_time' => 'required',
                'initial_odometer' => 'required|numeric|min:0',
                'fuel_level' => 'required',
                'has_spare_tire' => 'required|boolean',
                'signature' => 'required|string',
                'confirmed' => 'required|accepted',
            ]);
        }

        if ($this->photo) {
            $rules['photo'] = 'image|max:5120';
        }

        if ($this->driver_photo) {
            $rules['driver_photo'] = 'image|max:5120';
        }

        $this->validate($rules);

        $data = [
            'vehicle_id' => $this->vehicle_id,
            'type' => $this->type,
            'notes' => $this->notes ?: null,
            'vehicle_condition' => $this->vehicle_condition ?: null,
            'checklist' => $this->checklistItems,
            'logged_at' => now(),
            'created_by' => auth()->id(),
        ];

        if ($this->photo) {
            $data['photo'] = $this->photo->store('gate/photos');
        }

        if ($this->driver_photo) {
            $data['driver_photo'] = $this->driver_photo->store('gate/driver_photos');
        }

        if ($this->type === 'entry') {
            $data['driver_name'] = $this->driver_name;
            $data['entry_date'] = $this->entry_date;
            $data['entry_time'] = $this->entry_time;
            $data['initial_odometer'] = $this->initial_odometer !== '' ? (float) $this->initial_odometer : null;
            $data['fuel_level'] = $this->fuel_level;
            $data['has_spare_tire'] = $this->has_spare_tire;
            $data['confirmed'] = $this->confirmed;
            $data['route_folio'] = $this->route_folio ?: null;
            $data['signature'] = $this->signature ?: null;
        } else {
            $data['route_folio'] = $this->route_folio;
            $data['exit_date'] = $this->exit_date;
            $data['exit_time'] = $this->exit_time;
            $data['initial_odometer'] = $this->initial_odometer !== '' ? (float) $this->initial_odometer : null;
            $data['fuel_level'] = $this->fuel_level;
            $data['has_spare_tire'] = $this->has_spare_tire;
            $data['confirmed'] = $this->confirmed;
            $data['signature'] = $this->signature ?: null;
        }

        $log = $gateService->create($data);

        $this->dispatch('gate-row-added', id: $log->id);
        $this->dispatch('swal:success', title: 'Pase registrado', message: 'Anotado en la bitácora del día.');
        $this->resetForm();
    }

    public function messages()
    {
        return [
            'vehicle_id.required' => 'Selecciona un vehículo.',
            'type.required' => 'Elige entrada o salida.',
            'driver_name.required' => 'Escribe el nombre del conductor.',
            'entry_date.required' => 'Falta la fecha de entrada.',
            'entry_time.required' => 'Falta la hora de entrada.',
            'exit_date.required' => 'Falta la fecha de salida.',
            'exit_time.required' => 'Falta la hora de salida.',
            'initial_odometer.required' => 'Anota el kilometraje.',
            'fuel_level.required' => 'Selecciona el nivel de gasolina.',
            'route_folio.required' => 'Escribe el folio del pase.',
            'has_spare_tire.required' => 'Indica si lleva llanta de refacción.',
            'signature.required' => 'La firma es obligatoria: firma en el recuadro.',
            'confirmed.required' => 'Confirma que revisaste el estado del vehículo.',
            'confirmed.accepted' => 'Marca la confirmación para registrar el pase.',
            'checklistItems.required' => 'Marca al menos un punto del check list.',
        ];
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

    public function clearSignature()
    {
        $this->signature = '';
    }

    public function render(VehicleService $vehicleService, GateService $gateService)
    {
        $now = now();
        $today = $now->toDateString();

        $todayEntries = GateLog::where('type', 'entry')->whereDate('logged_at', $today)->count();
        $todayExits = GateLog::where('type', 'exit')->whereDate('logged_at', $today)->count();

        $lastByVehicle = GateLog::orderBy('id')->get()->groupBy('vehicle_id')->map->last();
        $inYard = $lastByVehicle->filter(fn ($log) => $log->type->value === 'entry')->count();

        return view('livewire.gate-manager', [
            'vehicles' => $vehicleService->getAll(),
            'logs' => $gateService->getAllPaginated(20),
            'fuelLevels' => Catalog::byGroup('NivelCombustible')->orderBy('value', 'desc')->get(),
            'vehicleConditions' => Catalog::byGroup('CondicionVehiculo')->get(),
            'todayEntries' => $todayEntries,
            'todayExits' => $todayExits,
            'inYard' => $inYard,
            'todayFull' => $now->locale('es')->translatedFormat('l d \d\e F'),
            'todayMono' => strtoupper($now->locale('es')->translatedFormat('d M Y')),
        ]);
    }
}
