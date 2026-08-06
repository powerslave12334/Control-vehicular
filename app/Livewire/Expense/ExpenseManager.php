<?php

namespace App\Livewire\Expense;

use App\Domains\Expense\DTO\ExpenseData;
use App\Domains\Expense\Models\Expense;
use App\Domains\Expense\Services\ExpenseService;
use App\Domains\Route\Models\Route as RouteModel;
use App\Domains\User\Models\User;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Vehicle\Services\VehicleService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts::app')]
class ExpenseManager extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public bool $showDetail = false;

    public bool $showCardForm = false;

    public bool $showCardEdit = false;

    public ?int $editId = null;

    public $detailRecord = null;

    public string $filterSearch = '';

    public string $filterStatus = '';

    public string $filterVehicleId = '';

    public string $filterDateFrom = '';

    public string $filterDateTo = '';

    public string $cardFilterSearch = '';

    public string $cardScope = 'cards';

    public int $cardPage = 1;

    protected const CARD_PER_PAGE = 10;

    #[Rule('required|exists:vehicles,id')]
    public string $vehicle_id = '';

    #[Rule('nullable|integer|exists:users,id')]
    public string $operator_id = '';

    #[Rule('required|regex:/^[\pL\s0-9]+$/u')]
    public string $type = '';

    #[Rule('required|regex:/^[\pL\s0-9\.\,\-\/\#\(\)]+$/u')]
    public string $description = '';

    #[Rule('required|regex:/^\d+(\.\d{1,2})?$/|min:0.01')]
    public string $amount = '';

    #[Rule('required|date')]
    public string $date = '';

    #[Rule('nullable|regex:/^[A-Za-z0-9\-\.\/]+$/u')]
    public string $folio = '';

    #[Rule('nullable|regex:/^[\pL\s0-9\.\,\-\/\(\)]+$/u')]
    public string $provider_name = '';

    #[Rule('required|string|in:pendiente,aprobado,rechazado')]
    public string $status = 'aprobado';

    public ?string $rejectionReason = null;

    public ?int $selectedVehicleId = null;

    public $selectedVehicleCard = null;

    public string $card_vehicle_id = '';

    public string $card_number = '';

    public string $card_authorized_amount = '0';

    public ?int $editCardVehicleId = null;

    public string $editCardNumber = '';

    public string $editCardAuthorizedAmount = '0';

    public function mount()
    {
        $this->date = now()->toDateString();
    }

    public function updatedFilterSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterVehicleId()
    {
        $this->resetPage();
    }

    public function updatedFilterDateFrom()
    {
        $this->resetPage();
    }

    public function updatedFilterDateTo()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->filterSearch = '';
        $this->filterStatus = '';
        $this->filterVehicleId = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
        $this->resetPage();
    }

    public function updatedCardFilterSearch()
    {
        $this->cardPage = 1;
    }

    public function updatedCardScope()
    {
        $this->cardPage = 1;
    }

    public function previousCardPage()
    {
        $this->cardPage = max(1, $this->cardPage - 1);
    }

    public function nextCardPage()
    {
        $this->cardPage++;
    }

    public function resetForm()
    {
        $this->showForm = false;
        $this->editId = null;
        $this->vehicle_id = '';
        $this->operator_id = '';
        $this->type = '';
        $this->description = '';
        $this->amount = '';
        $this->date = now()->toDateString();
        $this->folio = '';
        $this->provider_name = '';
        $this->status = 'aprobado';
        $this->rejectionReason = null;
        $this->selectedVehicleId = null;
        $this->selectedVehicleCard = null;
    }

    public function resetCardForm()
    {
        $this->showCardForm = false;
        $this->card_vehicle_id = '';
        $this->card_number = '';
        $this->card_authorized_amount = '0';
    }

    public function resetCardEdit()
    {
        $this->showCardEdit = false;
        $this->editCardVehicleId = null;
        $this->editCardNumber = '';
        $this->editCardAuthorizedAmount = '0';
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function createCard()
    {
        $this->resetCardForm();
        $this->showCardForm = true;
    }

    public function saveCard(VehicleService $service)
    {
        $this->validate([
            'card_vehicle_id' => 'required|exists:vehicles,id',
            'card_number' => 'required|regex:/^[\d\-]+$/u|max:50',
            'card_authorized_amount' => 'required|regex:/^\d+(\.\d{1,2})?$/|min:0',
        ]);

        $vehicle = $service->getById($this->card_vehicle_id);
        $vehicle->expense_card_number = $this->card_number;
        $vehicle->authorized_expense = (float) $this->card_authorized_amount;
        $vehicle->save();

        $this->dispatch('swal:success', title: 'Tarjeta registrada', message: 'Tarjeta asignada al vehículo correctamente.');
        $this->resetCardForm();
    }

    public function editCard(int $id, VehicleService $service)
    {
        $v = $service->getById($id);
        $this->editCardVehicleId = $v->id;
        $this->editCardNumber = $v->expense_card_number ?? '';
        $this->editCardAuthorizedAmount = (string) ($v->authorized_expense ?? '0');
        $this->showCardEdit = true;
    }

    public function updateCard(VehicleService $service)
    {
        $this->validate([
            'editCardNumber' => 'required|regex:/^[\d\-]+$/u|max:50',
            'editCardAuthorizedAmount' => 'required|regex:/^\d+(\.\d{1,2})?$/|min:0',
        ]);

        $vehicle = $service->getById($this->editCardVehicleId);
        $vehicle->expense_card_number = $this->editCardNumber;
        $vehicle->authorized_expense = (float) $this->editCardAuthorizedAmount;
        $vehicle->save();

        $this->dispatch('swal:success', title: 'Tarjeta actualizada', message: 'Tarjeta actualizada correctamente.');
        $this->resetCardEdit();
    }

    public function confirmDeleteCard(int $id)
    {
        $this->dispatch('swal:confirm',
            title: 'Eliminar tarjeta',
            text: '¿Estás seguro de eliminar esta tarjeta? Los gastos asociados se conservarán.',
            callback: 'deleteCard',
            params: ['id' => $id],
        );
    }

    #[On('deleteCard')]
    public function deleteCard(int $id, VehicleService $service)
    {
        $vehicle = $service->getById($id);
        $vehicle->expense_card_number = null;
        $vehicle->authorized_expense = 0;
        $vehicle->save();
        $this->dispatch('swal:success', title: 'Tarjeta eliminada', message: 'Tarjeta desvinculada del vehículo.');
    }

    public function updatedVehicleId($value)
    {
        $this->selectedVehicleId = $value ? (int) $value : null;

        if ($this->selectedVehicleId) {
            $vehicle = Vehicle::find($this->selectedVehicleId);
            if ($vehicle && $vehicle->expense_card_number) {
                $spent = Expense::where('vehicle_id', $vehicle->id)
                    ->where('status', 'aprobado')
                    ->whereYear('date', now()->year)
                    ->whereMonth('date', now()->month)
                    ->sum('amount');

                $this->selectedVehicleCard = (object) [
                    'card_number' => $vehicle->expense_card_number,
                    'authorized' => (float) ($vehicle->authorized_expense ?? 0),
                    'spent' => (float) $spent,
                    'remaining' => max(0, (float) ($vehicle->authorized_expense ?? 0) - (float) $spent),
                    'percent' => $vehicle->authorized_expense > 0
                        ? round(($spent / $vehicle->authorized_expense) * 100, 1)
                        : 0,
                ];

                return;
            }
        }
        $this->selectedVehicleCard = null;
    }

    public function edit(int $id, ExpenseService $service)
    {
        $e = $service->getById($id);
        $this->editId = $e->id;
        $this->vehicle_id = (string) $e->vehicle_id;
        $this->operator_id = (string) ($e->operator_id ?? '');
        $this->type = $e->type;
        $this->description = $e->description;
        $this->amount = (string) $e->amount;
        $this->date = $e->date?->toDateString() ?? now()->toDateString();
        $this->folio = $e->folio ?? '';
        $this->provider_name = $e->provider_name ?? '';
        $this->status = $e->status;
        $this->showForm = true;
        $this->updatedVehicleId((string) $e->vehicle_id);
    }

    public function save(ExpenseService $service)
    {
        $this->validate();

        $route = RouteModel::where('vehicle_id', $this->vehicle_id)
            ->whereDate('date', $this->date)
            ->first();

        $data = [
            'vehicle_id' => $this->vehicle_id,
            'route_id' => $route?->id,
            'operator_id' => $this->operator_id !== '' ? (int) $this->operator_id : null,
            'type' => $this->type,
            'description' => $this->description,
            'amount' => $this->amount,
            'date' => $this->date,
            'folio' => $this->folio ?: null,
            'provider_name' => $this->provider_name ?: null,
            'status' => $this->status,
        ];

        if ($this->editId) {
            $expense = $service->getById($this->editId);
            $expense->update($data);
            $this->dispatch('swal:success', title: 'Actualizado', message: 'Gasto actualizado correctamente.');
        } else {
            $dto = ExpenseData::fromArray($data);
            $service->create($dto);
            $this->dispatch('swal:success', title: 'Registrado', message: 'Gasto registrado correctamente.');
        }

        $this->resetForm();
    }

    public function approve(int $id, ExpenseService $service)
    {
        try {
            $service->approve($id, auth()->id());
            $this->dispatch('swal:success', title: 'Aprobado', message: 'Gasto aprobado correctamente.');
        } catch (\DomainException $e) {
            $this->dispatch('swal:error', title: 'Error', message: $e->getMessage());
        }
    }

    public function confirmReject(int $id)
    {
        $this->dispatch('swal:prompt',
            title: 'Rechazar gasto',
            text: 'Indica el motivo del rechazo:',
            callback: 'rejectExpense',
            params: ['id' => $id],
        );
    }

    #[On('rejectExpense')]
    public function rejectExpense(int $id, ?string $reason, ExpenseService $service)
    {
        if (! $reason) {
            $this->dispatch('swal:error', title: 'Error', message: 'Debes indicar un motivo de rechazo.');

            return;
        }
        try {
            $service->reject($id, $reason);
            $this->dispatch('swal:success', title: 'Rechazado', message: 'Gasto rechazado correctamente.');
        } catch (\DomainException $e) {
            $this->dispatch('swal:error', title: 'Error', message: $e->getMessage());
        }
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm',
            title: 'Eliminar gasto',
            text: '¿Estás seguro de eliminar este gasto?',
            callback: 'deleteExpense',
            params: ['id' => $id],
        );
    }

    #[On('deleteExpense')]
    public function deleteExpense(int $id, ExpenseService $service)
    {
        try {
            $service->delete($id);
            $this->dispatch('swal:success', title: 'Eliminado', message: 'Gasto eliminado correctamente.');
        } catch (\DomainException $e) {
            $this->dispatch('swal:error', title: 'Error', message: $e->getMessage());
        }
    }

    public function showExpediente(int $id, ExpenseService $service)
    {
        $this->detailRecord = $service->getById($id);
        $this->showDetail = true;
    }

    public function resetDetail()
    {
        $this->showDetail = false;
        $this->detailRecord = null;
    }

    public function render(VehicleService $vehicleService, ExpenseService $service)
    {
        $allVehicles = $vehicleService->getAll();
        $vehiclesWithCards = $allVehicles->filter(fn ($v) => ! is_null($v->expense_card_number));

        $baseVehicles = $this->cardScope === 'all' ? $allVehicles : $vehiclesWithCards;

        if (trim($this->cardFilterSearch) !== '') {
            $search = mb_strtolower(trim($this->cardFilterSearch));
            $baseVehicles = $baseVehicles->filter(function ($v) use ($search) {
                $haystack = mb_strtolower(implode(' ', array_filter([
                    $v->plate,
                    $v->brand,
                    $v->model,
                    $v->expense_card_number,
                ])));

                return str_contains($haystack, $search);
            });
        }

        $cardStats = $baseVehicles->map(function ($vehicle) {
            $spent = Expense::where('vehicle_id', $vehicle->id)
                ->where('status', 'aprobado')
                ->whereYear('date', now()->year)
                ->whereMonth('date', now()->month)
                ->sum('amount');

            return (object) [
                'vehicle' => $vehicle,
                'card_number' => $vehicle->expense_card_number,
                'authorized' => (float) ($vehicle->authorized_expense ?? 0),
                'spent' => (float) $spent,
                'remaining' => max(0, (float) ($vehicle->authorized_expense ?? 0) - (float) $spent),
                'percent' => $vehicle->authorized_expense > 0
                    ? round(($spent / $vehicle->authorized_expense) * 100, 1)
                    : 0,
            ];
        })->values();

        $cardTotal = $cardStats->count();
        $cardLastPage = max(1, (int) ceil($cardTotal / self::CARD_PER_PAGE));
        $this->cardPage = min($this->cardPage, $cardLastPage);
        $cardFirstItem = $cardTotal > 0 ? ($this->cardPage - 1) * self::CARD_PER_PAGE + 1 : 0;
        $cardLastItem = min($this->cardPage * self::CARD_PER_PAGE, $cardTotal);

        $expenses = $service->getAllFiltered([
            'search' => trim($this->filterSearch),
            'status' => $this->filterStatus,
            'vehicle_id' => $this->filterVehicleId,
            'date_from' => $this->filterDateFrom,
            'date_to' => $this->filterDateTo,
        ], 15);

        return view('livewire.expense-manager', [
            'expenses' => $expenses,
            'vehiclesWithCards' => $vehiclesWithCards,
            'vehicles' => $allVehicles,
            'operators' => User::whereIn('role', ['Operador', 'Chofer', 'Instalador'])
                ->orderBy('name')
                ->get(['id', 'name']),
            'cardStats' => $cardStats->slice(($this->cardPage - 1) * self::CARD_PER_PAGE, self::CARD_PER_PAGE)->values(),
            'cardTotal' => $cardTotal,
            'cardFirstItem' => $cardFirstItem,
            'cardLastItem' => $cardLastItem,
            'cardPage' => $this->cardPage,
            'cardLastPage' => $cardLastPage,
            'expenseStatuses' => [
                'pendiente' => 'Pendiente',
                'aprobado' => 'Aprobado',
                'rechazado' => 'Rechazado',
            ],
        ]);
    }
}
