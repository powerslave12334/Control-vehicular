<?php

namespace App\Domains\Insurance\DTO;

readonly class InsuranceData
{
    public function __construct(
        public int $vehicle_id,
        public string $policy_number,
        public string $insurer,
        public string $coverage_type,
        public string $start_date,
        public string $end_date,
        public float $premium,
        public float $deductible,
        public string $status = 'active',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            vehicle_id: (int)$data['vehicle_id'],
            policy_number: $data['policy_number'],
            insurer: $data['insurer'],
            coverage_type: $data['coverage_type'],
            start_date: $data['start_date'],
            end_date: $data['end_date'],
            premium: (float)$data['premium'],
            deductible: (float)$data['deductible'],
            status: $data['status'] ?? 'active',
        );
    }

    public function toArray(): array
    {
        return [
            'vehicle_id' => $this->vehicle_id,
            'policy_number' => $this->policy_number,
            'insurer' => $this->insurer,
            'coverage_type' => $this->coverage_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'premium' => $this->premium,
            'deductible' => $this->deductible,
            'status' => $this->status,
        ];
    }
}
