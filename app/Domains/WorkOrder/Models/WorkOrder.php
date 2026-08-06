<?php

namespace App\Domains\WorkOrder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Operator\Models\Operator;
use App\Domains\Maintenance\Models\Maintenance;
use App\Domains\Provider\Models\Provider;
use App\Domains\User\Models\User;
use App\Shared\Traits\HasAuditColumns;

class WorkOrder extends Model
{
    use SoftDeletes, HasAuditColumns;

    protected $table = 'work_orders';

    protected $fillable = [
        'code', 'vehicle_id', 'operator_id', 'maintenance_id', 'provider_id',
        'description', 'diagnosis', 'priority', 'status',
        'estimated_cost', 'labor_cost', 'parts_cost', 'total_cost',
        'requested_by', 'approved_by', 'assigned_to',
        'started_at', 'completed_at', 'closed_at',
        'mileage_at_request', 'mileage_at_completion',
        'notes', 'rejection_reason',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'estimated_cost' => 'decimal:2',
            'labor_cost' => 'decimal:2',
            'parts_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'mileage_at_request' => 'decimal:2',
            'mileage_at_completion' => 'decimal:2',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function maintenance()
    {
        return $this->belongsTo(Maintenance::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function assignedProvider()
    {
        return $this->belongsTo(Provider::class, 'assigned_to');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function parts()
    {
        return $this->hasMany(WorkOrderPart::class);
    }

    public function timeline()
    {
        return $this->hasMany(WorkOrderTimeline::class);
    }

    public function evaluation()
    {
        return $this->hasOne(WorkOrderEvaluation::class);
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
