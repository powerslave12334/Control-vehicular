<?php

namespace App\Domains\WorkOrder\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\User\Models\User;

class WorkOrderTimeline extends Model
{
    protected $table = 'work_order_timeline';

    protected $fillable = [
        'work_order_id', 'event_type', 'description',
        'user_id', 'metadata',
    ];

    protected function casts(): array
    {
        return ['metadata' => 'array'];
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
