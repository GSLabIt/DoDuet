<?php

namespace App\Traits;

use Illuminate\Notifications\HasDatabaseNotifications;

trait PlatformIsolatedNotifications
{
    use MultiDatabaseRelation;
    use HasDatabaseNotifications {
        notifications as protected _notification;
    }

    public function notifications()
    {
        return $this->multiDatabaseRunQuery(
            "mysql",
            fn() => $this->_notification()
        );
    }
}
