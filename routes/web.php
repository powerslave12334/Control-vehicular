<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\Vehicle\VehicleManager;
use App\Livewire\Fuel\FuelManager;
use App\Livewire\Maintenance\MaintenanceManager;
use App\Livewire\Operator\OperatorManager;
use App\Livewire\Route\RouteManager;
use App\Livewire\Incident\IncidentManager;
use App\Livewire\Catalog\CatalogManager;
use App\Livewire\Evidence\EvidenceGallery;
use App\Livewire\Gate\GateLogBook;
use App\Livewire\Gate\GateManager;
use App\Livewire\User\UserManager;
use App\Livewire\Security\SecurityPanel;
use App\Livewire\Reports\Reports;
use App\Livewire\Geolocation\Geolocation;
use App\Livewire\Calendar\Calendar;
use App\Livewire\Notification\NotificationCenter;
use App\Livewire\Driver\DriverDashboard;
use App\Livewire\Driver\DriverRoute;
use App\Livewire\Driver\DriverIncident;
use App\Livewire\Expense\ExpenseManager;
use App\Livewire\Driver\DriverRefuel;

Route::get('/', Login::class)->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/vehicles', VehicleManager::class)->name('vehicles');
    Route::get('/maintenance', MaintenanceManager::class)->name('maintenance');
    Route::get('/fuel', FuelManager::class)->name('fuel');
    Route::get('/routes', RouteManager::class)->name('routes');
    Route::get('/geolocation', Geolocation::class)->name('geolocation');
    Route::get('/calendar', Calendar::class)->name('calendar');
    Route::get('/operators', OperatorManager::class)->name('operators');
    Route::get('/incidents', IncidentManager::class)->name('incidents');
    Route::get('/expenses', ExpenseManager::class)->name('expenses');
    Route::get('/evidence', EvidenceGallery::class)->name('evidence');
    Route::get('/gate', GateManager::class)->name('gate.dashboard');
    Route::get('/gate/logs', GateLogBook::class)->name('gate.logs');
    Route::get('/reports', Reports::class)->name('reports');
    Route::get('/users', UserManager::class)->name('users');
    Route::get('/security', SecurityPanel::class)->name('security');
    Route::get('/catalogs', CatalogManager::class)->name('catalogs');
    Route::get('/notifications', NotificationCenter::class)->name('notifications');

    Route::prefix('driver')->group(function () {
        Route::get('/dashboard', DriverDashboard::class)->name('driver.dashboard');
        Route::get('/route/{routeId}', DriverRoute::class)->name('driver.route');
        Route::get('/incident/{routeId}', DriverIncident::class)->name('driver.incident');
        Route::get('/refuel/{routeId}', DriverRefuel::class)->name('driver.refuel');
    });

    Route::post('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
