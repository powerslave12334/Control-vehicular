<?php

namespace App\Shared\Traits;

use Illuminate\Support\Facades\Auth;

trait HasAuditColumns
{
    public static function bootHasAuditColumns(): void
    {
        static::creating(function ($model) {
            if (Auth::check() && !$model->isDirty('created_by')) {
                $model->created_by = Auth::id();
            }
            if (Auth::check() && !$model->isDirty('updated_by')) {
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

        static::restoring(function ($model) {
            if (Auth::check() && !$model->isDirty('updated_by')) {
                $model->updated_by = Auth::id();
            }
        });
    }
}
