<?php

namespace App\Domains\Operator\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class License extends Model
{
    use SoftDeletes;

    protected $table = 'licenses';

    protected $fillable = [
        'operator_id', 'license_number', 'category',
        'expedition_date', 'expiration_date', 'status',
    ];

    protected function casts(): array
    {
        return [
            'expedition_date' => 'date',
            'expiration_date' => 'date',
        ];
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
