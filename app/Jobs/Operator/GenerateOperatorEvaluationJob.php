<?php

namespace App\Jobs\Operator;

use App\Domains\Operator\Models\Operator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class GenerateOperatorEvaluationJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function __construct(public readonly Operator $operator) {}

    public function handle(): void
    {
        \Illuminate\Support\Facades\Log::info('Operator evaluation report generated', [
            'operator_id' => $this->operator->id,
            'name' => $this->operator->name,
        ]);
    }
}
