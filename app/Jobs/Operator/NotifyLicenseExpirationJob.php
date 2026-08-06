<?php

namespace App\Jobs\Operator;

use App\Domains\Operator\Models\Operator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class NotifyLicenseExpirationJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(public readonly Operator $operator) {}

    public function handle(): void
    {
        \App\Domains\Notification\Models\Notification::create([
            'user_id' => 1,
            'type' => 'critical',
            'priority' => 'critical',
            'title' => 'Licencia por vencer',
            'message' => "La licencia del operador {$this->operator->name} está próxima a vencer.",
            'read' => false,
            'related_id' => $this->operator->id,
            'related_type' => Operator::class,
        ]);
    }
}
