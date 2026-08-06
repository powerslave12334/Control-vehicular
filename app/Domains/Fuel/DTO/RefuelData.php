<?php

namespace App\Domains\Fuel\DTO;

readonly class RefuelData
{
    public function __construct(
        public string $date,
        public int $vehicle_id,
        public int $driver_id,
        public string $driver_name,
        public float $liters,
        public float $amount,
        public float $price_per_liter,
        public string $payment_method,
        public ?int $route_id = null,
        public ?string $ticket_photo = null,
        public ?int $odometer = null,
        public ?string $folio = null,
        public ?int $fuel_station_id = null,
        public ?int $fuel_card_id = null,
        public string $status = 'pendiente',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            date: $data['date'],
            vehicle_id: (int)$data['vehicle_id'],
            driver_id: (int)$data['driver_id'],
            driver_name: $data['driver_name'],
            liters: (float)$data['liters'],
            amount: (float)$data['amount'],
            price_per_liter: (float)$data['price_per_liter'],
            payment_method: $data['payment_method'],
            route_id: isset($data['route_id']) ? (int)$data['route_id'] : null,
            ticket_photo: $data['ticket_photo'] ?? null,
            odometer: isset($data['odometer']) ? (int)$data['odometer'] : null,
            folio: $data['folio'] ?? null,
            fuel_station_id: isset($data['fuel_station_id']) ? (int)$data['fuel_station_id'] : null,
            fuel_card_id: isset($data['fuel_card_id']) ? (int)$data['fuel_card_id'] : null,
            status: $data['status'] ?? 'pendiente',
        );
    }

    public function toArray(): array
    {
        return [
            'date' => $this->date,
            'route_id' => $this->route_id,
            'vehicle_id' => $this->vehicle_id,
            'driver_id' => $this->driver_id,
            'driver_name' => $this->driver_name,
            'liters' => $this->liters,
            'amount' => $this->amount,
            'price_per_liter' => $this->price_per_liter,
            'payment_method' => $this->payment_method,
            'ticket_photo' => $this->ticket_photo,
            'odometer' => $this->odometer,
            'folio' => $this->folio,
            'fuel_station_id' => $this->fuel_station_id,
            'fuel_card_id' => $this->fuel_card_id,
            'status' => $this->status,
        ];
    }
}
