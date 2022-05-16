<?php

namespace Tests\Feature;

use App\Enums\RouteClass;
use App\Enums\RouteGroup;
use App\Enums\RouteMethod;
use App\Enums\RouteName;
use App\Models\ReportReasons;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Validation\ValidationException;

class ReportReasonsControllerTest extends TestCase
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

    /** Test ReportReason creation */

    /** Test well-formed ReportReason creation */
    public function test_report_reason_creation()
    {
        $this->user->assignRole(["super-admin"]);
        $this->authAsUser();
        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::REPORT_REASONS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::REPORT_REASONS_COVER_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
            ]
        )->assertJsonStructure([
            "reportReason"
        ]);
    }

    /** Test well-formed ReportReason creation */
    public function test_report_reason_creation_without_permission()
    {
        $this->authAsUser();
        $this->expectException(AuthorizationException::class);
        $this->expectExceptionMessage("This action is unauthorized.");
        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::REPORT_REASONS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::REPORT_REASONS_COVER_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
            ]
        );
    }

    /** Test well-formed Track creation with null name */
    public function test_track_creation_null_name()
    {
        $this->user->assignRole(["super-admin"]);
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The name field is required.");
        $this->authAsUser();
        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::REPORT_REASONS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::REPORT_REASONS_USER_CREATE),
            [
                "name" => null,
                "description" => "Description",
            ]
        );
    }

    /** Test well-formed Track creation with null description */
    public function test_track_creation_null_description()
    {
        $this->user->assignRole(["super-admin"]);
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The description field is required.");
        $this->authAsUser();
        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::REPORT_REASONS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::REPORT_REASONS_ALBUM_CREATE),
            [
                "name" => "Name",
                "description" => null,
            ]
        );
    }

    /** Test ReportReason update */

    /** Test well-formed ReportReason update */
    public function test_report_reason_update()
    {
        $this->user->assignRole(["super-admin"]);
        $this->authAsUser();
        /** @var ReportReasons $report_reason */
        $report_reason = ReportReasons::factory()->create();
        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::REPORT_REASONS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::REPORT_REASONS_UPDATE)
            ->route([
                "report_reason_id" => $report_reason->id
            ]),
            [
                "name" => "Name",
                "description" => "Description",
            ]
        )->assertJsonStructure([
            "reportReason"
        ]);
    }

    /** Test well-formed ReportReason update */
    public function test_report_reason_update_without_permission()
    {
        $this->expectException(AuthorizationException::class);
        $this->expectExceptionMessage("This action is unauthorized.");
        $this->authAsUser();
        /** @var ReportReasons $report_reason */
        $report_reason = ReportReasons::factory()->create();
        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::REPORT_REASONS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::REPORT_REASONS_UPDATE)
            ->route([
                "report_reason_id" => $report_reason->id
            ]),
            [
                "name" => "Name",
                "description" => "Description",
            ]
        );
    }

    /** Test well-formed Track update with null name */
    public function test_report_reason_update_null_name()
    {
        $this->user->assignRole(["super-admin"]);
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The name field is required.");
        /** @var ReportReasons $report_reason */
        $report_reason = ReportReasons::factory()->create();
        $this->authAsUser();
        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::REPORT_REASONS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::REPORT_REASONS_UPDATE)
            ->route([
                "report_reason_id" => $report_reason->id
            ]),
            [
                "name" => null,
                "description" => "Description",
            ]
        );
    }

    /** Test well-formed Track update with null description */
    public function test_report_reason_update_null_description()
    {
        $this->user->assignRole(["super-admin"]);
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The description field is required.");
        /** @var ReportReasons $report_reason */
        $report_reason = ReportReasons::factory()->create();
        $this->authAsUser();
        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::REPORT_REASONS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::REPORT_REASONS_UPDATE)
            ->route([
                "report_reason_id" => $report_reason->id
            ]),
            [
                "name" => "Name",
                "description" => null,
            ]
        );
    }

    /** Test ReportReason delete */

    /** Test well-formed ReportReason delete */
    public function test_report_reason_delete()
    {
        $this->user->assignRole(["super-admin"]);
        $this->authAsUser();
        /** @var ReportReasons $report_reason */
        $report_reason = ReportReasons::factory()->create();
        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::REPORT_REASONS)
            ->method(RouteMethod::DELETE)
            ->name(RouteName::REPORT_REASONS_DELETE)
            ->route([
                "report_reason_id" => $report_reason->id
            ])
        )->assertJsonStructure([
            "reportReason"
        ]);
    }

    /** Test well-formed ReportReason delete */
    public function test_report_reason_delete_without_permission()
    {
        $this->expectException(AuthorizationException::class);
        $this->expectExceptionMessage("This action is unauthorized.");
        $this->authAsUser();
        /** @var ReportReasons $report_reason */
        $report_reason = ReportReasons::factory()->create();
        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::REPORT_REASONS)
            ->method(RouteMethod::DELETE)
            ->name(RouteName::REPORT_REASONS_DELETE)
            ->route([
                "report_reason_id" => $report_reason->id
            ])
        );
    }
}
