<?php

namespace App\Domains\User\Actions;

use App\Domains\User\DTO\UserData;
use App\Domains\User\Events\UserUpdated;
use App\Domains\User\Models\User;

class UpdateUserAction
{
    public function execute(User $user, UserData $data): User
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
        $user->refresh();

        event(new UserUpdated($user));

        return $user;
    }
}
