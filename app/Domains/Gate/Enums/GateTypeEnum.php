<?php

namespace App\Domains\Gate\Enums;

enum GateTypeEnum: string
{
    case Entry = 'entry';
    case Exit = 'exit';
}
