<?php

namespace App\Livewire\Notification;

use App\Domains\Notification\Services\NotificationService;
use Livewire\Component;

class NotificationBell extends Component
{
    public int $unreadCount = 0;
    public string $filter = 'all';

    protected $listeners = ['notification-read' => 'refresh'];

    public function boot(NotificationService $notificationService): void
    {
        $this->refresh($notificationService);
    }

    public function refresh(?NotificationService $notificationService = null): void
    {
        $service = $notificationService ?? app(NotificationService::class);
        $this->unreadCount = $service->getUnreadCount(auth()->id());
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    public function markAsRead(int $id): void
    {
        app(NotificationService::class)->markAsRead($id);
        $this->dispatch('notification-read');
    }

    public function markAllAsRead(): void
    {
        app(NotificationService::class)->markAllAsRead(auth()->id());
        $this->dispatch('notification-read');
    }

    public function render(NotificationService $notificationService)
    {
        $notifications = $notificationService->getAllPaginated(auth()->id(), $this->filter, 8);

        return view('livewire.notification-bell', [
            'notifications' => $notifications,
        ]);
    }
}