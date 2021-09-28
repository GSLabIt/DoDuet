<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Activitylog\Traits\LogsActivity;

class UserSegmentsFunctionalities extends Pivot
{
    use LogsActivity, ActivityLogAll;
}
