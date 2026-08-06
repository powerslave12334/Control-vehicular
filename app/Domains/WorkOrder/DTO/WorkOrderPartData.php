<?php

namespace App\Domains\WorkOrder\DTO;

readonly class WorkOrderPartData
{
    public function __construct(
        public int $work_order_id,
        public string $description,
        public int $quantity,
        public float $unit_cost,
        public ?int $part_id = null,
        public string $source = 'inventory',
        public ?string $notes = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            work_order_id: (int)$data['work_order_id'],
            description: $data['description'],
            quantity: (int)$data['quantity'],
            unit_cost: (float)$data['unit_cost'],
            part_id: isset($data['part_id']) ? (int)$data['part_id'] : null,
            source: $data['source'] ?? 'inventory',
            notes: $data['notes'] ?? null,
        );
    }

    public function toArray(): array
    {
        $total = $this->quantity * $this->unit_cost;
        return [
            'work_order_id' => $this->work_order_id,
            'part_id' => $this->part_id,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'unit_cost' => $this->unit_cost,
            'total_cost' => $total,
            'source' => $this->source,
            'notes' => $this->notes,
        ];
    }
}
