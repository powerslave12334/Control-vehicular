<?php

namespace App\Domains\Document\DTO;

readonly class DocumentData
{
    public function __construct(
        public string $documentable_type,
        public int $documentable_id,
        public string $type,
        public string $name,
        public string $file_path,
        public ?string $issuance_date = null,
        public ?string $expiry_date = null,
        public string $status = 'active',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            documentable_type: $data['documentable_type'],
            documentable_id: (int)$data['documentable_id'],
            type: $data['type'],
            name: $data['name'],
            file_path: $data['file_path'],
            issuance_date: $data['issuance_date'] ?? null,
            expiry_date: $data['expiry_date'] ?? null,
            status: $data['status'] ?? 'active',
        );
    }

    public function toArray(): array
    {
        return [
            'documentable_type' => $this->documentable_type,
            'documentable_id' => $this->documentable_id,
            'type' => $this->type,
            'name' => $this->name,
            'file_path' => $this->file_path,
            'issuance_date' => $this->issuance_date,
            'expiry_date' => $this->expiry_date,
            'status' => $this->status,
        ];
    }
}
