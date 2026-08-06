<?php

namespace App\Livewire\Driver;

use App\Domains\Fuel\Services\FuelService;
use App\Domains\Fuel\DTO\RefuelData;
use App\Domains\Route\Services\RouteService;
use App\Domains\Catalog\Models\Catalog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts::driver')]
class DriverRefuel extends Component
{
    use WithFileUploads;

    public Route $route;

    #[Rule('required|regex:/^\d+(\.\d{1,3})?$/u|min:1')]
    public string $liters = '';
    #[Rule('required|regex:/^\d+(\.\d{1,2})?$/u|min:1')]
    public string $amount = '';
    #[Rule('required')]
    public string $payment_method = 'Tarjeta';
    #[Rule('nullable|integer|min:0')]
    public string $odometer = '';
    #[Rule('nullable|image|max:5120')]
    public $ticket_photo;

    public function mount(string $routeId, RouteService $routeService)
    {
        $userId = Auth::id();
        $this->route = $routeService->getById((int) $routeId);
        abort_if($this->route->driver_id !== $userId, 403);
    }

    public function save(FuelService $service)
    {
        $this->validate();

        $dto = RefuelData::fromArray([
            'date' => now()->toDateString(),
            'route_id' => $this->route->id,
            'vehicle_id' => $this->route->vehicle_id,
            'driver_id' => $this->route->driver_id,
            'driver_name' => $this->route->driver_name ?? Auth::user()->name,
            'liters' => (float) $this->liters,
            'amount' => (float) $this->amount,
            'price_per_liter' => $this->liters > 0 ? round((float) $this->amount / (float) $this->liters, 2) : 0,
            'payment_method' => $this->payment_method,
            'odometer' => $this->odometer ? (int) $this->odometer : null,
        ]);

        if ($this->ticket_photo) {
            $data = $dto->toArray();
            $data['ticket_photo'] = $this->ticket_photo->store('driver/tickets', 'public');
            $dto = RefuelData::fromArray($data);
        }

        $service->create($dto);

        $this->dispatch('swal:success',
            title: 'Carga registrada',
            message: number_format((float) $this->liters, 2) . 'L registrados correctamente.',
        );

        return $this->redirect(route('driver.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.driver-refuel', [
            'paymentMethods' => Catalog::byGroup('MetodoPago')->get(),
        ]);
    }
}
