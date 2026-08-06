<?php

namespace App\Domains\Incident\DTO;

readonly class IncidentData
{
    public function __construct(
        public string $date,
        public ?string $time,
        public int $vehicle_id,
        public int $driver_id,
        public string $driver_name,
        public string $description,
        public string $severity,
        public string $status = 'reported',
        public ?string $type = null,
        public ?string $location = null,
        public ?string $photo = null,
        public ?float $cost = null,
        public bool $involves_third_party = false,
        public ?array $third_party_data = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            date: $data['date'],
            time: $data['time'] ?? null,
            vehicle_id: (int)$data['vehicle_id'],
            driver_id: (int)$data['driver_id'],
            driver_name: $data['driver_name'],
            description: $data['description'],
            severity: $data['severity'],
            status: $data['status'] ?? 'reported',
            type: $data['type'] ?? null,
            location: $data['location'] ?? null,
            photo: $data['photo'] ?? null,
            cost: isset($data['cost']) ? (float)$data['cost'] : null,
            involves_third_party: (bool)($data['involves_third_party'] ?? false),
            third_party_data: $data['third_party_data'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'date' => $this->date,
            'time' => $this->time,
            'vehicle_id' => $this->vehicle_id,
            'driver_id' => $this->driver_id,
            'driver_name' => $this->driver_name,
            'description' => $this->description,
            'severity' => $this->severity,
            'status' => $this->status,
            'type' => $this->type,
            'location' => $this->location,
            'photo' => $this->photo,
            'cost' => $this->cost,
            'involves_third_party' => $this->involves_third_party,
            'third_party_data' => $this->third_party_data,
        ];
    }
}
