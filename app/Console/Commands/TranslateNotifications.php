<?php

namespace App\Console\Commands;

use App\Domains\Notification\Models\Notification;
use Illuminate\Console\Command;

class TranslateNotifications extends Command
{
    protected $signature = 'notifications:translate';

    protected $description = 'Traduce a español los títulos y mensajes de notificaciones existentes generados con texto en inglés';

    private array $models = [
        'Route' => 'Ruta',
        'Vehicle' => 'Vehículo',
        'Maintenance' => 'Mantenimiento',
        'Incident' => 'Incidencia',
        'WorkOrder' => 'Orden de trabajo',
        'Refuel' => 'Carga de combustible',
        'Expense' => 'Gasto',
        'Insurance' => 'Seguro',
        'Part' => 'Refacción',
        'Document' => 'Documento',
        'Operator' => 'Operador',
        'Provider' => 'Proveedor',
        'Inspection' => 'Inspección',
        'License' => 'Licencia',
        'GateLog' => 'Registro de portería',
        'User' => 'Usuario',
    ];

    private array $events = [
        'Created' => 'creado',
        'Updated' => 'actualizado',
        'Deleted' => 'eliminado',
        'Restored' => 'restaurado',
        'Approved' => 'aprobado',
        'Rejected' => 'rechazado',
        'Completed' => 'completado',
        'Started' => 'iniciado',
        'Finished' => 'finalizado',
        'Cancelled' => 'cancelado',
        'Closed' => 'cerrado',
        'Assigned' => 'asignado',
        'Released' => 'liberado',
        'StatusChanged' => 'con cambio de estado',
        'Escalated' => 'escalado',
        'Overdue' => 'vencido',
        'Expired' => 'expirado',
        'LowStock' => 'con stock bajo',
        'OutOfStock' => 'sin stock',
        'AbnormalFuelConsumptionDetected' => 'con consumo anormal',
        'Uploaded' => 'subido',
        'Registered' => 'registrado',
        'PartsUpdated' => 'actualizado en refacciones',
    ];

    private array $titleEvents = [
        'Created' => 'Nuevo registro',
        'Updated' => 'Registro actualizado',
        'Deleted' => 'Registro eliminado',
        'Restored' => 'Registro restaurado',
        'Approved' => 'Aprobado',
        'Rejected' => 'Rechazado',
        'Completed' => 'Completado',
        'Started' => 'Iniciado',
        'Finished' => 'Finalizado',
        'Cancelled' => 'Cancelado',
        'Closed' => 'Cerrado',
        'Assigned' => 'Asignado',
        'Released' => 'Liberado',
        'StatusChanged' => 'Cambio de estado',
        'Escalated' => 'Escalado',
        'Overdue' => 'Vencido',
        'Expired' => 'Expirado',
        'LowStock' => 'Stock bajo',
        'OutOfStock' => 'Sin stock',
        'AbnormalFuelConsumptionDetected' => 'Consumo anormal detectado',
        'Uploaded' => 'Documento subido',
        'Registered' => 'Registrado',
        'PartsUpdated' => 'Refacciones actualizadas',
    ];

    public function handle(): int
    {
        $notifications = Notification::all();
        $updated = 0;

        foreach ($notifications as $notification) {
            $title = $this->translateTitle($notification->title);
            $message = $this->translateMessage($notification->message);

            if ($title !== $notification->title || $message !== $notification->message) {
                $notification->update(['title' => $title, 'message' => $message]);
                $updated++;
            }
        }

        $this->info("Notificaciones traducidas: {$updated} de {$notifications->count()}.");

        return self::SUCCESS;
    }

    private function translateTitle(string $title): string
    {
        return preg_replace_callback('/^([A-Za-z]+):\s+(.+)$/', function ($m) {
            $model = $this->models[$m[1]] ?? $m[1];
            $suffix = $this->titleSuffix($m[1], $m[2]);

            return "{$model}: {$suffix}";
        }, $title) ?? $title;
    }

    private function translateMessage(string $message): string
    {
        $decoded = json_decode($message, true);

        if (is_array($decoded) && isset($decoded['active_vehicles'], $decoded['routes_completed'], $decoded['total_fuel_cost'], $decoded['total_maintenance_cost'], $decoded['pending_incidents'])) {
            return sprintf(
                'Vehículos activos: %d · Rutas completadas: %d · Costo combustible: $%s · Mantenimiento: $%s · Incidencias pendientes: %d',
                $decoded['active_vehicles'],
                $decoded['routes_completed'],
                number_format((float) $decoded['total_fuel_cost'], 2),
                number_format((float) $decoded['total_maintenance_cost'], 2),
                $decoded['pending_incidents'],
            );
        }

        return preg_replace_callback('/^([A-Za-z]+)\s+([A-Za-z]+):\s+#(\d+)$/', function ($m) {
            $model = $this->models[$m[1]] ?? $m[1];
            $verb = $this->resolveVerb($m[1], $m[2]);

            return "{$model} {$verb}: #{$m[3]}";
        }, $message) ?? $message;
    }

    private function resolveVerb(string $model, string $eventName): string
    {
        $suffix = $this->eventSuffix($model, $eventName);

        return $this->events[$suffix] ?? $eventName;
    }

    private function titleSuffix(string $model, string $eventName): string
    {
        $suffix = $this->eventSuffix($model, $eventName);

        return $this->titleEvents[$suffix] ?? $eventName;
    }

    private function eventSuffix(string $model, string $eventName): string
    {
        return str_starts_with($eventName, $model)
            ? substr($eventName, strlen($model))
            : $eventName;
    }
}