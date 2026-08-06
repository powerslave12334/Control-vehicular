<?php

namespace App\Domains\Provider\Observers;

use App\Domains\Provider\Models\Provider;
use App\Domains\Provider\Events\ProviderCreated;
use App\Domains\Provider\Events\ProviderUpdated;
use App\Domains\Provider\Events\ProviderDeleted;
use App\Domains\Provider\Events\ProviderRestored;
use App\Shared\Services\AuditService;

class ProviderObserver
{
    public function __construct(protected AuditService $audit) {}

    public function created(Provider $provider): void
    {
        event(new ProviderCreated($provider));
        $this->audit->log('created', $provider, "Proveedor '{$provider->name}' creado");
    }

    public function updated(Provider $provider): void
    {
        event(new ProviderUpdated($provider));
        $this->audit->log('updated', $provider, "Proveedor '{$provider->name}' actualizado", $provider->getChanges());
    }

    public function deleted(Provider $provider): void
    {
        event(new ProviderDeleted($provider));
        $this->audit->log('deleted', $provider, "Proveedor '{$provider->name}' eliminado");
    }

    public function restored(Provider $provider): void
    {
        event(new ProviderRestored($provider));
        $this->audit->log('restored', $provider, "Proveedor '{$provider->name}' restaurado");
    }
}
