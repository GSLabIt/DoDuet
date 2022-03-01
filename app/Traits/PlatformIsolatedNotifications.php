<?php

namespace App\Traits;

use Illuminate\Notifications\HasDatabaseNotifications;
use Illuminate\Notifications\RoutesNotifications;

trait PlatformIsolatedNotifications
{
    use MultiDatabaseRelation;
    use RoutesNotifications;
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
