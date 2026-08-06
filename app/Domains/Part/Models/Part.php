<?php

namespace App\Domains\Part\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\User\Models\User;
use App\Shared\Traits\HasAuditColumns;

class Part extends Model
{
    use SoftDeletes, HasAuditColumns;

    protected $table = 'parts';

    protected $fillable = [
        'name', 'part_category_id', 'part_brand_id', 'sku',
        'description', 'unit_price', 'current_stock', 'min_stock',
        'max_stock', 'unit_type', 'location', 'status',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'current_stock' => 'integer',
            'min_stock' => 'integer',
            'max_stock' => 'integer',
        ];
    }

    public function category()
    {
        return $this->belongsTo(PartCategory::class, 'part_category_id');
    }

    public function brand()
    {
        return $this->belongsTo(PartBrand::class, 'part_brand_id');
    }

    public function movements()
    {
        return $this->hasMany(PartInventory::class, 'part_id');
    }

    public function suppliers()
    {
        return $this->hasMany(PartSupplier::class, 'part_id');
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
