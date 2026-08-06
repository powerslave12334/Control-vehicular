<?php

namespace App\Providers;

use App\Shared\Listeners\DispatchInAppNotificationListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // ─── Vehicle ───────────────────────────────────────────────
        // ─── Vehicle ───────────────────────────────────────────────
        \App\Domains\Vehicle\Events\VehicleCreated::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Vehicle\Events\VehicleDeleted::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Vehicle\Events\VehicleAssigned::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Vehicle\Events\VehicleReleased::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Vehicle\Events\VehicleStatusChanged::class => [
            DispatchInAppNotificationListener::class,
            \App\Shared\Listeners\DispatchVehicleStatusChangeJobListener::class,
        ],

        // ─── Operator ──────────────────────────────────────────────
        \App\Domains\Operator\Events\OperatorCreated::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Operator\Events\OperatorDeleted::class => [
            DispatchInAppNotificationListener::class,
        ],

        // ─── Route ─────────────────────────────────────────────────
        \App\Domains\Route\Events\RouteCreated::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Route\Events\RouteDeleted::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Route\Events\RouteStarted::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Route\Events\RouteFinished::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Route\Events\RouteCancelled::class => [
            DispatchInAppNotificationListener::class,
        ],

        // ─── Fuel ──────────────────────────────────────────────────
        \App\Domains\Fuel\Events\RefuelCreated::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Fuel\Events\FuelApproved::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Fuel\Events\FuelRejected::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Fuel\Events\AbnormalFuelConsumptionDetected::class => [
            DispatchInAppNotificationListener::class,
            \App\Shared\Listeners\DispatchAbnormalFuelConsumptionJobListener::class,
        ],

        // ─── Maintenance ───────────────────────────────────────────
        \App\Domains\Maintenance\Events\MaintenanceCreated::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Maintenance\Events\MaintenanceApproved::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Maintenance\Events\MaintenanceCancelled::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Maintenance\Events\MaintenanceCompleted::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Maintenance\Events\MaintenanceStarted::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Maintenance\Events\MaintenanceRejected::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Maintenance\Events\MaintenanceOverdue::class => [
            DispatchInAppNotificationListener::class,
            \App\Shared\Listeners\DispatchMaintenanceOverdueJobListener::class,
        ],

        // ─── Incident ──────────────────────────────────────────────
        \App\Domains\Incident\Events\IncidentCreated::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Incident\Events\IncidentClosed::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Incident\Events\IncidentEscalated::class => [
            DispatchInAppNotificationListener::class,
            \App\Shared\Listeners\DispatchIncidentEscalationJobListener::class,
        ],

        // ─── WorkOrder ─────────────────────────────────────────────
        \App\Domains\WorkOrder\Events\WorkOrderCreated::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\WorkOrder\Events\WorkOrderApproved::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\WorkOrder\Events\WorkOrderCancelled::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\WorkOrder\Events\WorkOrderClosed::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\WorkOrder\Events\WorkOrderCompleted::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\WorkOrder\Events\WorkOrderStarted::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\WorkOrder\Events\WorkOrderRejected::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\WorkOrder\Events\WorkOrderAssigned::class => [
            DispatchInAppNotificationListener::class,
        ],

        // ─── Part ──────────────────────────────────────────────────
        \App\Domains\Part\Events\PartCreated::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Part\Events\PartLowStock::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Part\Events\PartOutOfStock::class => [
            DispatchInAppNotificationListener::class,
        ],

        // ─── Provider ──────────────────────────────────────────────
        \App\Domains\Provider\Events\ProviderCreated::class => [
            DispatchInAppNotificationListener::class,
        ],

        // ─── Document ──────────────────────────────────────────────
        \App\Domains\Document\Events\DocumentUploaded::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Document\Events\DocumentExpired::class => [
            DispatchInAppNotificationListener::class,
        ],

        // ─── Expense ───────────────────────────────────────────────
        \App\Domains\Expense\Events\ExpenseRegistered::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Expense\Events\ExpenseApproved::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Expense\Events\ExpenseRejected::class => [
            DispatchInAppNotificationListener::class,
        ],

        // ─── Insurance ─────────────────────────────────────────────
        \App\Domains\Insurance\Events\InsuranceCreated::class => [
            DispatchInAppNotificationListener::class,
        ],
        \App\Domains\Insurance\Events\InsuranceExpired::class => [
            DispatchInAppNotificationListener::class,
        ],

        // ─── Inspection ────────────────────────────────────────────
        \App\Domains\Inspection\Events\InspectionCreated::class => [
            DispatchInAppNotificationListener::class,
        ],

        // ─── Gate ──────────────────────────────────────────────────
        \App\Domains\Gate\Events\GateLogCreated::class => [
            DispatchInAppNotificationListener::class,
        ],

        // ─── User ──────────────────────────────────────────────────
        \App\Domains\User\Events\UserCreated::class => [
            DispatchInAppNotificationListener::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
