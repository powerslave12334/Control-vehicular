<?php

namespace App\Domains\Route\Observers;

use App\Domains\Route\Models\Route;
use App\Domains\Route\Events\RouteCreated;
use App\Domains\Route\Events\RouteUpdated;
use App\Domains\Route\Events\RouteDeleted;
use App\Domains\Route\Events\RouteRestored;
use App\Shared\Services\AuditService;

class RouteObserver
{
    public function __construct(protected AuditService $audit) {}

    public function created(Route $route): void
    {
        event(new RouteCreated($route));
        $this->audit->log('created', $route, "Ruta #{$route->id} creada");
    }

    public function updated(Route $route): void
    {
        event(new RouteUpdated($route));
        $changes = $route->getChanges();

        if (isset($changes['status'])) {
            $original = $route->getRawOriginal('status');
            $this->audit->log('status_changed', $route, "Ruta #{$route->id}: {$original} → {$changes['status']}", $changes);
        } else {
            $this->audit->log('updated', $route, "Ruta #{$route->id} actualizada", $changes);
        }
    }

    public function deleted(Route $route): void
    {
        event(new RouteDeleted($route));
        $this->audit->log('deleted', $route, "Ruta #{$route->id} eliminada");
    }

    public function restored(Route $route): void
    {
        event(new RouteRestored($route));
        $this->audit->log('restored', $route, "Ruta #{$route->id} restaurada");
    }
}
