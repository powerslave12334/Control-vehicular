<?php

namespace App\Domains\Inspection\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Operator\Models\Operator;
use App\Domains\User\Models\User;
use App\Shared\Traits\HasAuditColumns;

class Inspection extends Model
{
    use SoftDeletes, HasAuditColumns;

    protected $table = 'inspections';

    protected $fillable = [
        'vehicle_id', 'operator_id', 'type', 'performed_at',
        'mileage', 'result', 'notes', 'checklist_results',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'performed_at' => 'datetime',
            'mileage' => 'decimal:2',
            'checklist_results' => 'array',
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
