<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Domains\Vehicle\Models\Vehicle::class => \App\Domains\Vehicle\Policies\VehiclePolicy::class,
        \App\Domains\Operator\Models\Operator::class => \App\Domains\Operator\Policies\OperatorPolicy::class,
        \App\Domains\Route\Models\Route::class => \App\Domains\Route\Policies\RoutePolicy::class,
        \App\Domains\Fuel\Models\Refuel::class => \App\Domains\Fuel\Policies\FuelPolicy::class,
        \App\Domains\Maintenance\Models\Maintenance::class => \App\Domains\Maintenance\Policies\MaintenancePolicy::class,
        \App\Domains\Incident\Models\Incident::class => \App\Domains\Incident\Policies\IncidentPolicy::class,
        \App\Domains\Document\Models\Document::class => \App\Domains\Document\Policies\DocumentPolicy::class,
        \App\Domains\Expense\Models\Expense::class => \App\Domains\Expense\Policies\ExpensePolicy::class,
        \App\Domains\Inspection\Models\Inspection::class => \App\Domains\Inspection\Policies\InspectionPolicy::class,
        \App\Domains\Insurance\Models\Insurance::class => \App\Domains\Insurance\Policies\InsurancePolicy::class,
        \App\Domains\Part\Models\Part::class => \App\Domains\Part\Policies\PartPolicy::class,
        \App\Domains\Provider\Models\Provider::class => \App\Domains\Provider\Policies\ProviderPolicy::class,
        \App\Domains\WorkOrder\Models\WorkOrder::class => \App\Domains\WorkOrder\Policies\WorkOrderPolicy::class,
        \App\Domains\User\Models\User::class => \App\Domains\User\Policies\UserPolicy::class,
        \App\Domains\Catalog\Models\Catalog::class => \App\Domains\Catalog\Policies\CatalogPolicy::class,
        \App\Domains\Notification\Models\Notification::class => \App\Domains\Notification\Policies\NotificationPolicy::class,
        \App\Domains\Gate\Models\GateLog::class => \App\Domains\Gate\Policies\GateLogPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
