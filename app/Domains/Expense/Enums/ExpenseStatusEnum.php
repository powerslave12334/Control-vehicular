<?php

namespace App\Domains\Expense\Enums;

enum ExpenseStatusEnum: string
{
    case Pendiente = 'pendiente';
    case Aprobado = 'aprobado';
    case Rechazado = 'rechazado';
}
