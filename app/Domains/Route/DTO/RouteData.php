<?php

namespace App\Domains\Route\DTO;

readonly class RouteData
{
    public function __construct(
        public string $date,
        public int $driver_id,
        public string $driver_name,
        public int $vehicle_id,
        public string $vehicle_plate,
        public ?string $code = null,
        public ?string $description = null,
        public ?string $origin = null,
        public ?string $destination = null,
        public ?array $destinations = null,
        public ?float $distance_km = null,
        public ?float $estimated_duration = null,
        public ?string $week = null,
        public ?int $assistant_id = null,
        public ?string $assistant_name = null,
        public ?string $client_name = null,
        public ?float $planned_km = null,
        public ?float $actual_km = null,
        public string $status = 'Programada',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            date: $data['date'],
            driver_id: (int)$data['driver_id'],
            driver_name: $data['driver_name'],
            vehicle_id: (int)$data['vehicle_id'],
            vehicle_plate: $data['vehicle_plate'],
            code: $data['code'] ?? null,
            description: $data['description'] ?? null,
            origin: $data['origin'] ?? null,
            destination: $data['destination'] ?? null,
            destinations: isset($data['destinations']) && is_array($data['destinations']) ? array_values($data['destinations']) : null,
            distance_km: isset($data['distance_km']) ? (float)$data['distance_km'] : null,
            estimated_duration: isset($data['estimated_duration']) ? (float)$data['estimated_duration'] : null,
            week: $data['week'] ?? null,
            assistant_id: isset($data['assistant_id']) ? (int)$data['assistant_id'] : null,
            assistant_name: $data['assistant_name'] ?? null,
            client_name: $data['client_name'] ?? null,
            planned_km: isset($data['planned_km']) ? (float)$data['planned_km'] : null,
            actual_km: isset($data['actual_km']) ? (float)$data['actual_km'] : null,
            status: $data['status'] ?? 'Programada',
        );
    }

    public function toArray(): array
    {
        return [
            'date' => $this->date,
            'driver_id' => $this->driver_id,
            'driver_name' => $this->driver_name,
            'vehicle_id' => $this->vehicle_id,
            'vehicle_plate' => $this->vehicle_plate,
            'code' => $this->code,
            'description' => $this->description,
            'origin' => $this->origin,
            'destination' => $this->destination,
            'destinations' => $this->destinations,
            'distance_km' => $this->distance_km,
            'estimated_duration' => $this->estimated_duration,
            'week' => $this->week,
            'assistant_id' => $this->assistant_id,
            'assistant_name' => $this->assistant_name,
            'client_name' => $this->client_name,
            'planned_km' => $this->planned_km,
            'actual_km' => $this->actual_km,
            'status' => $this->status,
        ];
    }
}
