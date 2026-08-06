<?php

namespace App\Jobs\WorkOrder;

use App\Domains\WorkOrder\Models\WorkOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class NotifyWorkOrderPendingApprovalJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 3;

    public function __construct(public readonly WorkOrder $workOrder) {}

    public function handle(): void
    {
        foreach (\App\Domains\User\Models\User::whereIn('role', ['Administrador del sistema', 'Dirección'])->get() as $user) {
            \App\Domains\Notification\Models\Notification::create([
                'user_id' => $user->id,
                'type' => 'warning',
                'priority' => 'high',
                'title' => 'Orden pendiente de aprobación',
                'message' => "Orden #{$this->workOrder->id} requiere aprobación",
                'read' => false,
                'related_id' => $this->workOrder->id,
                'related_type' => WorkOrder::class,
            ]);
        }
    }
}
