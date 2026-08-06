<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\Dashboard\DashboardController;
use App\Http\Controllers\Api\Vehicle\VehicleController;
use App\Http\Controllers\Api\Operator\OperatorController;
use App\Http\Controllers\Api\Route\RouteController;
use App\Http\Controllers\Api\Fuel\RefuelController;
use App\Http\Controllers\Api\Maintenance\MaintenanceController;
use App\Http\Controllers\Api\Incident\IncidentController;
use App\Http\Controllers\Api\WorkOrder\WorkOrderController;
use App\Http\Controllers\Api\Part\PartController;
use App\Http\Controllers\Api\Document\DocumentController;
use App\Http\Controllers\Api\Insurance\InsuranceController;
use App\Http\Controllers\Api\Notification\NotificationController;
use App\Http\Controllers\Api\Catalog\CatalogItemController;
use App\Http\Controllers\Api\Gate\GateController;

// Public auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    // Profile
    Route::get('/user', [ProfileController::class, 'show']);
    Route::put('/user', [ProfileController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('permission:dashboard.read');

    // Vehicles
    Route::middleware('permission:vehicle.read')->group(function () {
        Route::get('/vehicles', [VehicleController::class, 'index']);
        Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->name('api.vehicles.show');
    });
    Route::post('/vehicles', [VehicleController::class, 'store'])->middleware('permission:vehicle.create');
    Route::match(['put', 'patch'], '/vehicles/{vehicle}', [VehicleController::class, 'update'])->middleware('permission:vehicle.update');
    Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->middleware('permission:vehicle.delete');

    // Operators
    Route::middleware('permission:operator.read')->group(function () {
        Route::get('/operators', [OperatorController::class, 'index']);
        Route::get('/operators/{operator}', [OperatorController::class, 'show']);
    });
    Route::post('/operators', [OperatorController::class, 'store'])->middleware('permission:operator.create');
    Route::match(['put', 'patch'], '/operators/{operator}', [OperatorController::class, 'update'])->middleware('permission:operator.update');
    Route::delete('/operators/{operator}', [OperatorController::class, 'destroy'])->middleware('permission:operator.delete');

    // Routes
    Route::middleware('permission:route.read')->group(function () {
        Route::get('/routes', [RouteController::class, 'index']);
        Route::get('/routes/{route}', [RouteController::class, 'show'])->name('api.routes.show');
    });
    Route::post('/routes', [RouteController::class, 'store'])->middleware('permission:route.create');
    Route::match(['put', 'patch'], '/routes/{route}', [RouteController::class, 'update'])->middleware('permission:route.update');
    Route::delete('/routes/{route}', [RouteController::class, 'destroy'])->middleware('permission:route.delete');

    // Fuel / Refuels
    Route::middleware('permission:refuel.read')->group(function () {
        Route::get('/refuels', [RefuelController::class, 'index']);
        Route::get('/refuels/{refuel}', [RefuelController::class, 'show']);
    });
    Route::post('/refuels', [RefuelController::class, 'store'])->middleware('permission:refuel.create');
    Route::match(['put', 'patch'], '/refuels/{refuel}', [RefuelController::class, 'update'])->middleware('permission:refuel.update');
    Route::delete('/refuels/{refuel}', [RefuelController::class, 'destroy'])->middleware('permission:refuel.delete');

    // Maintenance
    Route::middleware('permission:maintenance.read')->group(function () {
        Route::get('/maintenance', [MaintenanceController::class, 'index']);
        Route::get('/maintenance/{maintenance}', [MaintenanceController::class, 'show']);
    });
    Route::post('/maintenance', [MaintenanceController::class, 'store'])->middleware('permission:maintenance.create');
    Route::match(['put', 'patch'], '/maintenance/{maintenance}', [MaintenanceController::class, 'update'])->middleware('permission:maintenance.update');
    Route::delete('/maintenance/{maintenance}', [MaintenanceController::class, 'destroy'])->middleware('permission:maintenance.delete');

    // Incidents
    Route::middleware('permission:incident.read')->group(function () {
        Route::get('/incidents', [IncidentController::class, 'index']);
        Route::get('/incidents/{incident}', [IncidentController::class, 'show']);
    });
    Route::post('/incidents', [IncidentController::class, 'store'])->middleware('permission:incident.create');
    Route::match(['put', 'patch'], '/incidents/{incident}', [IncidentController::class, 'update'])->middleware('permission:incident.update');
    Route::delete('/incidents/{incident}', [IncidentController::class, 'destroy'])->middleware('permission:incident.delete');

    // Work Orders
    Route::middleware('permission:work_order.read')->group(function () {
        Route::get('/work-orders', [WorkOrderController::class, 'index']);
        Route::get('/work-orders/{workOrder}', [WorkOrderController::class, 'show']);
    });
    Route::post('/work-orders', [WorkOrderController::class, 'store'])->middleware('permission:work_order.create');
    Route::match(['put', 'patch'], '/work-orders/{workOrder}', [WorkOrderController::class, 'update'])->middleware('permission:work_order.update');
    Route::delete('/work-orders/{workOrder}', [WorkOrderController::class, 'destroy'])->middleware('permission:work_order.delete');

    // Parts
    Route::middleware('permission:part.read')->group(function () {
        Route::get('/parts', [PartController::class, 'index']);
        Route::get('/parts/{part}', [PartController::class, 'show']);
    });
    Route::post('/parts', [PartController::class, 'store'])->middleware('permission:part.create');
    Route::match(['put', 'patch'], '/parts/{part}', [PartController::class, 'update'])->middleware('permission:part.update');
    Route::delete('/parts/{part}', [PartController::class, 'destroy'])->middleware('permission:part.delete');

    // Documents
    Route::middleware('permission:document.read')->group(function () {
        Route::get('/documents', [DocumentController::class, 'index']);
        Route::get('/documents/{document}', [DocumentController::class, 'show']);
    });
    Route::post('/documents', [DocumentController::class, 'store'])->middleware('permission:document.create');
    Route::match(['put', 'patch'], '/documents/{document}', [DocumentController::class, 'update'])->middleware('permission:document.update');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->middleware('permission:document.delete');

    // Insurance
    Route::middleware('permission:insurance.read')->group(function () {
        Route::get('/insurance', [InsuranceController::class, 'index']);
        Route::get('/insurance/{insurance}', [InsuranceController::class, 'show']);
    });
    Route::post('/insurance', [InsuranceController::class, 'store'])->middleware('permission:insurance.create');
    Route::match(['put', 'patch'], '/insurance/{insurance}', [InsuranceController::class, 'update'])->middleware('permission:insurance.update');
    Route::delete('/insurance/{insurance}', [InsuranceController::class, 'destroy'])->middleware('permission:insurance.delete');

    // Notifications
    Route::middleware('permission:notification.read')->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    });

    // Catalog
    Route::middleware('permission:catalog.read')->group(function () {
        Route::get('/catalog', [CatalogItemController::class, 'index']);
        Route::get('/catalog/{catalogItem}', [CatalogItemController::class, 'show']);
    });
    Route::post('/catalog', [CatalogItemController::class, 'store'])->middleware('permission:catalog.create');
    Route::match(['put', 'patch'], '/catalog/{catalogItem}', [CatalogItemController::class, 'update'])->middleware('permission:catalog.update');
    Route::delete('/catalog/{catalogItem}', [CatalogItemController::class, 'destroy'])->middleware('permission:catalog.delete');

    // Gate logs
    Route::middleware('permission:gate_log.read')->group(function () {
        Route::get('/gate-logs', [GateController::class, 'index']);
        Route::get('/gate-logs/{gateLog}', [GateController::class, 'show']);
    });
    Route::post('/gate-logs', [GateController::class, 'store'])->middleware('permission:gate_log.create');
    Route::match(['put', 'patch'], '/gate-logs/{gateLog}', [GateController::class, 'update'])->middleware('permission:gate_log.update');
    Route::delete('/gate-logs/{gateLog}', [GateController::class, 'destroy'])->middleware('permission:gate_log.delete');
});
