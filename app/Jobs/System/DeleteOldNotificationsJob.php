<?php

namespace App\Jobs\System;

use App\Domains\Notification\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class DeleteOldNotificationsJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function handle(): void
    {
        Notification::where('created_at', '<', now()->subMonths(6))
            ->where('archived', true)
            ->delete();

        Notification::where('created_at', '<', now()->subMonths(12))
            ->delete();
    }
}
