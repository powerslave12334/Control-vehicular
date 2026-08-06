<?php

namespace App\Domains\Part\Models;

use Illuminate\Database\Eloquent\Model;

class PartInventory extends Model
{
    protected $table = 'part_inventory';

    protected $fillable = [
        'part_id', 'quantity', 'movement_type',
        'reference_type', 'reference_id', 'unit_cost',
        'notes', 'user_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_cost' => 'decimal:2',
        ];
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
