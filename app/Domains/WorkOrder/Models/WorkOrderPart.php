<?php

namespace App\Domains\WorkOrder\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Part\Models\Part;

class WorkOrderPart extends Model
{
    protected $table = 'work_order_parts';

    protected $fillable = [
        'work_order_id', 'part_id', 'description', 'quantity',
        'unit_cost', 'total_cost', 'source', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
        ];
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
