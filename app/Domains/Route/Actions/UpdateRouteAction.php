<?php
 
namespace App\Domains\Route\Actions;
 
use App\Domains\Route\Models\Route;
use App\Domains\Route\DTO\RouteData;
 
class UpdateRouteAction
{
    public function execute(int $id, RouteData $data): Route
    {
        $route = Route::findOrFail($id);
        $route->update($data->toArray());
        return $route;
    }
}
