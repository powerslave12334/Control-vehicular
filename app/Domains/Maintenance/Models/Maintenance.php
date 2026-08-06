<?php

namespace App\Domains\Maintenance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Maintenance\Enums\MaintenanceTypeEnum;
use App\Domains\User\Models\User;
use App\Shared\Traits\HasAuditColumns;

class Maintenance extends Model
{
    use SoftDeletes, HasAuditColumns;

    protected $table = 'maintenances';

    protected $fillable = [
        'date', 'vehicle_id', 'type', 'category', 'description', 'cost',
        'workshop', 'workshop_id', 'evidence', 'odometer',
        'scheduled_date', 'start_date', 'end_date', 'status',
        'requested_by', 'approved_by', 'rejection_reason',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'scheduled_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
            'cost' => 'decimal:2',
            'type' => MaintenanceTypeEnum::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Maintenance $maintenance) {
            if ($maintenance->workshop && !$maintenance->workshop_id) {
                $maintenance->workshop_id = Workshop::firstOrCreate(['name' => $maintenance->workshop])->id;
            }
        });

        static::updating(function (Maintenance $maintenance) {
            if ($maintenance->isDirty('workshop') && !$maintenance->isDirty('workshop_id')) {
                $maintenance->workshop_id = Workshop::firstOrCreate(['name' => $maintenance->workshop])->id;
            }
        });
    }

    public function maintenanceWorkshop()
    {
        return $this->belongsTo(Workshop::class, 'workshop_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
