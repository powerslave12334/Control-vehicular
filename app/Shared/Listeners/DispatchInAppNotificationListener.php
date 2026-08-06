<?php

namespace App\Shared\Listeners;

use App\Domains\Notification\Models\Notification;
use App\Domains\Notification\Services\NotificationService;
use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use ReflectionProperty;

class DispatchInAppNotificationListener
{
    public function handle(object $event): void
    {
        $subject = $this->extractSubject($event);

        if (!$subject instanceof Model || !$subject->exists) {
            return;
        }

        $eventName = class_basename($event);
        $modelName = class_basename($subject);
        $title = $this->buildTitle($modelName, $eventName);
        $message = $this->buildMessage($modelName, $eventName, $subject->id);
        $priority = $this->resolvePriority($eventName);

        $service = app(NotificationService::class);
        $userIds = User::pluck('id')->toArray();

        foreach ($userIds as $userId) {
            if (!$service->shouldNotify($userId, $eventName)) {
                continue;
            }

            Notification::create([
                'user_id' => $userId,
                'type' => $this->resolveNotificationType($eventName),
                'priority' => $priority,
                'title' => $title,
                'message' => $message,
                'read' => false,
                'related_id' => $subject->id,
                'related_type' => get_class($subject),
            ]);
        }
    }

    private function extractSubject(object $event): mixed
    {
        $reflection = new ReflectionClass($event);

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $value = $property->getValue($event);
            if ($value instanceof Model) {
                return $value;
            }
        }

        return null;
    }

    private function buildTitle(string $modelName, string $eventName): string
    {
        $titles = [
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

        $suffix = $titles[$this->eventSuffix($modelName, $eventName)] ?? $eventName;

        return "{$this->translateModel($modelName)}: {$suffix}";
    }

    private function buildMessage(string $modelName, string $eventName, int $subjectId): string
    {
        $verbs = [
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

        $model = $this->translateModel($modelName);
        $verb = $verbs[$this->eventSuffix($modelName, $eventName)] ?? $eventName;

        return "{$model} {$verb}: #{$subjectId}";
    }

    private function eventSuffix(string $modelName, string $eventName): string
    {
        return str_starts_with($eventName, $modelName)
            ? substr($eventName, strlen($modelName))
            : $eventName;
    }

    private function translateModel(string $modelName): string
    {
        $models = [
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

        return $models[$modelName] ?? $modelName;
    }

    private function resolveNotificationType(string $eventName): string
    {
        $critical = ['Deleted', 'Overdue', 'Expired', 'OutOfStock', 'AbnormalFuelConsumptionDetected', 'Escalated'];

        if (in_array($eventName, $critical)) {
            return 'critical';
        }

        $info = ['Created', 'Updated', 'Restored', 'Started', 'Finished', 'Uploaded', 'Registered'];

        if (in_array($eventName, $info)) {
            return 'info';
        }

        return 'warning';
    }

    private function resolvePriority(string $eventName): string
    {
        $critical = ['Deleted', 'Overdue', 'Expired', 'OutOfStock', 'AbnormalFuelConsumptionDetected', 'Escalated'];

        if (in_array($eventName, $critical)) {
            return 'critical';
        }

        $high = ['Cancelled', 'Rejected', 'LowStock'];

        if (in_array($eventName, $high)) {
            return 'high';
        }

        return 'normal';
    }
}
