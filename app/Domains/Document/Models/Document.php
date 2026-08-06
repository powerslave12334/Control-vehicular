<?php

namespace App\Domains\Document\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domains\User\Models\User;
use App\Shared\Traits\HasAuditColumns;

class Document extends Model
{
    use SoftDeletes, HasAuditColumns;

    protected $table = 'documents';

    protected $fillable = [
        'documentable_type', 'documentable_id', 'type', 'name',
        'file_path', 'issuance_date', 'expiry_date', 'status',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'issuance_date' => 'date',
            'expiry_date' => 'date',
        ];
    }

    public function documentable()
    {
        return $this->morphTo();
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
