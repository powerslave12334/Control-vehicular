<?php

namespace App\Domains\Part\Services;

use App\Domains\Part\Models\Part;
use App\Domains\Part\Models\PartCategory;
use App\Domains\Part\Models\PartBrand;
use App\Domains\Part\Models\PartInventory;
use App\Domains\Part\DTO\PartData;
use App\Domains\Part\DTO\PartMovementData;
use App\Domains\Part\Actions\CreatePartAction;
use App\Domains\Part\Actions\UpdatePartAction;
use App\Domains\Part\Actions\DeletePartAction;
use App\Domains\Part\Actions\RestorePartAction;
use App\Domains\Part\Actions\StockMovementAction;
use Illuminate\Pagination\LengthAwarePaginator;

class PartService
{
    public function __construct(
        protected CreatePartAction $createAction,
        protected UpdatePartAction $updateAction,
        protected DeletePartAction $deleteAction,
        protected RestorePartAction $restoreAction,
        protected StockMovementAction $stockMovementAction,
    ) {}

    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Part::with(['category', 'brand'])
            ->orderBy('name')->paginate($perPage);
    }

    public function getById(int $id): Part
    {
        return Part::with(['category', 'brand', 'suppliers.provider'])->findOrFail($id);
    }

    public function create(PartData $data): Part
    {
        return $this->createAction->execute($data);
    }

    public function update(int $id, PartData $data): Part
    {
        return $this->updateAction->execute($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->deleteAction->execute($id);
    }

    public function restore(int $id): Part
    {
        return $this->restoreAction->execute($id);
    }

    public function registerMovement(PartMovementData $data): PartInventory
    {
        return $this->stockMovementAction->execute($data);
    }

    public function getStockAlerts(): array
    {
        $lowStock = Part::where('current_stock', '>', 0)
            ->whereColumn('current_stock', '<', 'min_stock')->get();
        $outOfStock = Part::where('current_stock', '<=', 0)->get();
        $overstock = Part::whereNotNull('max_stock')
            ->whereColumn('current_stock', '>', 'max_stock')->get();

        return [
            'low_stock' => $lowStock,
            'out_of_stock' => $outOfStock,
            'overstock' => $overstock,
        ];
    }

    public function getCategories(): \Illuminate\Database\Eloquent\Collection
    {
        return PartCategory::all();
    }

    public function getBrands(): \Illuminate\Database\Eloquent\Collection
    {
        return PartBrand::all();
    }
}
