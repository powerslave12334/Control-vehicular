<?php

namespace App\Domains\Vehicle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tire extends Model
{
    use SoftDeletes;

    protected $table = 'tires';

    protected $fillable = [
        'vehicle_id', 'brand', 'model', 'size', 'serial_number',
        'position', 'status', 'installation_date', 'installation_odometer',
    ];

    protected function casts(): array
    {
        return [
            'installation_date' => 'date',
            'installation_odometer' => 'decimal:2',
        ];
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
