<?php

namespace App\Domains\Route\Enums;

enum RouteStatusEnum: string
{
    case Programada = 'Programada';
    case Asignada = 'Asignada';
    case EnTransito = 'En tránsito';
    case Instalando = 'Instalando';
    case Completada = 'Completada';
    case Cancelada = 'Cancelada';
}
