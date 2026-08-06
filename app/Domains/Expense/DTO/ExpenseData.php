<?php

namespace App\Domains\Expense\DTO;

readonly class ExpenseData
{
    public function __construct(
        public int $vehicle_id,
        public ?int $route_id = null,
        public string $type,
        public string $description,
        public float $amount,
        public string $date,
        public ?int $operator_id = null,
        public ?string $folio = null,
        public ?string $provider_name = null,
        public string $status = 'aprobado',
        public ?string $evidence = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            vehicle_id: (int)$data['vehicle_id'],
            route_id: isset($data['route_id']) ? (int)$data['route_id'] : null,
            type: $data['type'],
            description: $data['description'],
            amount: (float)$data['amount'],
            date: $data['date'],
            operator_id: isset($data['operator_id']) ? (int)$data['operator_id'] : null,
            folio: $data['folio'] ?? null,
            provider_name: $data['provider_name'] ?? null,
            status: $data['status'] ?? 'aprobado',
            evidence: $data['evidence'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'vehicle_id' => $this->vehicle_id,
            'route_id' => $this->route_id,
            'operator_id' => $this->operator_id,
            'type' => $this->type,
            'description' => $this->description,
            'amount' => $this->amount,
            'date' => $this->date,
            'folio' => $this->folio,
            'provider_name' => $this->provider_name,
            'status' => $this->status,
            'evidence' => $this->evidence,
        ];
    }
}
