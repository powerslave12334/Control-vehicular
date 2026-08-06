<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Notification;

use App\Domains\Notification\Models\Notification;
use App\Http\Controllers\Controller;
use App\Http\Resources\Notification\NotificationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Notification::query();

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('read')) {
            $query->where('read', filter_var($request->read, FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        $unreadCount = Notification::where('read', false)->count();

        return response()->json([
            'data' => NotificationResource::collection($notifications),
            'meta' => [
                'total' => $notifications->total(),
                'per_page' => $notifications->perPage(),
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'unread_count' => $unreadCount,
            ],
        ]);
    }

    public function markAsRead(Request $request, Notification $notification): JsonResponse
    {
        $notification->markAsRead();

        return response()->json([
            'data' => new NotificationResource($notification->fresh()),
        ]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        Notification::where('read', false)->update([
            'read' => true,
            'read_at' => now(),
        ]);

        return response()->json([
            'data' => ['message' => 'Todas las notificaciones marcadas como leídas.'],
        ]);
    }
}
