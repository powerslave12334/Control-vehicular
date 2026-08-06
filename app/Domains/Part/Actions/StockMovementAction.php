<?php

namespace App\Domains\Part\Actions;

use App\Domains\Part\Models\Part;
use App\Domains\Part\Models\PartInventory;
use App\Domains\Part\DTO\PartMovementData;
use App\Domains\Part\Events\PartLowStock;
use App\Domains\Part\Events\PartOutOfStock;

class StockMovementAction
{
    public function execute(PartMovementData $data): PartInventory
    {
        $part = Part::findOrFail($data->part_id);

        if ($part->status === 'discontinued') {
            throw new \DomainException('No se pueden registrar movimientos en refacciones discontinuadas.');
        }

        $quantity = $data->movement_type === 'out' ? -abs($data->quantity) : $data->quantity;
        if ($data->movement_type === 'adjustment') {
            $quantity = $data->quantity;
        }

        if ($part->current_stock + $quantity < 0) {
            throw new \DomainException('Stock insuficiente. Stock actual: ' . $part->current_stock);
        }

        $part->increment('current_stock', $quantity);

        $movement = PartInventory::create($data->toArray());

        if ($part->current_stock <= 0) {
            event(new PartOutOfStock($part));
        } elseif ($part->current_stock < $part->min_stock) {
            event(new PartLowStock($part));
        }

        return $movement;
    }
}
