<?php
 
namespace App\Domains\Route\Actions;
 
use App\Domains\Route\Models\Route;
use App\Domains\Route\DTO\RouteData;
 
class CreateRouteAction
{
    public function execute(RouteData $data): Route
    {
        return Route::create($data->toArray());
    }
}
