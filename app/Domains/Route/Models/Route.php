<?php

namespace App\Domains\Route\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Fuel\Models\Refuel;
use App\Domains\Expense\Models\Expense;
use App\Domains\Route\Enums\RouteStatusEnum;
use App\Domains\User\Models\User;
use App\Shared\Traits\HasAuditColumns;

class Route extends Model
{
    use SoftDeletes, HasAuditColumns;

    protected $table = 'routes';

    protected $fillable = [
        'date', 'week', 'driver_id', 'driver_name', 'assistant_id',
        'assistant_name', 'vehicle_id', 'vehicle_plate', 'client_name',
        'planned_km', 'actual_km', 'status',
        'code', 'description', 'origin', 'destination', 'destinations',
        'distance_km', 'estimated_duration',
        'start_odometer', 'end_odometer',
        'started_at', 'finished_at', 'cancelled_at', 'cancellation_reason',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'planned_km' => 'decimal:2',
            'actual_km' => 'decimal:2',
            'distance_km' => 'decimal:2',
            'start_odometer' => 'decimal:2',
            'end_odometer' => 'decimal:2',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'status' => RouteStatusEnum::class,
            'destinations' => 'array',
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

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function steps()
    {
        return $this->hasMany(RouteStep::class);
    }

    public function refuels()
    {
        return $this->hasMany(Refuel::class);
    }

    public function extraordinaryMovements()
    {
        return $this->hasMany(ExtraordinaryMovement::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
