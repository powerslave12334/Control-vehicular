<?php

namespace App\Jobs\WorkOrder;

use App\Domains\WorkOrder\Models\WorkOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class NotifyWorkOrderCompletedJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 3;

    public function __construct(public readonly WorkOrder $workOrder) {}

    public function handle(): void
    {
        \App\Domains\Notification\Models\Notification::create([
            'user_id' => $this->workOrder->created_by ?? 1,
            'type' => 'info',
            'priority' => 'normal',
            'title' => 'Orden completada',
            'message' => "Orden #{$this->workOrder->id} ha sido completada",
            'read' => false,
            'related_id' => $this->workOrder->id,
            'related_type' => WorkOrder::class,
        ]);
    }
}
