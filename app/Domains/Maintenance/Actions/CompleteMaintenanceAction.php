<?php

namespace App\Domains\Maintenance\Actions;

use App\Domains\Maintenance\Models\Maintenance;
use App\Domains\Maintenance\Events\MaintenanceCompleted;

class CompleteMaintenanceAction
{
    public function execute(int $id): Maintenance
    {
        $maintenance = Maintenance::findOrFail($id);

        if ($maintenance->status !== 'en_progreso') {
            throw new \DomainException('Solo mantenimientos en progreso pueden completarse.');
        }

        $maintenance->update([
            'status' => 'completado',
            'end_date' => now(),
        ]);

        event(new MaintenanceCompleted($maintenance));

        return $maintenance;
    }
}
