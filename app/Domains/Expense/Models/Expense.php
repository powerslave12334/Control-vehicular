<?php

namespace App\Domains\Expense\Models;

use App\Domains\Expense\Enums\ExpenseStatusEnum;
use App\Domains\Route\Models\Route;
use App\Domains\User\Models\User;
use App\Domains\Vehicle\Models\Vehicle;
use App\Shared\Traits\HasAuditColumns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasAuditColumns, SoftDeletes;

    protected $table = 'expenses';

    protected $fillable = [
        'vehicle_id', 'route_id', 'operator_id', 'type', 'description', 'amount',
        'date', 'folio', 'provider_name', 'status', 'evidence',
        'rejection_reason', 'approved_by',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'date' => 'date',
            'status' => ExpenseStatusEnum::class,
        ];
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
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
