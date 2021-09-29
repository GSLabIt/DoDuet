<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSegments;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserSegmentsController extends Controller
{
    public static function assignToSegment(User $user) {
        // check the user is not yet in another segment
        // this check will be done only here as we want to ensure that at registration the user is assigned to only one
        // segment, admins may move multiple users into different segments
        if($user->userSegments->count() === 0) {
            // retrieve the last user segment
            $user_segments = UserSegments::all()->last();

            // check if the segment does not exists or it is full, if this is the case create a new segment
            if(is_null($user_segments) || $user_segments->users->count() >= config("user-segments.per_segment")) {
                $user_segments = UserSegments::create([
                    "name" => Str::random(64)
                ]);
            }

            $user->userSegments()->save($user_segments);
        }
    }
}
