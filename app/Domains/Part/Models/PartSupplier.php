<?php

namespace App\Domains\Part\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Provider\Models\Provider;

class PartSupplier extends Model
{
    protected $table = 'part_suppliers';

    protected $fillable = [
        'part_id', 'provider_id', 'price',
        'lead_time_days', 'is_preferred',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'lead_time_days' => 'integer',
            'is_preferred' => 'boolean',
        ];
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
