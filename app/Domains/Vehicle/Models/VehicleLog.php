<?php

namespace App\Domains\Vehicle\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\User\Models\User;

class VehicleLog extends Model
{
    protected $table = 'vehicle_logs';

    protected $fillable = [
        'vehicle_id', 'event_type', 'description', 'user_id', 'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'json',
        ];
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
