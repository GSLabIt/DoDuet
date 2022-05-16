<?php

namespace App\Http\Controllers;

use App\Exceptions\SafeException;
use App\Models\PersonalInformations;
use App\Models\User;
use Doinc\Modules\TelnyxExtendedApi\Exceptions\InvalidNumber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use TelnyxExtendedApi;

class PersonalInformationsController extends Controller
{
    /**
     * @throws \Illuminate\Validation\ValidationException
     * @throws SafeException
     */
    public function setPersonalInformations(Request $request): JsonResponse {
        Validator::validate($request->all(), [
            "alias" => "nullable|string|max:255",
            "mobile" => "nullable|string",
            "profile_cover_path" => "nullable|url",
            "description" => "nullable|string",
        ]);

        /** @var $user User */
        $user = auth()->user();

        /** @var PersonalInformations $personal_informations */
        $personal_informations = PersonalInformations::where("owner_id", $user->id)->first();
        try {
            if(!empty($request->input("mobile")) && !TelnyxExtendedApi::numberLookup($request->input("mobile"))->isValid()) {
                throw new SafeException(
                    config("error-codes.INVALID_MOBILE_NUMBER.message"),
                    config("error-codes.INVALID_MOBILE_NUMBER.code")
                );
            }
        } catch (InvalidNumber) {
            throw new SafeException(
                config("error-codes.INVALID_MOBILE_NUMBER.message"),
                config("error-codes.INVALID_MOBILE_NUMBER.code")
            );
        }

        if(!empty($personal_informations)) {
            $personal_informations->update([
                "alias" => $request->input("alias"),
                "mobile" => $request->input("mobile"),
                "profile_cover_path" => $request->input("profile_cover_path"),
                "description" => $request->input("description")
            ]);
        } else {
            $personal_informations = PersonalInformations::create([
                "alias" => $request->input("alias"),
                "mobile" => $request->input("mobile"),
                "profile_cover_path" => $request->input("profile_cover_path"),
                "description" => $request->input("description"),
                "owner_id" => $user->id,
            ]);
        }

        return response()->json([
            "personal_informations" => $personal_informations->withoutRelations()
        ]);
    }
}
