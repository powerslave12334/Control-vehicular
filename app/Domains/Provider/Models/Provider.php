<?php

namespace App\Domains\Provider\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\User\Models\User;
use App\Shared\Traits\HasAuditColumns;

class Provider extends Model
{
    use SoftDeletes, HasAuditColumns;

    protected $table = 'providers';

    protected $fillable = [
        'name', 'type', 'contact', 'phone', 'email',
        'address', 'rfc', 'status',
        'created_by', 'updated_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
