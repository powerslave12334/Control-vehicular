<?php

namespace App\Jobs\Route;

use App\Domains\Route\Models\Route;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class NotifyRouteAssignmentJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 3;

    public function __construct(public readonly Route $route) {}

    public function handle(): void
    {
        if (!$this->route->driver_id) return;

        $operator = \App\Domains\Operator\Models\Operator::find($this->route->driver_id);
        if (!$operator) return;

        \App\Domains\Notification\Models\Notification::create([
            'user_id' => $this->route->created_by ?? 1,
            'type' => 'info',
            'priority' => 'normal',
            'title' => 'Ruta asignada',
            'message' => "Ruta a {$this->route->client_name} asignada a {$this->route->driver_name}",
            'read' => false,
            'related_id' => $this->route->id,
            'related_type' => Route::class,
        ]);
    }
}
