<?php

namespace App\Domains\Incident\Enums;

enum IncidentStatusEnum: string
{
    case Reported = 'Reportada';
    case Investigating = 'Atendida';
    case Resolved = 'Resuelta';
    case Closed = 'Cerrada';
}
