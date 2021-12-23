<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\User;
use App\Notifications\NewReferralNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\RedirectResponse as SymponyRedirect;
use Illuminate\Http\RedirectResponse;

class SocialController extends Controller
{
    /**
     * This function redirects the user to the required social login page
     *
     * @param string $social
     * @return SymponyRedirect
     * @throws ValidationException
     */
    public function redirect(string $social): SymponyRedirect
    {
        Validator::validate([
            'social' => $social
        ], [
            'social' => 'required|string|in:github,google,twitter,facebook,microsoft,wordpress,yahoo,discord,twitch,asana,atlassian,bitly,bitbucket,digitalocean,dropbox,envato,eventbrite,heroku',
        ]);

        return Socialite::driver($social)->redirect();
    }

    /**
     * Either login or create the user from socialite callback
     *
     * @param string $social
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function callback(string $social): RedirectResponse
    {
        Validator::validate([
            'social' => $social
        ], [
            'social' => 'required|string|in:github,google,twitter,facebook,microsoft,wordpress,yahoo,discord,twitch,asana,atlassian,bitly,bitbucket,digitalocean,dropbox,envato,eventbrite,heroku',
        ]);

        $oauthUser = Socialite::driver($social)->user();

        $oauthEmail = $oauthUser->getEmail(); // get email from oauth service
        $oauthGeneratedPassword = "{$oauthUser->getId()}:{$oauthEmail}"; //compose a password with ID:EMAIL format

        // Try to login the user
        $user = User::where('email', $oauthEmail)->first();

        if ($user) {
            Auth::login($user);
            secureUser($user)->set("password", $oauthGeneratedPassword);
            wallet($user)->generate();
            return redirect()->to("dashboard");
        }

        // If the User does not exist, create it
        $user = User::create([
            'name' => $oauthUser->getName() ?? $oauthEmail,
            'email' => $oauthEmail,
            'password' => $oauthGeneratedPassword,
        ]);

        wallet($user)->generate();
        secureUser($user)->set("password", $oauthGeneratedPassword);

        UserSegmentsController::assignToSegment($user);

        // Generates unique referral code
        $user->referral()->create([
            "code" => hash("sha1", sodium()->derivation()->generateSalt(64))
        ]);

        // check if the user registered with a referral code
        // if session has the referral_code value than the user is registering with a ref
        if (session()->has("referral_code")) {
            // retrieve the ref code and forget it immediately in order to avoid potential session injection attacks
            $ref = session("referral_code");
            session()->forget("referral_code");

            // use the ref code to retrieve the referral and check for its existence
            $referral = Referral::where("code", $ref)->first();
            if (!is_null($referral)) {
                // if the referral code exists than associate the just created user with the referrer
                // the prize for the referrer is computed on the fly based on the amount of refs it has
                $prize = ReferralController::computePrizeForNewReferer($referral->owner);

                // actually create the association
                $user->referredBy()->create([
                    "referrer_id" => $referral->owner->id,
                    "prize" => $prize
                ]);

                // notify the referrer of the new ref
                $referral->owner->notify(new NewReferralNotification($user->id, $prize));
            }
        }

        Auth::login($user);

        return redirect()->to("dashboard");
    }
}


