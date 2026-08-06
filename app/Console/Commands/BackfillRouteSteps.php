<?php

namespace App\Console\Commands;

use App\Domains\Route\Models\Route;
use App\Domains\Route\Models\RouteStep;
use Illuminate\Console\Command;

class BackfillRouteSteps extends Command
{
    protected $signature = 'routes:backfill-steps';
    protected $description = 'Create missing route steps for existing routes';

    public function handle()
    {
        $stepTypes = ['departurePlant', 'arrivalClient', 'startInstallation', 'endInstallation', 'returnToPlant', 'arrivalPlant'];

        Route::all()->each(function (Route $route) use ($stepTypes) {
            $existing = $route->steps()->pluck('step_type')->all();
            $missing = array_diff($stepTypes, $existing);

            if (empty($missing)) {
                $this->info("Route #{$route->id} — all steps present");
                return;
            }

            foreach ($missing as $type) {
                RouteStep::create([
                    'route_id' => $route->id,
                    'step_type' => $type,
                    'timestamp' => now(),
                ]);
                $this->line("  Created step: {$type}");
            }

            $this->info("Route #{$route->id} ({$route->client_name}) — filled " . count($missing) . ' missing steps');
        });

        $this->newLine();
        $this->info('Done.');
    }
}
