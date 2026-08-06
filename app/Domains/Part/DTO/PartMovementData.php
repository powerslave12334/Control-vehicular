<?php

namespace App\Domains\Part\DTO;

readonly class PartMovementData
{
    public function __construct(
        public int $part_id,
        public int $quantity,
        public string $movement_type,
        public int $user_id,
        public ?string $reference_type = null,
        public ?int $reference_id = null,
        public ?float $unit_cost = null,
        public ?string $notes = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            part_id: (int)$data['part_id'],
            quantity: (int)$data['quantity'],
            movement_type: $data['movement_type'],
            user_id: (int)$data['user_id'],
            reference_type: $data['reference_type'] ?? null,
            reference_id: isset($data['reference_id']) ? (int)$data['reference_id'] : null,
            unit_cost: isset($data['unit_cost']) ? (float)$data['unit_cost'] : null,
            notes: $data['notes'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'part_id' => $this->part_id,
            'quantity' => $this->quantity,
            'movement_type' => $this->movement_type,
            'user_id' => $this->user_id,
            'reference_type' => $this->reference_type,
            'reference_id' => $this->reference_id,
            'unit_cost' => $this->unit_cost,
            'notes' => $this->notes,
        ];
    }
}
