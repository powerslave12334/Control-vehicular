<?php

namespace App\Jobs\Insurance;

use App\Domains\Insurance\Models\Insurance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class CheckExpiringInsuranceJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function handle(): void
    {
        $expired = Insurance::where('end_date', '<=', now())->get();

        foreach ($expired as $insurance) {
            \App\Domains\Notification\Models\Notification::create([
                'user_id' => 1,
                'type' => 'critical',
                'priority' => 'critical',
                'title' => 'Seguro vencido',
                'message' => "Póliza #{$insurance->id} ha expirado el {$insurance->end_date->format('d/m/Y')}",
                'read' => false,
                'related_id' => $insurance->id,
                'related_type' => Insurance::class,
            ]);
        }

        $expiringSoon = Insurance::where('end_date', '>', now())
            ->where('end_date', '<=', now()->addDays(30))
            ->get();

        foreach ($expiringSoon as $insurance) {
            \App\Domains\Notification\Models\Notification::create([
                'user_id' => 1,
                'type' => 'warning',
                'priority' => 'high',
                'title' => 'Seguro por vencer',
                'message' => "Póliza #{$insurance->id} vence el {$insurance->end_date->format('d/m/Y')}",
                'read' => false,
                'related_id' => $insurance->id,
                'related_type' => Insurance::class,
            ]);
        }
    }
}
