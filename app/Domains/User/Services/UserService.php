<?php

namespace App\Domains\User\Services;

use App\Domains\User\DTO\UserData;
use App\Domains\User\Models\User;

class UserService
{
    public function create(UserData $data): User
    {
        return User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => bcrypt($data->password),
            'role' => $data->role,
            'status' => $data->status,
            'phone' => $data->phone,
            'document_type' => $data->document_type,
            'document_number' => $data->document_number,
            'license_type' => $data->license_type,
            'emergency_contact' => $data->emergency_contact,
            'emergency_phone' => $data->emergency_phone,
            'address' => $data->address,
        ]);
    }

    public function update(User $user, UserData $data): User
    {
        $fields = [
            'name' => $data->name,
            'email' => $data->email,
            'role' => $data->role,
            'status' => $data->status,
            'phone' => $data->phone,
            'document_type' => $data->document_type,
            'document_number' => $data->document_number,
            'license_type' => $data->license_type,
            'emergency_contact' => $data->emergency_contact,
            'emergency_phone' => $data->emergency_phone,
            'address' => $data->address,
        ];

        if ($data->password) {
            $fields['password'] = bcrypt($data->password);
        }

        $user->update($fields);
        return $user->fresh();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function toggleStatus(User $user): User
    {
        $newStatus = $user->status === 'Activo' ? 'Inactivo' : 'Activo';
        $user->update(['status' => $newStatus]);
        return $user->fresh();
    }
}
