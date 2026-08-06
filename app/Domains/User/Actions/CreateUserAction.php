<?php

namespace App\Domains\User\Actions;

use App\Domains\User\DTO\UserData;
use App\Domains\User\Events\UserCreated;
use App\Domains\User\Models\User;

class CreateUserAction
{
    public function execute(UserData $data): User
    {
        $user = User::create([
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

        event(new UserCreated($user));

        return $user;
    }
}
