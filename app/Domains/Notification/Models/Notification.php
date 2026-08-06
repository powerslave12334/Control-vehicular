<?php

namespace App\Domains\Notification\Models;

use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id', 'type', 'priority', 'title', 'message', 'read',
        'read_at', 'archived', 'archived_at',
        'related_id', 'related_type',
    ];

    protected function casts(): array
    {
        return [
            'read' => 'boolean',
            'read_at' => 'datetime',
            'archived' => 'boolean',
            'archived_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('read', false)->where('archived', false);
    }

    public function scopeArchived($query)
    {
        return $query->where('archived', true);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function markAsRead(): void
    {
        $this->update(['read' => true, 'read_at' => now()]);
    }

    public function markAsUnread(): void
    {
        $this->update(['read' => false, 'read_at' => null]);
    }

    public function archive(): void
    {
        $this->update(['archived' => true, 'archived_at' => now()]);
    }

    public function unarchive(): void
    {
        $this->update(['archived' => false, 'archived_at' => null]);
    }
}
