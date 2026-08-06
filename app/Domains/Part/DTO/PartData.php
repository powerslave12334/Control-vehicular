<?php

namespace App\Domains\Part\DTO;

readonly class PartData
{
    public function __construct(
        public string $name,
        public int $part_category_id,
        public string $sku,
        public float $unit_price,
        public string $unit_type,
        public ?int $part_brand_id = null,
        public ?string $description = null,
        public int $current_stock = 0,
        public int $min_stock = 0,
        public ?int $max_stock = null,
        public ?string $location = null,
        public string $status = 'active',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            part_category_id: (int)$data['part_category_id'],
            sku: $data['sku'],
            unit_price: (float)$data['unit_price'],
            unit_type: $data['unit_type'],
            part_brand_id: isset($data['part_brand_id']) ? (int)$data['part_brand_id'] : null,
            description: $data['description'] ?? null,
            current_stock: (int)($data['current_stock'] ?? 0),
            min_stock: (int)($data['min_stock'] ?? 0),
            max_stock: isset($data['max_stock']) ? (int)$data['max_stock'] : null,
            location: $data['location'] ?? null,
            status: $data['status'] ?? 'active',
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'part_category_id' => $this->part_category_id,
            'part_brand_id' => $this->part_brand_id,
            'sku' => $this->sku,
            'description' => $this->description,
            'unit_price' => $this->unit_price,
            'current_stock' => $this->current_stock,
            'min_stock' => $this->min_stock,
            'max_stock' => $this->max_stock,
            'unit_type' => $this->unit_type,
            'location' => $this->location,
            'status' => $this->status,
        ];
    }
}
