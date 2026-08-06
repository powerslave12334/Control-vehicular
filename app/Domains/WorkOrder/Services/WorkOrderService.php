<?php

namespace App\Domains\WorkOrder\Services;

use App\Domains\WorkOrder\Models\WorkOrder;
use App\Domains\WorkOrder\DTO\WorkOrderData;
use App\Domains\WorkOrder\DTO\WorkOrderPartData;
use App\Domains\WorkOrder\DTO\WorkOrderEvaluationData;
use App\Domains\WorkOrder\Actions\CreateWorkOrderAction;
use App\Domains\WorkOrder\Actions\DeleteWorkOrderAction;
use App\Domains\WorkOrder\Actions\RestoreWorkOrderAction;
use App\Domains\WorkOrder\Actions\SubmitForApprovalAction;
use App\Domains\WorkOrder\Actions\ApproveWorkOrderAction;
use App\Domains\WorkOrder\Actions\RejectWorkOrderAction;
use App\Domains\WorkOrder\Actions\AssignWorkOrderAction;
use App\Domains\WorkOrder\Actions\StartWorkOrderAction;
use App\Domains\WorkOrder\Actions\CompleteWorkOrderAction;
use App\Domains\WorkOrder\Actions\CloseWorkOrderAction;
use App\Domains\WorkOrder\Actions\CancelWorkOrderAction;
use App\Domains\WorkOrder\Actions\AddPartToWorkOrderAction;
use App\Domains\WorkOrder\Models\WorkOrderPart;
use Illuminate\Pagination\LengthAwarePaginator;

class WorkOrderService
{
    public function __construct(
        protected CreateWorkOrderAction $createAction,
        protected DeleteWorkOrderAction $deleteAction,
        protected RestoreWorkOrderAction $restoreAction,
        protected SubmitForApprovalAction $submitAction,
        protected ApproveWorkOrderAction $approveAction,
        protected RejectWorkOrderAction $rejectAction,
        protected AssignWorkOrderAction $assignAction,
        protected StartWorkOrderAction $startAction,
        protected CompleteWorkOrderAction $completeAction,
        protected CloseWorkOrderAction $closeAction,
        protected CancelWorkOrderAction $cancelAction,
        protected AddPartToWorkOrderAction $addPartAction,
    ) {}

    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return WorkOrder::with(['vehicle', 'provider', 'requester', 'parts'])
            ->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getById(int $id): WorkOrder
    {
        return WorkOrder::with(['vehicle', 'provider', 'requester', 'approver', 'assignedProvider', 'parts.part', 'timeline.user', 'evaluation'])->findOrFail($id);
    }

    public function create(WorkOrderData $data): WorkOrder
    {
        return $this->createAction->execute($data);
    }

    public function delete(int $id): bool
    {
        return $this->deleteAction->execute($id);
    }

    public function restore(int $id): WorkOrder
    {
        return $this->restoreAction->execute($id);
    }

    public function submitForApproval(int $id, int $userId): WorkOrder
    {
        return $this->submitAction->execute($id, $userId);
    }

    public function approve(int $id, int $approvedBy): WorkOrder
    {
        return $this->approveAction->execute($id, $approvedBy);
    }

    public function reject(int $id, string $reason, int $userId): WorkOrder
    {
        return $this->rejectAction->execute($id, $reason, $userId);
    }

    public function assign(int $id, int $providerId, int $userId): WorkOrder
    {
        return $this->assignAction->execute($id, $providerId, $userId);
    }

    public function start(int $id, int $userId): WorkOrder
    {
        return $this->startAction->execute($id, $userId);
    }

    public function complete(int $id, float $laborCost, ?float $mileage = null, ?string $diagnosis = null): WorkOrder
    {
        return $this->completeAction->execute($id, $laborCost, $mileage, $diagnosis);
    }

    public function close(int $id, WorkOrderEvaluationData $evaluation): WorkOrder
    {
        return $this->closeAction->execute($id, $evaluation);
    }

    public function cancel(int $id, string $reason, int $userId): WorkOrder
    {
        return $this->cancelAction->execute($id, $reason, $userId);
    }

    public function addPart(WorkOrderPartData $data, int $userId): WorkOrderPart
    {
        return $this->addPartAction->execute($data, $userId);
    }

    public function generateCode(): string
    {
        $prefix = 'WO-' . now()->format('Ym') . '-';
        $last = WorkOrder::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')->value('code');
        $num = $last ? (int)substr($last, -5) + 1 : 1;
        return $prefix . str_pad((string)$num, 5, '0', STR_PAD_LEFT);
    }
}
