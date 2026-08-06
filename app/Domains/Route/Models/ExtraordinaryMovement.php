<?php

namespace App\Domains\Route\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtraordinaryMovement extends Model
{
    use SoftDeletes;
    protected $table = 'extraordinary_movements';
 
    protected $fillable = [
        'route_id', 'type', 'description', 'photo', 'observations',
        'timestamp', 'latitude', 'longitude',
    ];
 
    protected function casts(): array
    {
        return [
            'timestamp' => 'datetime',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }
 
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
