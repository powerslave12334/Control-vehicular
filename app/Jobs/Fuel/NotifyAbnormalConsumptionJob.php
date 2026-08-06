<?php

namespace App\Jobs\Fuel;

use App\Domains\Fuel\Models\Refuel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class NotifyAbnormalConsumptionJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 3;

    public function __construct(public readonly Refuel $refuel, public readonly float $actualKmPerLiter) {}

    public function handle(): void
    {
        foreach (\App\Domains\User\Models\User::whereIn('role', ['Administrador del sistema', 'Logística'])->get() as $user) {
            \App\Domains\Notification\Models\Notification::create([
                'user_id' => $user->id,
                'type' => 'critical',
                'priority' => 'critical',
                'title' => 'Consumo anormal detectado',
                'message' => "Vehículo #{$this->refuel->vehicle_id} — rendimiento de {$this->actualKmPerLiter} km/l",
                'read' => false,
                'related_id' => $this->refuel->id,
                'related_type' => Refuel::class,
            ]);
        }
    }
}
