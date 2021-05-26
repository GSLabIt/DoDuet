<?php


namespace App\Http\Controllers\NFTSession;


use Carbon\Carbon;

class NFTTimes extends NFTSession
{
    public function getPlaying(): float|int|string|null {
        return self::get(self::$PLAYING_TIME);
    }

    public function hasPlaying(): bool
    {
        return self::has(self::$PLAYING_TIME);
    }

    public function hasNotPlaying(): bool
    {
        return !$this->hasPlaying();
    }

    public function isPlayingTimeElapsed(): bool
    {
        return self::states()->isNotPlaying() ||
            (   // is playing but timer is elapsed
                self::states()->isPlaying() &&
                now()->isAfter(Carbon::createFromTimestamp(self::getPlaying()))
            );
    }

    public function isNotPlayingTimeElapsed(): bool {
        return !$this->isPlayingTimeElapsed();
    }
}
