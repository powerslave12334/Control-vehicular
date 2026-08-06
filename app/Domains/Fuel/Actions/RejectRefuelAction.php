<?php

namespace App\Domains\Fuel\Actions;

use App\Domains\Fuel\Models\Refuel;
use App\Domains\Fuel\Events\FuelRejected;
use RuntimeException;

class RejectRefuelAction
{
    public function execute(int $id, string $reason): Refuel
    {
        $refuel = Refuel::findOrFail($id);

        if ($refuel->status === 'aprobado') {
            throw new RuntimeException("No se puede rechazar una recarga ya aprobada.");
        }

        if ($refuel->status === 'rechazado') {
            return $refuel;
        }

        $refuel->status = 'rechazado';
        $refuel->rejection_reason = $reason;
        $refuel->save();

        event(new FuelRejected($refuel, $reason));

        return $refuel;
    }
}
