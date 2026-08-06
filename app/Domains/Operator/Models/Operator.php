<?php

namespace App\Domains\Operator\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Route\Models\Route;
use App\Domains\Fuel\Models\Refuel;
use App\Domains\Incident\Models\Incident;
use App\Domains\Operator\Enums\OperatorStatusEnum;
use App\Domains\User\Models\User;
use App\Shared\Traits\HasAuditColumns;

class Operator extends Model
{
    use SoftDeletes, HasAuditColumns;

    protected $fillable = [
        'name', 'document_type', 'document_number', 'phone', 'email',
        'address', 'blood_type', 'emergency_contact', 'emergency_phone',
        'avatar', 'license_type', 'status',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => OperatorStatusEnum::class,
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function routes()
    {
        return $this->hasMany(Route::class, 'driver_id');
    }

    public function refuels()
    {
        return $this->hasMany(Refuel::class, 'driver_id');
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class, 'driver_id');
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }

    public function activeLicense()
    {
        return $this->hasOne(License::class)->where('status', 'active')->latest();
    }

    public function assignedVehicle()
    {
        return $this->hasOne(\App\Domains\Vehicle\Models\Vehicle::class, 'assigned_driver_id');
    }
}
