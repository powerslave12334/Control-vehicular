<?php

namespace App\Jobs\WorkOrder;

use App\Domains\WorkOrder\Models\WorkOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class GenerateWorkOrderReportJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function __construct(public readonly WorkOrder $workOrder) {}

    public function handle(): void
    {
        \Illuminate\Support\Facades\Log::info('Work order report generated', [
            'work_order_id' => $this->workOrder->id,
            'status' => $this->workOrder->status,
            'total_cost' => $this->workOrder->total_cost,
        ]);
    }
}
