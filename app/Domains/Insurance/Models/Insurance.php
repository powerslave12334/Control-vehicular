<?php

namespace App\Domains\Insurance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\User\Models\User;
use App\Shared\Traits\HasAuditColumns;

class Insurance extends Model
{
    use SoftDeletes, HasAuditColumns;

    protected $table = 'insurances';

    protected $fillable = [
        'vehicle_id', 'policy_number', 'insurer', 'coverage_type',
        'start_date', 'end_date', 'premium', 'deductible', 'status',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'premium' => 'decimal:2',
            'deductible' => 'decimal:2',
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

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
