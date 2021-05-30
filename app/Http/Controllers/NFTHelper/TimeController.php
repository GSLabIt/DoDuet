<?php


namespace App\Http\Controllers\NFTHelper;


use App\Models\Track;
use Carbon\Carbon;

class TimeController
{
    /**
     * Returns the carbon representation of the track duration
     * @param Track $track
     * @return bool|Carbon
     */
    public static function getTrackDuration(Track $track): bool|Carbon
    {
        return Carbon::createFromFormat("H:i:s", $track->duration);
    }

    /**
     * Returns the amount of time the track should be available after the listening request
     * @param Carbon $duration
     * @return Carbon
     */
    public static function getTrackListeningDuration(Carbon $duration): Carbon
    {
        return $duration->addMinutes(env("TRACK_LISTENING_TIME_MINUTES"))
            ->addSeconds(env("TRACK_LISTENING_TIME_SECONDS"));
    }

    /**
     * Creates the url elapse time of a track
     * @param Carbon $duration
     * @return \Illuminate\Support\Carbon
     */
    public static function getUrlElapseTime(Carbon $duration): \Illuminate\Support\Carbon
    {
        return now()->addHours($duration->hour)
            ->addMinutes($duration->minute)
            ->addSeconds($duration->second);
    }
}
