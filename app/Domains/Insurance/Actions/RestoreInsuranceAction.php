<?php

namespace App\Domains\Insurance\Actions;

use App\Domains\Insurance\Models\Insurance;

class RestoreInsuranceAction
{
    public function execute(int $id): Insurance
    {
        $insurance = Insurance::withTrashed()->findOrFail($id);
        $insurance->restore();
        return $insurance;
    }
}
