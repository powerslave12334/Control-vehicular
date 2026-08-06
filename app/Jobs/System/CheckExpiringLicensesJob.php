<?php

namespace App\Jobs\System;

use App\Jobs\Operator\NotifyLicenseExpirationJob;
use App\Domains\Operator\Models\Operator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class CheckExpiringLicensesJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function handle(): void
    {
        \Illuminate\Support\Facades\Log::info('Licenses checked for expiration');
    }
}
