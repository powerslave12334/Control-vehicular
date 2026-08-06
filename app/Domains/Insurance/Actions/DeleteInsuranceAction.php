<?php

namespace App\Domains\Insurance\Actions;

use App\Domains\Insurance\Models\Insurance;

class DeleteInsuranceAction
{
    public function execute(int $id): bool
    {
        $insurance = Insurance::findOrFail($id);
        return $insurance->delete();
    }
}
