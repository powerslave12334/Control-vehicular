<?php

namespace App\Livewire\Driver;

use App\Domains\Route\Models\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::driver')]
class DriverDashboard extends Component
{
    public array $todayRoutes = [];

    public function mount()
    {
        $userId = Auth::id();
        $today = now()->toDateString();

        $this->todayRoutes = Route::where('driver_id', $userId)
            ->where('date', $today)
            ->with('vehicle')
            ->orderBy('client_name')
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.driver-dashboard');
    }
}
