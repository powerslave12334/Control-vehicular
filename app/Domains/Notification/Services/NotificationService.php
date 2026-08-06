<?php

namespace App\Domains\Notification\Services;

use App\Domains\Notification\Models\Notification;
use App\Domains\Notification\Models\NotificationPreference;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationService
{
    public function getUnreadByUser(int $userId, int $limit = 10)
    {
        return Notification::forUser($userId)->unread()->latest()->take($limit)->get();
    }

    public function getUnreadCount(int $userId): int
    {
        return Notification::forUser($userId)->unread()->count();
    }

    public function getAllPaginated(int $userId, string $filter = 'all', int $perPage = 20): LengthAwarePaginator
    {
        $query = Notification::forUser($userId);

        switch ($filter) {
            case 'unread':
                $query->unread();
                break;
            case 'archived':
                $query->archived();
                break;
        }

        return $query->latest()->paginate($perPage);
    }

    public function markAsRead(int $id): void
    {
        Notification::findOrFail($id)->markAsRead();
    }

    public function markAsUnread(int $id): void
    {
        Notification::findOrFail($id)->markAsUnread();
    }

    public function markAllAsRead(int $userId): void
    {
        Notification::forUser($userId)->unread()->update(['read' => true, 'read_at' => now()]);
    }

    public function archive(int $id): void
    {
        Notification::findOrFail($id)->archive();
    }

    public function unarchive(int $id): void
    {
        Notification::findOrFail($id)->unarchive();
    }

    public function create(array $data): Notification
    {
        return Notification::create($data);
    }

    public function delete(int $id): void
    {
        Notification::findOrFail($id)->delete();
    }

    public function getPreferences(int $userId)
    {
        return NotificationPreference::where('user_id', $userId)->get()->keyBy('event_type');
    }

    public function updatePreference(int $userId, string $eventType, array $data): NotificationPreference
    {
        return NotificationPreference::updateOrCreate(
            ['user_id' => $userId, 'event_type' => $eventType],
            $data,
        );
    }

    public function shouldNotify(int $userId, string $eventType, string $channel = 'in_app'): bool
    {
        $pref = NotificationPreference::where('user_id', $userId)
            ->where('event_type', $eventType)
            ->first();

        if (!$pref) {
            return $channel === 'in_app';
        }

        return $pref->$channel ?? ($channel === 'in_app');
    }
}
