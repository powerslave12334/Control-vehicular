<?php

namespace App\Domains\Maintenance\Actions;

use App\Domains\Maintenance\Models\Maintenance;
use App\Domains\Maintenance\Events\MaintenanceApproved;

class ApproveMaintenanceAction
{
    public function execute(int $id, int $approvedBy): Maintenance
    {
        $maintenance = Maintenance::findOrFail($id);

        if ($maintenance->status !== 'programado') {
            throw new \DomainException('Solo mantenimientos en estado "Programado" pueden ser aprobados.');
        }

        $maintenance->update([
            'status' => 'aprobado',
            'approved_by' => $approvedBy,
        ]);

        event(new MaintenanceApproved($maintenance));

        return $maintenance;
    }
}
