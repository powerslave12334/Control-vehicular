<?php

namespace App\Domains\Incident\Enums;

enum IncidentSeverityEnum: string
{
    case Low = 'Baja';
    case Medium = 'Media';
    case High = 'Alta';
    case Critical = 'Crítica';
}
