<?php

use App\Jobs\Document\CheckExpiredDocumentsJob;
use App\Jobs\System\CheckOverdueMaintenanceJob;
use App\Jobs\Insurance\CheckExpiringInsuranceJob;
use App\Jobs\System\CheckExpiringLicensesJob;
use App\Jobs\Part\CheckLowStockJob;
use App\Jobs\System\CleanAuditLogsJob;
use App\Jobs\System\SendWeeklySummaryJob;
use App\Jobs\System\DeleteOldNotificationsJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new CheckExpiredDocumentsJob)->dailyAt('06:00');
Schedule::job(new CheckOverdueMaintenanceJob)->dailyAt('06:30');
Schedule::job(new CheckExpiringInsuranceJob)->dailyAt('07:00');
Schedule::job(new CheckLowStockJob)->dailyAt('07:30');
Schedule::job(new CheckExpiringLicensesJob)->weeklyOn(1, '06:00');
Schedule::job(new SendWeeklySummaryJob)->weeklyOn(1, '08:00');
Schedule::job(new CleanAuditLogsJob)->monthlyOn(1, '03:00');
Schedule::job(new DeleteOldNotificationsJob)->monthlyOn(1, '04:00');
