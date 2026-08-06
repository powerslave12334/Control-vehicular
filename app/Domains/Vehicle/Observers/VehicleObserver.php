<?php

namespace App\Domains\Vehicle\Observers;

use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Vehicle\Models\VehicleLog;
use App\Domains\Vehicle\Events\VehicleCreated;
use App\Domains\Vehicle\Events\VehicleUpdated;
use App\Domains\Vehicle\Events\VehicleDeleted;
use App\Domains\Vehicle\Events\VehicleRestored;
use App\Domains\Vehicle\Events\VehicleStatusChanged;
use App\Shared\Services\AuditService;
use Illuminate\Support\Facades\Auth;

class VehicleObserver
{
    public function __construct(protected AuditService $audit) {}

    public function created(Vehicle $vehicle): void
    {
        event(new VehicleCreated($vehicle));
        $this->audit->log('created', $vehicle, "Vehículo {$vehicle->plate} creado");
        $this->writeLog($vehicle, 'vehicle.created', "Vehículo {$vehicle->plate} registrado");
    }

    public function updated(Vehicle $vehicle): void
    {
        event(new VehicleUpdated($vehicle));
        $changes = $vehicle->getChanges();

        if (isset($changes['status'])) {
            $original = $vehicle->getOriginal('status');
            $originalStr = $original instanceof \BackedEnum ? $original->value : (string) $original;
            $newStr = $changes['status'] instanceof \BackedEnum ? $changes['status']->value : (string) $changes['status'];
            event(new VehicleStatusChanged($vehicle, $originalStr, $newStr));
            $this->writeLog($vehicle, 'vehicle.status_changed', "Estado cambiado de {$originalStr} a {$newStr}");
        }

        if (isset($changes['assigned_driver_id'])) {
            $action = $changes['assigned_driver_id'] ? 'vehicle.assigned' : 'vehicle.released';
            $desc = $changes['assigned_driver_id']
                ? "Conductor asignado al vehículo {$vehicle->plate}"
                : "Conductor liberado del vehículo {$vehicle->plate}";
            $this->writeLog($vehicle, $action, $desc);
        }

        $this->audit->log('updated', $vehicle, "Vehículo {$vehicle->plate} actualizado", $changes);
    }

    public function deleted(Vehicle $vehicle): void
    {
        event(new VehicleDeleted($vehicle));
        $this->audit->log('deleted', $vehicle, "Vehículo {$vehicle->plate} eliminado");
        $this->writeLog($vehicle, 'vehicle.deleted', "Vehículo {$vehicle->plate} eliminado");
    }

    public function restored(Vehicle $vehicle): void
    {
        event(new VehicleRestored($vehicle));
        $this->audit->log('restored', $vehicle, "Vehículo {$vehicle->plate} restaurado");
        $this->writeLog($vehicle, 'vehicle.restored', "Vehículo {$vehicle->plate} restaurado");
    }

    protected function writeLog(Vehicle $vehicle, string $eventType, string $description): void
    {
        VehicleLog::create([
            'vehicle_id' => $vehicle->id,
            'event_type' => $eventType,
            'description' => $description,
            'user_id' => Auth::id(),
            'metadata' => $vehicle->getChanges() ?: null,
        ]);
    }
}
