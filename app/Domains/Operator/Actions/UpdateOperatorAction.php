<?php
 
namespace App\Domains\Operator\Actions;
 
use App\Domains\Operator\Models\Operator;
use App\Domains\Operator\DTO\OperatorData;
 
class UpdateOperatorAction
{
    public function execute(int $id, OperatorData $data): Operator
    {
        $operator = Operator::findOrFail($id);
        $operator->update($data->toArray());
        return $operator;
    }
}
