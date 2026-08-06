<?php

namespace App\Domains\WorkOrder\DTO;

readonly class WorkOrderData
{
    public function __construct(
        public string $code,
        public int $vehicle_id,
        public int $provider_id,
        public string $description,
        public int $requested_by,
        public ?int $operator_id = null,
        public ?int $maintenance_id = null,
        public string $priority = 'medium',
        public string $status = 'draft',
        public ?float $estimated_cost = null,
        public ?float $mileage_at_request = null,
        public ?string $notes = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            code: $data['code'],
            vehicle_id: (int)$data['vehicle_id'],
            provider_id: (int)$data['provider_id'],
            description: $data['description'],
            requested_by: (int)$data['requested_by'],
            operator_id: isset($data['operator_id']) ? (int)$data['operator_id'] : null,
            maintenance_id: isset($data['maintenance_id']) ? (int)$data['maintenance_id'] : null,
            priority: $data['priority'] ?? 'medium',
            status: $data['status'] ?? 'draft',
            estimated_cost: isset($data['estimated_cost']) ? (float)$data['estimated_cost'] : null,
            mileage_at_request: isset($data['mileage_at_request']) ? (float)$data['mileage_at_request'] : null,
            notes: $data['notes'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'vehicle_id' => $this->vehicle_id,
            'operator_id' => $this->operator_id,
            'maintenance_id' => $this->maintenance_id,
            'provider_id' => $this->provider_id,
            'description' => $this->description,
            'priority' => $this->priority,
            'status' => $this->status,
            'estimated_cost' => $this->estimated_cost,
            'mileage_at_request' => $this->mileage_at_request,
            'notes' => $this->notes,
            'requested_by' => $this->requested_by,
        ];
    }
}
