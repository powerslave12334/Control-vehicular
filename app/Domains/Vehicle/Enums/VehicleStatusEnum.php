<?php

namespace App\Domains\Vehicle\Enums;

enum VehicleStatusEnum: string
{
    case Activa = 'Activa';
    case EnMantenimiento = 'En mantenimiento';
    case FueraDeServicio = 'Fuera de servicio';
}
