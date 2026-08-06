<?php

namespace App\Livewire\Operator;

use App\Domains\Catalog\Models\Catalog;
use App\Domains\User\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts::app')]
class OperatorManager extends Component
{
    use WithPagination;

    public string $filterSearch = '';

    public string $filterRole = '';

    public string $filterStatus = '';

    public function updatedFilterSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterRole()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->filterSearch = '';
        $this->filterRole = '';
        $this->filterStatus = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = User::whereIn('role', ['Operador', 'Chofer', 'Instalador']);

        if (trim($this->filterSearch) !== '') {
            $search = trim($this->filterSearch);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($this->filterRole !== '') {
            $query->where('role', $this->filterRole);
        }

        if ($this->filterStatus !== '') {
            $query->where('status', $this->filterStatus);
        }

        $operators = $query->orderBy('name')->paginate(15);

        return view('livewire.operator-manager', [
            'operators' => $operators,
            'operatorRoles' => ['Operador', 'Chofer', 'Instalador'],
            'userStatuses' => Catalog::byGroup('EstadoUsuario')->get(),
        ]);
    }
}
