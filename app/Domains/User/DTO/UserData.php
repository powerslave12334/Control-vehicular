<?php

namespace App\Domains\User\DTO;

class UserData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $role,
        public readonly string $status = 'Activo',
        public readonly ?string $phone = null,
        public readonly ?string $document_type = null,
        public readonly ?string $document_number = null,
        public readonly ?string $license_type = null,
        public readonly ?string $emergency_contact = null,
        public readonly ?string $emergency_phone = null,
        public readonly ?string $address = null,
    ) {}
}
