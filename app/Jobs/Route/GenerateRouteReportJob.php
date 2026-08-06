<?php

namespace App\Jobs\Route;

use App\Domains\Route\Models\Route;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class GenerateRouteReportJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function __construct(public readonly Route $route) {}

    public function handle(): void
    {
        \Illuminate\Support\Facades\Log::info('Route report generated', [
            'route_id' => $this->route->id,
            'client' => $this->route->client_name,
            'planned_km' => $this->route->planned_km,
            'actual_km' => $this->route->actual_km,
        ]);
    }
}
