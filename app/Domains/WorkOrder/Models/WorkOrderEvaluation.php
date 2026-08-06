<?php

namespace App\Domains\WorkOrder\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\User\Models\User;

class WorkOrderEvaluation extends Model
{
    protected $table = 'work_order_evaluations';

    protected $fillable = [
        'work_order_id', 'quality_score', 'timeliness_score',
        'cost_score', 'comments', 'evaluated_by',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
}
