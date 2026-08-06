<?php

namespace App\Domains\Maintenance\DTO;

readonly class MaintenanceData
{
    public function __construct(
        public string $date,
        public int $vehicle_id,
        public string $type,
        public string $category,
        public string $description,
        public float $cost,
        public string $workshop,
        public ?string $evidence = null,
        public ?int $odometer = null,
        public ?int $workshop_id = null,
        public ?string $scheduled_date = null,
        public ?string $start_date = null,
        public ?string $end_date = null,
        public ?int $requested_by = null,
        public string $status = 'programado',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            date: $data['date'],
            vehicle_id: (int) $data['vehicle_id'],
            type: $data['type'],
            category: $data['category'] ?? '',
            description: $data['description'],
            cost: (float) $data['cost'],
            workshop: $data['workshop'],
            evidence: $data['evidence'] ?? null,
            odometer: isset($data['odometer']) ? (int) $data['odometer'] : null,
            workshop_id: isset($data['workshop_id']) ? (int) $data['workshop_id'] : null,
            scheduled_date: $data['scheduled_date'] ?? null,
            start_date: $data['start_date'] ?? null,
            end_date: $data['end_date'] ?? null,
            requested_by: isset($data['requested_by']) ? (int) $data['requested_by'] : null,
            status: $data['status'] ?? 'programado',
        );
    }

    public function toArray(): array
    {
        return [
            'date' => $this->date,
            'vehicle_id' => $this->vehicle_id,
            'type' => $this->type,
            'category' => $this->category,
            'description' => $this->description,
            'cost' => $this->cost,
            'workshop' => $this->workshop,
            'evidence' => $this->evidence,
            'odometer' => $this->odometer,
            'workshop_id' => $this->workshop_id,
            'scheduled_date' => $this->scheduled_date,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'requested_by' => $this->requested_by,
            'status' => $this->status,
        ];
    }
}
