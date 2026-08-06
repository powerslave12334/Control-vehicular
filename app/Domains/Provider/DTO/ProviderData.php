<?php

namespace App\Domains\Provider\DTO;

readonly class ProviderData
{
    public function __construct(
        public string $name,
        public string $type,
        public ?string $contact = null,
        public ?string $phone = null,
        public ?string $email = null,
        public ?string $address = null,
        public ?string $rfc = null,
        public string $status = 'active',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            type: $data['type'],
            contact: $data['contact'] ?? null,
            phone: $data['phone'] ?? null,
            email: $data['email'] ?? null,
            address: $data['address'] ?? null,
            rfc: $data['rfc'] ?? null,
            status: $data['status'] ?? 'active',
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'contact' => $this->contact,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'rfc' => $this->rfc,
            'status' => $this->status,
        ];
    }
}
