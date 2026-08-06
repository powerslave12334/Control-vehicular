<?php

namespace App\Domains\User\Enums;

enum UserStatusEnum: string
{
    case Active = 'Activo';
    case Inactive = 'Inactivo';
}
