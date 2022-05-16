<?php

namespace Tests\Feature;

use App\Enums\RouteClass;
use App\Enums\RouteGroup;
use App\Enums\RouteMethod;
use App\Enums\RouteName;
use App\Exceptions\SafeException;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonalInformationsControllerTest extends TestCase
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

    /** Test well-formed personal informations set */
    public function test_personal_informations_set()
    {
        $this->authAsUser();

        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::PERSONAL_INFORMATIONS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::PERSONAL_INFORMATIONS_SET),
            [
                "alias" => "Alias",
                "mobile" => "+39 3383948286",
                "profile_cover_path" => "https://placekitten.com/200/300",
                "description" => "Description",
            ]
        )->assertJsonStructure([
            "personal_informations"
        ]);
    }

    /** Test well-formed personal informations set invalid mobile*/
    public function test_personal_informations_set_invalid_mobile()
    {
        $this->authAsUser();

        $this->expectException(SafeException::class);
        $this->expectExceptionMessage("The provided mobile phone number is invalid");
        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::PERSONAL_INFORMATIONS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::PERSONAL_INFORMATIONS_SET),
            [
                "alias" => "Alias",
                "mobile" => "+39 w4q2543383948286",
                "profile_cover_path" => "https://placekitten.com/200/300",
                "description" => "Description",
            ]
        );
    }

    /** Test well-formed personal informations set null informations*/
    public function test_personal_informations_set_null_informations()
    {
        $this->authAsUser();

        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::PERSONAL_INFORMATIONS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::PERSONAL_INFORMATIONS_SET),
            [
                "alias" => null,
                "mobile" => null,
                "profile_cover_path" => null,
                "description" => null,
            ]
        )->assertJsonStructure([
            "personal_informations"
        ]);
    }
}
