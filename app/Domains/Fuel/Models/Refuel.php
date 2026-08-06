<?php

namespace App\Domains\Fuel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Route\Models\Route;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Operator\Models\Operator;
use App\Domains\Fuel\Enums\PaymentMethodEnum;
use App\Domains\User\Models\User;
use App\Shared\Traits\HasAuditColumns;

class Refuel extends Model
{
    use SoftDeletes, HasAuditColumns;

    protected $table = 'refuels';

    protected $fillable = [
        'date', 'route_id', 'vehicle_id', 'driver_id', 'driver_name',
        'liters', 'amount', 'price_per_liter', 'payment_method',
        'ticket_photo', 'odometer', 'fuel_level',
        'folio', 'fuel_station_id', 'fuel_card_id', 'status',
        'approved_by', 'rejection_reason',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'liters' => 'decimal:2',
            'amount' => 'decimal:2',
            'price_per_liter' => 'decimal:2',
            'payment_method' => PaymentMethodEnum::class,
            'odometer' => 'integer',
        ];
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function fuelStation()
    {
        return $this->belongsTo(FuelStation::class);
    }

    public function fuelCard()
    {
        return $this->belongsTo(FuelCard::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
