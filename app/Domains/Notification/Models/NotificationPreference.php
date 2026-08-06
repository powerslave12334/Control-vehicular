<?php

namespace App\Domains\Notification\Models;

use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    protected $fillable = [
        'user_id', 'event_type', 'email', 'in_app',
    ];

    protected function casts(): array
    {
        return [
            'email' => 'boolean',
            'in_app' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
