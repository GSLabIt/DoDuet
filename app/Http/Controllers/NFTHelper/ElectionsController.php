<?php


namespace App\Http\Controllers\NFTHelper;


use App\Models\Election;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class ElectionsController
{
    /**
     * Retrieve the election of the current week
     * @return Model|Election|Builder|null
     */
    public static function getCurrentElection(): Model|Election|Builder|null
    {
        $week_start = now()->startOfWeek(Carbon::MONDAY);
        $week_end = now()->endOfWeek(Carbon::SUNDAY);
        return Election::whereBetween("created_at", [$week_start, $week_end])->first();
    }

    /**
     * Returns a carbon representation of the first day of the week
     * @return \Illuminate\Support\Carbon
     */
    public static function weekStart(): \Illuminate\Support\Carbon
    {
        return now()->startOfWeek(Carbon::MONDAY);
    }

    /**
     * Returns a carbon representation of the last day of the week
     * @return \Illuminate\Support\Carbon
     */
    public static function weekEnd(): \Illuminate\Support\Carbon
    {
        return now()->endOfWeek(Carbon::SUNDAY);
    }
}
