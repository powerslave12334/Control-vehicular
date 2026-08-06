<?php

namespace App\Domains\Fuel\Actions;

use App\Domains\Fuel\Models\Refuel;
use App\Domains\Fuel\Events\AbnormalFuelConsumptionDetected;

class CalculateFuelEfficiencyAction
{
    public function execute(int $refuelId): array
    {
        $refuel = Refuel::findOrFail($refuelId);

        $previous = Refuel::where('vehicle_id', $refuel->vehicle_id)
            ->where('id', '<', $refuel->id)
            ->where('status', 'aprobado')
            ->orderBy('id', 'desc')
            ->first();

        if (!$previous || !$previous->odometer || !$refuel->odometer) {
            return ['km_per_liter' => null, 'liters_per_100km' => null];
        }

        $kmDiff = $refuel->odometer - $previous->odometer;
        if ($kmDiff <= 0 || $refuel->liters <= 0) {
            return ['km_per_liter' => null, 'liters_per_100km' => null];
        }

        $kmPerLiter = round($kmDiff / $refuel->liters, 2);
        $litersPer100km = round(($refuel->liters / $kmDiff) * 100, 2);

        $lastFive = Refuel::where('vehicle_id', $refuel->vehicle_id)
            ->where('status', 'aprobado')
            ->where('id', '<=', $refuel->id)
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        if ($lastFive->count() >= 3) {
            $averages = [];
            for ($i = 1; $i < $lastFive->count(); $i++) {
                $prev = $lastFive[$i];
                $curr = $lastFive[$i - 1];
                if ($prev->odometer && $curr->odometer && $curr->liters > 0) {
                    $diff = $curr->odometer - $prev->odometer;
                    if ($diff > 0) {
                        $averages[] = $diff / $curr->liters;
                    }
                }
            }
            if (count($averages) >= 2) {
                $avgConsumption = array_sum($averages) / count($averages);
                $threshold = $avgConsumption * 0.7;

                if ($kmPerLiter < $threshold) {
                    event(new AbnormalFuelConsumptionDetected($refuel, $avgConsumption, $kmPerLiter));
                }
            }
        }

        return [
            'km_per_liter' => $kmPerLiter,
            'liters_per_100km' => $litersPer100km,
        ];
    }
}
