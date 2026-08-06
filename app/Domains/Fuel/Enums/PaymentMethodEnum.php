<?php

namespace App\Domains\Fuel\Enums;

enum PaymentMethodEnum: string
{
    case Tarjeta = 'Tarjeta';
    case Efectivo = 'Efectivo';
    case Vales = 'Vales';
}
