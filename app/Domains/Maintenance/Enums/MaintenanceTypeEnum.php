<?php

namespace App\Domains\Maintenance\Enums;

enum MaintenanceTypeEnum: string
{
    case Preventivo = 'Preventivo';
    case Correctivo = 'Correctivo';
}
