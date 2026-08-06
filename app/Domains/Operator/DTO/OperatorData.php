<?php

namespace App\Domains\Operator\DTO;

readonly class OperatorData
{
    public function __construct(
        public string $name,
        public string $phone,
        public string $status = 'Activo',
        public ?string $document_type = null,
        public ?string $document_number = null,
        public ?string $email = null,
        public ?string $address = null,
        public ?string $blood_type = null,
        public ?string $emergency_contact = null,
        public ?string $emergency_phone = null,
        public ?string $avatar = null,
        public ?string $license_type = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            phone: $data['phone'],
            status: $data['status'] ?? 'Activo',
            document_type: $data['document_type'] ?? null,
            document_number: $data['document_number'] ?? null,
            email: $data['email'] ?? null,
            address: $data['address'] ?? null,
            blood_type: $data['blood_type'] ?? null,
            emergency_contact: $data['emergency_contact'] ?? null,
            emergency_phone: $data['emergency_phone'] ?? null,
            avatar: $data['avatar'] ?? null,
            license_type: $data['license_type'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'status' => $this->status,
            'document_type' => $this->document_type,
            'document_number' => $this->document_number,
            'email' => $this->email,
            'address' => $this->address,
            'blood_type' => $this->blood_type,
            'emergency_contact' => $this->emergency_contact,
            'emergency_phone' => $this->emergency_phone,
            'avatar' => $this->avatar,
            'license_type' => $this->license_type,
        ];
    }
}
