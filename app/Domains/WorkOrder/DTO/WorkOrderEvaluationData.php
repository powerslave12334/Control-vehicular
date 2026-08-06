<?php

namespace App\Domains\WorkOrder\DTO;

readonly class WorkOrderEvaluationData
{
    public function __construct(
        public int $work_order_id,
        public int $quality_score,
        public int $timeliness_score,
        public int $cost_score,
        public int $evaluated_by,
        public ?string $comments = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            work_order_id: (int)$data['work_order_id'],
            quality_score: (int)$data['quality_score'],
            timeliness_score: (int)$data['timeliness_score'],
            cost_score: (int)$data['cost_score'],
            evaluated_by: (int)$data['evaluated_by'],
            comments: $data['comments'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'work_order_id' => $this->work_order_id,
            'quality_score' => $this->quality_score,
            'timeliness_score' => $this->timeliness_score,
            'cost_score' => $this->cost_score,
            'evaluated_by' => $this->evaluated_by,
            'comments' => $this->comments,
        ];
    }
}
