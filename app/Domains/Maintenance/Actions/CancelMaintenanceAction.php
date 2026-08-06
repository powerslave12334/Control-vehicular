<?php

namespace App\Domains\Maintenance\Actions;

use App\Domains\Maintenance\Models\Maintenance;
use App\Domains\Maintenance\Events\MaintenanceCancelled;

class CancelMaintenanceAction
{
    public function execute(int $id, string $reason): Maintenance
    {
        $maintenance = Maintenance::findOrFail($id);

        if (in_array($maintenance->status, ['completado', 'cancelado'])) {
            throw new \DomainException('No se puede cancelar un mantenimiento ya completado o cancelado.');
        }

        $maintenance->update([
            'status' => 'cancelado',
            'rejection_reason' => $reason,
        ]);

        event(new MaintenanceCancelled($maintenance));

        return $maintenance;
    }
}
