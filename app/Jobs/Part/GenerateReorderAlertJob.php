<?php

namespace App\Jobs\Part;

use App\Domains\Part\Models\Part;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class GenerateReorderAlertJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function __construct(public readonly Part $part) {}

    public function handle(): void
    {
        \App\Domains\Notification\Models\Notification::create([
            'user_id' => 1,
            'type' => 'warning',
            'priority' => 'high',
            'title' => 'Alerta de reorden',
            'message' => "Refacción {$this->part->name} requiere reorden. Stock actual: {$this->part->stock}",
            'read' => false,
            'related_id' => $this->part->id,
            'related_type' => Part::class,
        ]);
    }
}
