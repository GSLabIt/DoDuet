<?php

namespace App\Http\Controllers;

use App\Exceptions\SafeException;
use App\Models\User;
use App\Notifications\BanNotification;
use App\Notifications\ReportNotification;
use App\Notifications\UnbanNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BanController extends Controller
{
    /**
     * @throws \Illuminate\Validation\ValidationException
     * @throws SafeException
     */
    public function banUser(Request $request, $user_id): ?JsonResponse {
        Validator::validate($request->all(), [
            "comment" => "required|string|max:255",
            "expired_at" => "nullable|date|after:now"
        ]);
        /** @var $user User */
        $user = auth()->user();

        $banUser = User::where("id",$user_id)->first();

        if(!empty($banUser)) {
            $ban = $user->ban([
                "comment" => $request->input("comment"),
                "expired_at" => $request->input("expired_at")
            ]);
            if(!empty($request->input("expired_at"))) {
                $ban->isTemporary();
            } else {
                $ban->isPermanent();
            }
            $banUser->notify(new BanNotification($banUser,$ban));
            return response()->json([
                "ban" => $ban
            ]);
        }

        throw new SafeException(
            config("error-codes.USER_NOT_FOUND.message"),
            config("error-codes.USER_NOT_FOUND.code")
        );
    }

    /**
     * @throws SafeException
     */
    public function unbanUser(Request $request, $user_id): ?JsonResponse {
        $unbanUser = User::where("id",$user_id)->first();
        if(!empty($unbanUser)) {
            $unbanUser->notify(new UnbanNotification($unbanUser));
            $unbanUser->unban();
            return response()->json([
                "user" => $unbanUser->withoutRelations()
            ]);
        }

        throw new SafeException(
            config("error-codes.USER_NOT_FOUND.message"),
            config("error-codes.USER_NOT_FOUND.code")
        );
    }
}
