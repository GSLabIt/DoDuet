<?php

namespace App\Http\Controllers;


use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class SettingsController extends Controller
{
    /**
     * This function retrieves and return the server public key
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getServerPublicKey(Request $request): JsonResponse {
        return response()->json([
            "key" => env("SERVER_PUBLIC_KEY")
        ]);
    }

    /**
     * This function retrieves and return the user private key
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserSecretKey(Request $request): JsonResponse {
        /** @var User $user */
        $user = auth()->user();
        return response()->json([
            "key" => secureUser($user)->get(secureUser($user)->whitelistedItems()["secret_key"])
        ]);
    }

    /**
     * This function retrieves and return the user public key of a specified user
     *
     * @param Request $request
     * @param string $user_id
     * @return JsonResponse
     * @throws Exception
     * @throws ValidationException
     */
    public function getUserPublicKey(Request $request, string $user_id): JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "user_id" => "required|uuid|exists:users,id",
        ]);

        /** @var User $user */
        $user = User::where("id", $user_id)->first();

        if(!is_null($user)) {
            return response()->json([
                "key" => secureUser($user)->get(secureUser($user)->whitelistedItems()["public_key"])
            ]);
        }

        throw new \App\Exceptions\SafeException(
            config("error-codes.USER_NOT_FOUND.message"),
            config("error-codes.USER_NOT_FOUND.code")
        );
    }


    /**
     * This function retrieves and return the listened voice of the setting challenge_nine_random_tracks
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getListenedChallengeRandomTracks(Request $request): JsonResponse {
        /** @var User $user */
        $user = auth()->user();

        if(settings($user)->has("challenge_nine_random_tracks")) {
            return response()->json([
                "listened" => settings($user)->get("challenge_nine_random_tracks")->listened
            ]);
        }

        throw new \App\Exceptions\SafeException(
            config("error-codes.SETTING_NOT_FOUND.message"),
            config("error-codes.SETTING_NOT_FOUND.code")
        );
    }
}
