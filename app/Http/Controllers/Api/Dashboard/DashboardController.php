<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Dashboard;

use App\Domains\Incident\Models\Incident;
use App\Domains\Maintenance\Models\Maintenance;
use App\Domains\Notification\Models\Notification;
use App\Domains\Operator\Models\Operator;
use App\Domains\Route\Models\Route;
use App\Domains\Vehicle\Models\Vehicle;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $totalVehicles = Vehicle::count();
        $activeVehicles = Vehicle::where('status', 'active')->count();

        $totalOperators = Operator::count();
        $activeOperators = Operator::where('status', 'active')->count();

        $pendingMaintenance = Maintenance::whereIn('status', ['pendiente', 'programado'])->count();

        $openIncidents = Incident::where('status', 'open')->count();

        $activeRoutes = Route::where('status', 'in_progress')->count();

        $recentNotifications = Notification::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'data' => [
                'total_vehicles' => $totalVehicles,
                'active_vehicles' => $activeVehicles,
                'total_operators' => $totalOperators,
                'active_operators' => $activeOperators,
                'pending_maintenance' => $pendingMaintenance,
                'open_incidents' => $openIncidents,
                'active_routes' => $activeRoutes,
                'recent_notifications' => $recentNotifications,
            ],
        ]);
    }
}
