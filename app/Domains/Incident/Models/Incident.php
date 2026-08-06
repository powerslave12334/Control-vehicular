<?php

namespace App\Domains\Incident\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Operator\Models\Operator;
use App\Domains\Incident\Enums\IncidentSeverityEnum;
use App\Domains\Incident\Enums\IncidentStatusEnum;
use App\Domains\User\Models\User;
use App\Shared\Traits\HasAuditColumns;

class Incident extends Model
{
    use SoftDeletes, HasAuditColumns;

    protected $table = 'incidents';

    protected $fillable = [
        'route_id', 'date', 'time', 'vehicle_id', 'driver_id', 'driver_name',
        'type', 'location', 'description', 'severity', 'status',
        'cost', 'involves_third_party', 'third_party_data', 'photo',
        'resolved_at', 'resolved_by',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'cost' => 'decimal:2',
            'involves_third_party' => 'boolean',
            'third_party_data' => 'array',
            'severity' => IncidentSeverityEnum::class,
            'status' => IncidentStatusEnum::class,
            'resolved_at' => 'datetime',
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

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Operator::class, 'driver_id');
    }
}
