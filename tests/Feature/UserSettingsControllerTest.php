<?php

namespace Tests\Feature;

use App\Enums\RouteClass;
use App\Enums\RouteGroup;
use App\Enums\RouteMethod;
use App\Enums\RouteName;
use App\Exceptions\SafeException;
use App\Models\User;
use Doinc\Modules\Settings\Exceptions\SettingNotFound;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserSettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    /**
     * Set up function.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
        $this->seed();

        /**@var User $user */
        $user = User::factory()->create();
        $this->user = $user;

        $this->actingAs($this->user);
        secureUser($this->user)->set("password", "password");
        // get the symmetric encryption key of the current user, this key is used to encrypt all
        // user's personal data
        $symmetric_key = secureUser($this->user)->get(secureUser($this->user)->whitelistedItems()["symmetric_key"])["key"];
        session()->put("mnemonic", "sauce blame resist south pelican area devote scissors silk treat observe nice aim fiction video nuclear apple lava powder swing trumpet vague hen illegal");
        $this->user->wallet()->create([
                "chain" => "beats",
                "private_key" => sodium()->encryption()->symmetric()->encrypt(
                    "0xf54dd85831b26ca012ed028fdbedbc73677e4ea60998ceb6111b0c6eebc40c06",
                    $symmetric_key,
                    sodium()->derivation()->generateSymmetricNonce()
                ),
                "public_key" => "0xec184bd9da1744ee9d3831b053c502cf41d2a0f641ec8bf7ac57cdc20bf6c51f",
                "seed" => sodium()->encryption()->symmetric()->encrypt(
                    "sauce blame resist south pelican area devote scissors silk treat observe nice aim fiction video nuclear apple lava powder swing trumpet vague hen illegal",
                    $symmetric_key,
                    sodium()->derivation()->generateSymmetricNonce()
                ),
                "address" => "6nDAFcQv9qgrG3PVtWxGTvFPvRdZjhr2NieScthS1guVp7Qg",
            ]
        );
    }

    private function authAsUser()
    {
        $this->actingAs($this->user);
        session()->put("mnemonic", "sauce blame resist south pelican area devote scissors silk treat observe nice aim fiction video nuclear apple lava powder swing trumpet vague hen illegal");
    }


    /** Test set AntiPhishing value */
    public function test_set_antiphishing_value() {
        $this->authAsUser();

        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::USER_SETTINGS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::USER_SETTINGS_SET),
            [
                "setting_name" => "anti_phishing_code",
                "value" => "TEST123"
            ]
        )->assertJsonStructure([
            "setting"
        ]);
    }

    /** Test set public_messaging value */
    public function test_set_public_messaging_value() {
        $this->authAsUser();

        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::USER_SETTINGS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::USER_SETTINGS_SET),
            [
                "setting_name" => "public_messaging",
                "value" => true
            ]
        )->assertJsonStructure([
            "setting"
        ]);
    }

    /** Test set display_wallet_address value */
    public function test_set_display_wallet_address_value() {
        $this->authAsUser();

        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::USER_SETTINGS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::USER_SETTINGS_SET),
            [
                "setting_name" => "display_wallet_address",
                "value" => true
            ]
        )->assertJsonStructure([
            "setting"
        ]);
    }

    /** Test set profile_page_public value */
    public function test_set_profile_page_public_value() {
        $this->authAsUser();

        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::USER_SETTINGS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::USER_SETTINGS_SET),
            [
                "setting_name" => "profile_page_public",
                "value" => true
            ]
        )->assertJsonStructure([
            "setting"
        ]);
    }

    /** Test set comment_contents value */
    public function test_set_comment_contents_value() {
        $this->authAsUser();

        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::USER_SETTINGS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::USER_SETTINGS_SET),
            [
                "setting_name" => "comment_contents",
                "value" => true
            ]
        )->assertJsonStructure([
            "setting"
        ]);
    }

    /** Test set comment_contents_public value */
    public function test_set_comment_contents_public_value() {
        $this->authAsUser();

        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::USER_SETTINGS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::USER_SETTINGS_SET),
            [
                "setting_name" => "comment_contents_public",
                "value" => true
            ]
        )->assertJsonStructure([
            "setting"
        ]);
    }

    /** Test set profile_page_public_unregistered value */
    public function test_set_profile_page_public_unregistered_value() {
        $this->authAsUser();

        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::USER_SETTINGS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::USER_SETTINGS_SET),
            [
                "setting_name" => "profile_page_public_unregistered",
                "value" => true
            ]
        )->assertJsonStructure([
            "setting"
        ]);
    }

    /** Test set users_follow value */
    public function test_set_users_follow_value() {
        $this->authAsUser();

        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::USER_SETTINGS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::USER_SETTINGS_SET),
            [
                "setting_name" => "users_follow",
                "value" => true
            ]
        )->assertJsonStructure([
            "setting"
        ]);
    }

    /** Test set users_tips value */
    public function test_set_users_tips_value() {
        $this->authAsUser();

        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::USER_SETTINGS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::USER_SETTINGS_SET),
            [
                "setting_name" => "users_tips",
                "value" => true
            ]
        )->assertJsonStructure([
            "setting"
        ]);
    }

    /** Test set AntiPhishing value with wrong type */
    public function test_set_antiphishing_value_wrong_type() {
        $this->authAsUser();

        $this->expectException(SafeException::class);
        $this->expectExceptionMessage("Unable to find the request setting");

        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::USER_SETTINGS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::USER_SETTINGS_SET),
            [
                "setting_name" => "anti_phishing_code",
                "value" => true
            ]
        );
    }

    /** Test set uneditable setting */
    public function test_set_uneditable_setting() {
        $this->authAsUser();

        $this->expectException(SafeException::class);
        $this->expectExceptionMessage("Unable to find the request setting");

        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::USER_SETTINGS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::USER_SETTINGS_SET),
            [
                "setting_name" => "master_key_salt",
                "value" => "A"
            ]
        );
    }
}
