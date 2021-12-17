<?php

namespace App\Traits;

use Illuminate\Notifications\RoutesNotifications;

trait MultiDatabaseNotifiable
{
    use RoutesNotifications, PlatformIsolatedNotifications;
}
