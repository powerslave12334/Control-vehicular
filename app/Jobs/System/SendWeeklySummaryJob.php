<?php

namespace App\Jobs\System;

use App\Domains\Notification\Models\Notification;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Fuel\Models\Refuel;
use App\Domains\Maintenance\Models\Maintenance;
use App\Domains\Route\Models\Route;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class SendWeeklySummaryJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function handle(): void
    {
        $summary = [
            'active_vehicles' => Vehicle::where('status', 'Activa')->count(),
            'routes_completed' => Route::where('status', 'Completada')
                ->where('date', '>=', now()->subWeek())->count(),
            'total_fuel_cost' => Refuel::where('created_at', '>=', now()->subWeek())->sum('amount'),
            'total_maintenance_cost' => Maintenance::where('created_at', '>=', now()->subWeek())->sum('cost'),
            'pending_incidents' => \App\Domains\Incident\Models\Incident::whereIn('status', ['Reportada', 'En proceso'])->count(),
        ];

        foreach (\App\Domains\User\Models\User::whereIn('role', ['Administrador del sistema', 'Dirección'])->get() as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'info',
                'priority' => 'normal',
                'title' => 'Resumen semanal',
                'message' => sprintf(
                    'Vehículos activos: %d · Rutas completadas: %d · Costo combustible: $%s · Mantenimiento: $%s · Incidencias pendientes: %d',
                    $summary['active_vehicles'],
                    $summary['routes_completed'],
                    number_format($summary['total_fuel_cost'], 2),
                    number_format($summary['total_maintenance_cost'], 2),
                    $summary['pending_incidents'],
                ),
                'read' => false,
            ]);
        }
    }
}
