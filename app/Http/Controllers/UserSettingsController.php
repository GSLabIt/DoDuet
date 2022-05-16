<?php

namespace App\Http\Controllers;

use App\Exceptions\SafeException;
use App\Models\User;
use Doinc\Modules\Settings\Exceptions\SettingNotFound;
use Doinc\Modules\Settings\Models\DTOs\Setting;
use Doinc\Modules\Settings\Models\DTOs\SettingBool;
use Doinc\Modules\Settings\Models\DTOs\SettingString;
use Doinc\Modules\Settings\Models\Settings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use function Pest\Laravel\instance;

class UserSettingsController extends Controller
{
    /**
     * @throws ValidationException
     * @throws SafeException
     * @throws SettingNotFound
     */
    public function setSettingValue(Request $request): JsonResponse
    {
        Validator::validate($request->all(), [
            "setting_name" => "required|string|max:255",
            "value" => "required"
        ]);
        /** @var User $user */
        $user = auth()->user();

        if ($this->isSettingValid($request->input("setting_name"))) {
            $setting = settings($user)->has($request->input("setting_name")) ? settings($user)->get($request->input("setting_name")) : null;
            $type = Settings::whereEncryptedIs("name",is_null($setting) ? $request->input("setting_name") : $setting->qualifiedName())->first()->type;
            if ($type === SettingBool::class && is_bool($request->input("value"))) {
                /** @var SettingBool $setting */
                if(!is_null($setting)) {
                    $setting->value = (bool) $request->input("value");
                    settings($user)->update($request->input("setting_name"), $setting->toJson());
                } else {
                    $setting = new SettingBool(value: (bool) $request->input("value"));
                    settings($user)->set($request->input("setting_name"), $setting->toJson());
                }
            } elseif ($type === SettingString::class && is_string($request->input("value"))) {
                /** @var SettingString $setting */
                if(!is_null($setting)) {
                    $setting->value = (string) $request->input("value");
                    settings($user)->update($request->input("setting_name"), $setting->toJson());
                } else {
                    $setting = new SettingString(value: (string) $request->input("value"));
                    settings($user)->set($request->input("setting_name"), $setting->toJson());
                }
            } else {
                throw new SafeException(
                    config("error-codes.SETTING_NOT_FOUND.message"),
                    config("error-codes.SETTING_NOT_FOUND.code")
                );
            }
            return response()->json([
                "setting" => [
                    "name" => $request->input("setting_name"),
                    "value" => $request->input("value")
                ]
            ]);
        }

        throw new SafeException(
            config("error-codes.SETTING_NOT_FOUND.message"),
            config("error-codes.SETTING_NOT_FOUND.code")
        );
    }

    /**
     * @throws SafeException
     */
    private function isSettingValid(string $name): bool
    {
        if (in_array($name, [
            "public_messaging", "display_wallet_address", "profile_page_public",
            "comment_contents", "comment_contents_public", "profile_page_public_unregistered",
            "users_follow", "users_tips", "anti_phishing_code"
        ])) {
            return true;
        } else {
            throw new SafeException(
                config("error-codes.SETTING_NOT_FOUND.message"),
                config("error-codes.SETTING_NOT_FOUND.code")
            );
        }

    }
}
