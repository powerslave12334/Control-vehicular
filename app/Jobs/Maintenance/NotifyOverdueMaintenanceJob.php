<?php

namespace App\Jobs\Maintenance;

use App\Domains\Maintenance\Models\Maintenance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class NotifyOverdueMaintenanceJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 3;

    public function __construct(public readonly Maintenance $maintenance) {}

    public function handle(): void
    {
        foreach (\App\Domains\User\Models\User::whereIn('role', ['Administrador del sistema', 'Dirección', 'Logística'])->get() as $user) {
            \App\Domains\Notification\Models\Notification::create([
                'user_id' => $user->id,
                'type' => 'critical',
                'priority' => 'critical',
                'title' => 'Mantenimiento vencido',
                'message' => "Mantenimiento #{$this->maintenance->id} está vencido — {$this->maintenance->description}",
                'read' => false,
                'related_id' => $this->maintenance->id,
                'related_type' => Maintenance::class,
            ]);
        }
    }
}
