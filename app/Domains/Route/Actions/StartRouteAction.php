<?php

namespace App\Domains\Route\Actions;

use App\Domains\Route\Models\Route;
use App\Domains\Route\Events\RouteStarted;
use RuntimeException;

class StartRouteAction
{
    public function execute(int $id, float $startOdometer): Route
    {
        $route = Route::findOrFail($id);

        if (!in_array($route->status->value, ['Programada', 'Asignada'])) {
            throw new RuntimeException("Solo se pueden iniciar rutas en estado Programada o Asignada.");
        }

        $activeRoutes = Route::where('vehicle_id', $route->vehicle_id)
            ->where('id', '!=', $route->id)
            ->whereIn('status', ['En tránsito', 'Instalando'])
            ->exists();

        if ($activeRoutes) {
            throw new RuntimeException("El vehículo ya tiene una ruta activa.");
        }

        $activeDriverRoutes = Route::where('driver_id', $route->driver_id)
            ->where('id', '!=', $route->id)
            ->whereIn('status', ['En tránsito', 'Instalando'])
            ->exists();

        if ($activeDriverRoutes) {
            throw new RuntimeException("El operador ya tiene una ruta activa.");
        }

        $route->status = 'En tránsito';
        $route->start_odometer = $startOdometer;
        $route->started_at = now();
        $route->save();

        event(new RouteStarted($route));

        return $route;
    }
}
