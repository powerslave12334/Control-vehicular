<?php

namespace App\Jobs\Vehicle;

use App\Domains\Vehicle\Models\Vehicle;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class NotifyVehicleStatusChangeJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(public readonly Vehicle $vehicle, public readonly string $oldStatus, public readonly string $newStatus) {}

    public function handle(): void
    {
        foreach (\App\Domains\User\Models\User::whereIn('role', ['Administrador del sistema', 'Dirección', 'Logística'])->get() as $user) {
            \App\Domains\Notification\Models\Notification::create([
                'user_id' => $user->id,
                'type' => 'warning',
                'priority' => 'high',
                'title' => 'Cambio de estado de vehículo',
                'message' => "Vehículo {$this->vehicle->plate} cambió de {$this->oldStatus} a {$this->newStatus}",
                'read' => false,
                'related_id' => $this->vehicle->id,
                'related_type' => Vehicle::class,
            ]);
        }
    }
}
