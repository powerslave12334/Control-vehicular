<?php

namespace App\Domains\Route\Actions;

use App\Domains\Route\Models\Route;
use App\Domains\Route\Events\RouteFinished;
use RuntimeException;

class FinishRouteAction
{
    public function execute(int $id, float $endOdometer): Route
    {
        $route = Route::findOrFail($id);

        if ($route->status->value === 'Completada') {
            throw new RuntimeException("La ruta ya está completada.");
        }

        if ($route->status->value === 'Cancelada') {
            throw new RuntimeException("No se puede completar una ruta cancelada.");
        }

        if ($endOdometer <= ($route->start_odometer ?: 0)) {
            throw new RuntimeException("El kilometraje final debe ser mayor al inicial.");
        }

        $route->status = 'Completada';
        $route->end_odometer = $endOdometer;
        $route->actual_km = $endOdometer - ($route->start_odometer ?: 0);
        $route->finished_at = now();
        $route->save();

        event(new RouteFinished($route));

        return $route;
    }
}
