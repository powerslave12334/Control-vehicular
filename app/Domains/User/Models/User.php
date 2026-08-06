<?php

namespace App\Domains\User\Models;

use App\Domains\User\Enums\UserStatusEnum;
use App\Models\Module;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    protected $fillable = [
        'name', 'email', 'password', 'role', 'status', 'phone',
        'assigned_vehicle_id',
        'document_type', 'document_number', 'license_type',
        'emergency_contact', 'emergency_phone', 'address',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatusEnum::class,
        ];
    }

    public function notifications()
    {
        return $this->hasMany(\App\Domains\Notification\Models\Notification::class);
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_user', 'user_id', 'module_id');
    }

    public function hasModule(string $moduleId): bool
    {
        return $this->modules->contains('id', $moduleId);
    }
}
