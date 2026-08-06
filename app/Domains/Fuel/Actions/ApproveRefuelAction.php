<?php

namespace App\Domains\Fuel\Actions;

use App\Domains\Fuel\Models\Refuel;
use App\Domains\Fuel\Events\FuelApproved;
use RuntimeException;

class ApproveRefuelAction
{
    public function execute(int $id, int $approvedBy): Refuel
    {
        $refuel = Refuel::findOrFail($id);

        if ($refuel->status === 'aprobado') {
            return $refuel;
        }

        if ($refuel->status === 'rechazado') {
            throw new RuntimeException("No se puede aprobar una recarga previamente rechazada.");
        }

        $refuel->status = 'aprobado';
        $refuel->approved_by = $approvedBy;
        $refuel->save();

        event(new FuelApproved($refuel));

        return $refuel;
    }
}
