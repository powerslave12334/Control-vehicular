<?php

namespace App\Domains\Gate\Models;

use App\Domains\Gate\Enums\GateTypeEnum;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class GateLog extends Model
{
    protected $table = 'gate_logs';

    protected $fillable = [
        'vehicle_id', 'driver_name', 'type', 'photo', 'driver_photo',
        'notes', 'logged_at', 'created_by',
        'route_folio', 'initial_odometer', 'fuel_level',
        'has_spare_tire', 'vehicle_condition',
        'entry_date', 'entry_time', 'exit_date', 'exit_time',
        'signature', 'checklist', 'confirmed',
    ];

    protected function casts(): array
    {
        return [
            'type' => GateTypeEnum::class,
            'logged_at' => 'datetime',
            'initial_odometer' => 'decimal:2',
            'fuel_level' => 'integer',
            'has_spare_tire' => 'boolean',
            'confirmed' => 'boolean',
            'entry_date' => 'date',
            'exit_date' => 'date',
            'checklist' => 'array',
        ];
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
