<?php

namespace App\Domains\Inspection\DTO;

readonly class InspectionData
{
    public function __construct(
        public int $vehicle_id,
        public int $operator_id,
        public string $type,
        public string $performed_at,
        public ?float $mileage = null,
        public string $result = 'passed',
        public ?string $notes = null,
        public ?array $checklist_results = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            vehicle_id: (int)$data['vehicle_id'],
            operator_id: (int)$data['operator_id'],
            type: $data['type'],
            performed_at: $data['performed_at'],
            mileage: isset($data['mileage']) ? (float)$data['mileage'] : null,
            result: $data['result'] ?? 'passed',
            notes: $data['notes'] ?? null,
            checklist_results: $data['checklist_results'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'vehicle_id' => $this->vehicle_id,
            'operator_id' => $this->operator_id,
            'type' => $this->type,
            'performed_at' => $this->performed_at,
            'mileage' => $this->mileage,
            'result' => $this->result,
            'notes' => $this->notes,
            'checklist_results' => $this->checklist_results,
        ];
    }
}
