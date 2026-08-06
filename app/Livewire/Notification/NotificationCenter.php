<?php

namespace App\Livewire\Notification;

use App\Domains\Notification\Models\NotificationPreference;
use App\Domains\Notification\Services\NotificationService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts::app')]
class NotificationCenter extends Component
{
    use WithPagination;

    public string $filter = 'all';
    public bool $showPreferences = false;
    public array $preferences = [];

    protected NotificationService $notificationService;

    public function boot(NotificationService $notificationService): void
    {
        $this->notificationService = $notificationService;
    }

    public function markAsRead(int $id): void
    {
        $this->notificationService->markAsRead($id);
        $this->dispatch('notification-read');
    }

    public function markAsUnread(int $id): void
    {
        $this->notificationService->markAsUnread($id);
        $this->dispatch('notification-read');
    }

    public function markAllAsRead(): void
    {
        $this->notificationService->markAllAsRead(auth()->id());
        $this->dispatch('notification-read');
    }

    public function archive(int $id): void
    {
        $this->notificationService->archive($id);
        $this->dispatch('notification-read');
    }

    public function unarchive(int $id): void
    {
        $this->notificationService->unarchive($id);
        $this->dispatch('notification-read');
    }

    public function delete(int $id): void
    {
        $this->notificationService->delete($id);
        $this->dispatch('notification-read');
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function togglePreferences(): void
    {
        $this->showPreferences = !$this->showPreferences;

        if ($this->showPreferences) {
            $this->loadPreferences();
        }
    }

    public function loadPreferences(): void
    {
        $prefs = $this->notificationService->getPreferences(auth()->id());
        $this->preferences = $prefs->toArray();
    }

    public function savePreferences(): void
    {
        foreach ($this->preferences as $key => $pref) {
            $this->notificationService->updatePreference(auth()->id(), $pref['event_type'], [
                'email' => $pref['email'] ?? false,
                'in_app' => $pref['in_app'] ?? true,
            ]);
        }

        $this->dispatch('swal:success', title: 'Preferencias guardadas', message: 'Tus preferencias de notificación se actualizaron.');
        $this->showPreferences = false;
    }

    public function enableEventType(string $eventType): void
    {
        $this->notificationService->updatePreference(auth()->id(), $eventType, [
            'in_app' => true,
            'email' => false,
        ]);
        $this->loadPreferences();
        $this->dispatch('swal:success', title: 'Activado', message: 'Notificaciones activadas para este evento.');
    }

    public function disableEventType(string $eventType): void
    {
        $this->notificationService->updatePreference(auth()->id(), $eventType, [
            'in_app' => false,
            'email' => false,
        ]);
        $this->loadPreferences();
        $this->dispatch('swal:success', title: 'Desactivado', message: 'Notificaciones desactivadas para este evento.');
    }

    #[On('notification-read')]
    public function render()
    {
        $notifications = $this->notificationService->getAllPaginated(auth()->id(), $this->filter);
        $unreadCount = $this->notificationService->getUnreadCount(auth()->id());

        return view('livewire.notification-center', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
}
