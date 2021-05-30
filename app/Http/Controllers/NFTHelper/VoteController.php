<?php


namespace App\Http\Controllers\NFTHelper;


use App\Models\Track;
use App\Models\User;

class VoteController
{
    /**
     * Check if the current user is the owner of the track
     * @param Track $track
     * @return bool
     */
    public static function currentUserIsOwner(Track $track): bool
    {
        $user = auth()->user(); /**@var User $user*/
        return self::isOwner($track, $user);
    }

    /**
     * Check if the provided instance of the user is the owner of the track
     * @param Track $track
     * @param User $user
     * @return bool
     */
    public static function isOwner(Track $track, User $user): bool
    {
        return $track->owner_id === $user->id;
    }

    /**
     * Check if the current user is NOT the owner of the track
     * @param Track $track
     * @return bool
     */
    public static function currentUserIsNotOwner(Track $track): bool
    {
        return !self::currentUserIsOwner($track);
    }

    /**
     * Check if the provided instance of the user is NOT the owner of the track
     * @param Track $track
     * @param User $user
     * @return bool
     */
    public static function isNotOwner(Track $track, User $user): bool {
        return !self::isOwner($track, $user);
    }
}
