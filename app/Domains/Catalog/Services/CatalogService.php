<?php

namespace App\Domains\Catalog\Services;

use App\Domains\Catalog\Models\Catalog;
use Illuminate\Support\Collection;

class CatalogService
{
    public function getAllGrouped(): Collection
    {
        return Catalog::orderBy('group')->orderBy('label')->get()->groupBy('group');
    }

    public function getByGroup(string $group): Collection
    {
        return Catalog::byGroup($group)->orderBy('label')->get();
    }

    public function getGroups(): Collection
    {
        return Catalog::select('group')->distinct()->pluck('group');
    }

    public function create(array $data): Catalog
    {
        return Catalog::create($data);
    }

    public function update(int $id, array $data): Catalog
    {
        $catalog = Catalog::findOrFail($id);
        $catalog->update($data);
        return $catalog;
    }

    public function delete(int $id): void
    {
        Catalog::findOrFail($id)->delete();
    }
}
