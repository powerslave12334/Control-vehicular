<?php

namespace App\Domains\Gate\DTO;

class GateLogData
{
    public function __construct(
        public readonly int $vehicle_id,
        public readonly string $driver_name,
        public readonly string $type,
        public readonly ?string $photo = null,
        public readonly ?string $notes = null,
        public readonly ?string $logged_at = null,
    ) {}
}
