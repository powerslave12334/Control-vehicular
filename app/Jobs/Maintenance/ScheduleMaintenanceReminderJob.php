<?php

namespace App\Jobs\Maintenance;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class ScheduleMaintenanceReminderJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function handle(): void
    {
        $upcoming = \App\Domains\Maintenance\Models\Maintenance::whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '<=', now()->addDays(7))
            ->where('next_maintenance_date', '>', now())
            ->get();

        foreach ($upcoming as $maintenance) {
            \App\Domains\Notification\Models\Notification::create([
                'user_id' => 1,
                'type' => 'warning',
                'priority' => 'high',
                'title' => 'Mantenimiento próximo',
                'message' => "Mantenimiento #{$maintenance->id} programado para {$maintenance->next_maintenance_date}",
                'read' => false,
                'related_id' => $maintenance->id,
                'related_type' => \App\Domains\Maintenance\Models\Maintenance::class,
            ]);
        }
    }
}
