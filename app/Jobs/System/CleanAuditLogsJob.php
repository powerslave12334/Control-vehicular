<?php

namespace App\Jobs\System;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class CleanAuditLogsJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function handle(): void
    {
        DB::table('activity_logs')
            ->where('created_at', '<', now()->subMonths(3))
            ->delete();
    }
}
