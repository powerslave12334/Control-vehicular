<?php

namespace App\Domains\Maintenance\Actions;

use App\Domains\Maintenance\Models\Maintenance;
use App\Domains\Maintenance\Events\MaintenanceStarted;

class StartMaintenanceAction
{
    public function execute(int $id): Maintenance
    {
        $maintenance = Maintenance::findOrFail($id);

        if (!in_array($maintenance->status, ['aprobado', 'en_progreso'])) {
            throw new \DomainException('El mantenimiento debe estar aprobado para iniciarse.');
        }

        $maintenance->update([
            'status' => 'en_progreso',
            'start_date' => now(),
        ]);

        event(new MaintenanceStarted($maintenance));

        return $maintenance;
    }
}
