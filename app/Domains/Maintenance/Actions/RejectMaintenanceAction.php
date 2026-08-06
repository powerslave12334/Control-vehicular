<?php

namespace App\Domains\Maintenance\Actions;

use App\Domains\Maintenance\Models\Maintenance;
use App\Domains\Maintenance\Events\MaintenanceRejected;

class RejectMaintenanceAction
{
    public function execute(int $id, string $reason): Maintenance
    {
        $maintenance = Maintenance::findOrFail($id);

        if ($maintenance->status !== 'programado') {
            throw new \DomainException('Solo mantenimientos en estado "Programado" pueden ser rechazados.');
        }

        $maintenance->update([
            'status' => 'rechazado',
            'rejection_reason' => $reason,
        ]);

        event(new MaintenanceRejected($maintenance));

        return $maintenance;
    }
}
