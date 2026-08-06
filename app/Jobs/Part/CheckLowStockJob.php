<?php

namespace App\Jobs\Part;

use App\Domains\Part\Models\Part;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class CheckLowStockJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function handle(): void
    {
        $lowStock = Part::whereColumn('stock', '<=', 'min_stock')->get();

        foreach ($lowStock as $part) {
            \App\Domains\Notification\Models\Notification::create([
                'user_id' => 1,
                'type' => 'warning',
                'priority' => 'high',
                'title' => 'Stock bajo',
                'message' => "Refacción {$part->name} — stock: {$part->stock}, mínimo: {$part->min_stock}",
                'read' => false,
                'related_id' => $part->id,
                'related_type' => Part::class,
            ]);
        }
    }
}
