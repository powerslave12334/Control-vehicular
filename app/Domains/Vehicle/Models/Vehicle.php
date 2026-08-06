<?php

namespace App\Domains\Vehicle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Route\Models\Route;
use App\Domains\Fuel\Models\Refuel;
use App\Domains\Maintenance\Models\Maintenance;
use App\Domains\Incident\Models\Incident;
use App\Domains\Vehicle\Enums\VehicleStatusEnum;
use App\Domains\User\Models\User;
use App\Shared\Traits\HasAuditColumns;

class Vehicle extends Model
{
    use SoftDeletes, HasAuditColumns;

    protected $fillable = [
        'brand', 'model', 'year', 'plate', 'fuel_type', 'cargo_capacity',
        'tank_capacity', 'gps_installed', 'vin', 'color', 'engine',
        'acquisition_date', 'acquisition_cost',
        'status', 'current_odometer',
        'last_maintenance', 'next_maintenance', 'incidents_count',
        'authorized_fuel', 'notes', 'responsible_user',
        'brand_id', 'model_id', 'fuel_type_id',
        'assigned_driver_id', 'vehicle_type',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'gps_installed' => 'boolean',
            'year' => 'integer',
            'tank_capacity' => 'decimal:2',
            'authorized_fuel' => 'decimal:2',
            'acquisition_cost' => 'decimal:2',
            'acquisition_date' => 'date',
            'last_maintenance' => 'date',
            'next_maintenance' => 'date',
            'status' => VehicleStatusEnum::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Vehicle $vehicle) {
            if ($vehicle->brand && !$vehicle->brand_id) {
                $vehicle->brand_id = VehicleBrand::firstOrCreate(['name' => $vehicle->brand])->id;
            }
            if ($vehicle->model && !$vehicle->model_id && $vehicle->brand_id) {
                $vehicle->model_id = VehicleModel::firstOrCreate(
                    ['vehicle_brand_id' => $vehicle->brand_id, 'name' => $vehicle->model]
                )->id;
            }
            if ($vehicle->fuel_type && !$vehicle->fuel_type_id) {
                $vehicle->fuel_type_id = FuelType::firstOrCreate(['name' => $vehicle->fuel_type])->id;
            }
        });

        static::updating(function (Vehicle $vehicle) {
            if ($vehicle->isDirty('brand') && !$vehicle->isDirty('brand_id')) {
                $vehicle->brand_id = VehicleBrand::firstOrCreate(['name' => $vehicle->brand])->id;
            }
            if ($vehicle->isDirty('model') && !$vehicle->isDirty('model_id') && $vehicle->brand_id) {
                $vehicle->model_id = VehicleModel::firstOrCreate(
                    ['vehicle_brand_id' => $vehicle->brand_id, 'name' => $vehicle->model]
                )->id;
            }
            if ($vehicle->isDirty('fuel_type') && !$vehicle->isDirty('fuel_type_id')) {
                $vehicle->fuel_type_id = FuelType::firstOrCreate(['name' => $vehicle->fuel_type])->id;
            }
        });
    }

    public function vehicleBrand()
    {
        return $this->belongsTo(VehicleBrand::class, 'brand_id');
    }

    public function vehicleModel()
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    public function vehicleFuelType()
    {
        return $this->belongsTo(FuelType::class, 'fuel_type_id');
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
        return $this->hasMany(Route::class);
    }

    public function refuels()
    {
        return $this->hasMany(Refuel::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function assignedDriver()
    {
        return $this->belongsTo(User::class, 'assigned_driver_id');
    }

    public function tires()
    {
        return $this->hasMany(Tire::class);
    }

    public function logs()
    {
        return $this->hasMany(VehicleLog::class);
    }
}
