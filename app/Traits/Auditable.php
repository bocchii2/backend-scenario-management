<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

trait Auditable
{
    /**
     * Boot the auditable trait.
     */
    public static function bootAuditable()
    {
        static::creating(function ($model) {
            try {
                if (Auth::check()) {
                    if (Schema::hasColumn($model->getTable(), 'created_by') && empty($model->created_by)) {
                        $model->created_by = Auth::id();
                    }

                    if (Schema::hasColumn($model->getTable(), 'updated_by') && empty($model->updated_by)) {
                        $model->updated_by = Auth::id();
                    }
                }
            } catch (\Exception $e) {
                // En entornos donde la conexión DB no está lista (ej. durante deploy), evitar romper la app
            }
        });

        static::updating(function ($model) {
            try {
                if (Auth::check() && Schema::hasColumn($model->getTable(), 'updated_by')) {
                    $model->updated_by = Auth::id();
                }
            } catch (\Exception $e) {
                // noop
            }
        });
    }
}
