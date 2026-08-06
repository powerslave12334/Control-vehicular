<?php
 
namespace App\Domains\Operator\Actions;
 
use App\Domains\Operator\Models\Operator;
use App\Domains\Operator\DTO\OperatorData;
 
class CreateOperatorAction
{
    public function execute(OperatorData $data): Operator
    {
        return Operator::create($data->toArray());
    }
}
