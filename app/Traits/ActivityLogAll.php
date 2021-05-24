<?php
namespace App\Traits;

use Spatie\Activitylog\LogOptions;

trait ActivityLogAll
{
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
        // Chain fluent methods for configuration options
    }
}
