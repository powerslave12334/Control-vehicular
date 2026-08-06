<?php

namespace App\Domains\Operator\Enums;

enum OperatorStatusEnum: string
{
    case Activo = 'Activo';
    case Inactivo = 'Inactivo';
    case Suspendido = 'Suspendido';
}
