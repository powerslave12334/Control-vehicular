<?php

namespace App\Domains\Vehicle\DTO;

readonly class VehicleData
{
    public function __construct(
        public string $brand,
        public string $model,
        public int $year,
        public string $plate,
        public string $fuel_type,
        public string $cargo_capacity,
        public float $tank_capacity,
        public bool $gps_installed,
        public string $vin,
        public string $status,
        public int $current_odometer,
        public ?string $color = null,
        public ?string $engine = null,
        public ?string $acquisition_date = null,
        public ?float $acquisition_cost = null,
        public ?string $vehicle_type = null,
        public ?int $assigned_driver_id = null,
        public ?string $last_maintenance = null,
        public ?string $next_maintenance = null,
        public int $incidents_count = 0,
        public float $authorized_fuel = 100.0,
        public ?string $expense_card_number = null,
        public float $authorized_expense = 0.0,
        public ?string $notes = null,
        public ?string $responsible_user = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            brand: $data['brand'],
            model: $data['model'],
            year: (int)$data['year'],
            plate: $data['plate'],
            fuel_type: $data['fuel_type'],
            cargo_capacity: $data['cargo_capacity'],
            tank_capacity: (float)$data['tank_capacity'],
            gps_installed: (bool)($data['gps_installed'] ?? false),
            vin: $data['vin'],
            status: $data['status'] ?? 'Activa',
            current_odometer: (int)$data['current_odometer'],
            color: $data['color'] ?? null,
            engine: $data['engine'] ?? null,
            acquisition_date: $data['acquisition_date'] ?? null,
            acquisition_cost: isset($data['acquisition_cost']) ? (float)$data['acquisition_cost'] : null,
            vehicle_type: $data['vehicle_type'] ?? null,
            assigned_driver_id: isset($data['assigned_driver_id']) ? (int)$data['assigned_driver_id'] : null,
            last_maintenance: $data['last_maintenance'] ?? null,
            next_maintenance: $data['next_maintenance'] ?? null,
            incidents_count: (int)($data['incidents_count'] ?? 0),
            authorized_fuel: (float)($data['authorized_fuel'] ?? 100.0),
            expense_card_number: $data['expense_card_number'] ?? null,
            authorized_expense: (float)($data['authorized_expense'] ?? 0.0),
            notes: $data['notes'] ?? null,
            responsible_user: $data['responsible_user'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => $this->year,
            'plate' => $this->plate,
            'fuel_type' => $this->fuel_type,
            'cargo_capacity' => $this->cargo_capacity,
            'tank_capacity' => $this->tank_capacity,
            'gps_installed' => $this->gps_installed,
            'vin' => $this->vin,
            'color' => $this->color,
            'engine' => $this->engine,
            'acquisition_date' => $this->acquisition_date,
            'acquisition_cost' => $this->acquisition_cost,
            'vehicle_type' => $this->vehicle_type,
            'assigned_driver_id' => $this->assigned_driver_id,
            'status' => $this->status,
            'current_odometer' => $this->current_odometer,
            'last_maintenance' => $this->last_maintenance,
            'next_maintenance' => $this->next_maintenance,
            'incidents_count' => $this->incidents_count,
            'authorized_fuel' => $this->authorized_fuel,
            'expense_card_number' => $this->expense_card_number,
            'authorized_expense' => $this->authorized_expense,
            'notes' => $this->notes,
            'responsible_user' => $this->responsible_user,
        ];
    }
}
