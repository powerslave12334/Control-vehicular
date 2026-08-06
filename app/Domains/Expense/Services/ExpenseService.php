<?php

namespace App\Domains\Expense\Services;

use App\Domains\Expense\Actions\ApproveExpenseAction;
use App\Domains\Expense\Actions\CreateExpenseAction;
use App\Domains\Expense\Actions\DeleteExpenseAction;
use App\Domains\Expense\Actions\RejectExpenseAction;
use App\Domains\Expense\Actions\RestoreExpenseAction;
use App\Domains\Expense\DTO\ExpenseData;
use App\Domains\Expense\Models\Expense;
use Illuminate\Pagination\LengthAwarePaginator;

class ExpenseService
{
    public function __construct(
        protected CreateExpenseAction $createAction,
        protected ApproveExpenseAction $approveAction,
        protected RejectExpenseAction $rejectAction,
        protected DeleteExpenseAction $deleteAction,
        protected RestoreExpenseAction $restoreAction,
    ) {}

    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Expense::with(['vehicle', 'operator', 'approver'])
            ->orderBy('date', 'desc')->paginate($perPage);
    }

    public function getAllFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $q = Expense::with(['vehicle', 'operator', 'approver', 'route'])
            ->orderBy('date', 'desc');

        if (! empty($filters['search'])) {
            $search = trim($filters['search']);
            $q->where(function ($query) use ($search) {
                $query->where('folio', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('provider_name', 'like', "%{$search}%")
                    ->orWhereHas('vehicle', fn ($v) => $v->where('plate', 'like', "%{$search}%"));
            });
        }

        if (! empty($filters['status'])) {
            $q->where('status', $filters['status']);
        }

        if (! empty($filters['vehicle_id'])) {
            $q->where('vehicle_id', (int) $filters['vehicle_id']);
        }

        if (! empty($filters['date_from'])) {
            $q->whereDate('date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $q->whereDate('date', '<=', $filters['date_to']);
        }

        return $q->paginate($perPage);
    }

    public function getById(int $id): Expense
    {
        return Expense::with(['vehicle', 'operator', 'approver'])->findOrFail($id);
    }

    public function create(ExpenseData $data): Expense
    {
        return $this->createAction->execute($data);
    }

    public function approve(int $id, int $approvedBy): Expense
    {
        return $this->approveAction->execute($id, $approvedBy);
    }

    public function reject(int $id, string $reason): Expense
    {
        return $this->rejectAction->execute($id, $reason);
    }

    public function delete(int $id): bool
    {
        return $this->deleteAction->execute($id);
    }

    public function restore(int $id): Expense
    {
        return $this->restoreAction->execute($id);
    }
}
