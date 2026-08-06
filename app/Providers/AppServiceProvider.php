<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Shared\Services\AuditService::class);
    }

    public function boot(): void
    {
        Paginator::defaultView('pagination');

        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()));
    
        \App\Domains\Vehicle\Models\Vehicle::observe(\App\Domains\Vehicle\Observers\VehicleObserver::class);
        \App\Domains\Operator\Models\Operator::observe(\App\Domains\Operator\Observers\OperatorObserver::class);
        \App\Domains\Route\Models\Route::observe(\App\Domains\Route\Observers\RouteObserver::class);
        \App\Domains\Fuel\Models\Refuel::observe(\App\Domains\Fuel\Observers\RefuelObserver::class);
        \App\Domains\Maintenance\Models\Maintenance::observe(\App\Domains\Maintenance\Observers\MaintenanceObserver::class);
        \App\Domains\Incident\Models\Incident::observe(\App\Domains\Incident\Observers\IncidentObserver::class);
        \App\Domains\Inspection\Models\Inspection::observe(\App\Domains\Inspection\Observers\InspectionObserver::class);
        \App\Domains\Provider\Models\Provider::observe(\App\Domains\Provider\Observers\ProviderObserver::class);
        \App\Domains\Part\Models\Part::observe(\App\Domains\Part\Observers\PartObserver::class);
        \App\Domains\WorkOrder\Models\WorkOrder::observe(\App\Domains\WorkOrder\Observers\WorkOrderObserver::class);
        \App\Domains\Expense\Models\Expense::observe(\App\Domains\Expense\Observers\ExpenseObserver::class);
        \App\Domains\Document\Models\Document::observe(\App\Domains\Document\Observers\DocumentObserver::class);
        \App\Domains\Insurance\Models\Insurance::observe(\App\Domains\Insurance\Observers\InsuranceObserver::class);
        \App\Domains\User\Models\User::observe(\App\Domains\User\Observers\UserObserver::class);
        \App\Domains\Gate\Models\GateLog::observe(\App\Domains\Gate\Observers\GateLogObserver::class);
    }
}
