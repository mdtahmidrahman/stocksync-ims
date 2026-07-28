<?php

namespace App\Traits;

use App\Models\AuditLog;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $name = class_basename($model);
            $identifier = $model->name ?? $model->title ?? $model->reference_number ?? $model->order_number ?? $model->invoice_number ?? ("#" . $model->id);
            AuditLog::record("{$name} Created", "{$name} '{$identifier}' was created.");
        });

        static::updated(function ($model) {
            $name = class_basename($model);
            $identifier = $model->name ?? $model->title ?? $model->reference_number ?? $model->order_number ?? $model->invoice_number ?? ("#" . $model->id);
            
            if ($model->wasChanged('is_active')) {
                if ($model->is_active) {
                    AuditLog::record("{$name} Activated", "{$name} '{$identifier}' was set to active.");
                } else {
                    AuditLog::record("{$name} Deactivated", "{$name} '{$identifier}' was set to inactive.");
                }
            } else {
                AuditLog::record("{$name} Updated", "{$name} '{$identifier}' was updated.");
            }
        });

        static::deleted(function ($model) {
            $name = class_basename($model);
            $identifier = $model->name ?? $model->title ?? $model->reference_number ?? $model->order_number ?? $model->invoice_number ?? ("#" . $model->id);
            AuditLog::record("{$name} Deleted", "{$name} '{$identifier}' was deleted.");
        });
    }
}
