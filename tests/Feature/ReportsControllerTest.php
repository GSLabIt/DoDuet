<?php

namespace Tests\Feature;

use App\Enums\RouteClass;
use App\Enums\RouteGroup;
use App\Enums\RouteMethod;
use App\Enums\RouteName;
use App\Models\ReportReasons;
use App\Models\Reports;
use App\Models\Tracks;
use App\Models\User;
use App\Notifications\ReportNotification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ReportsControllerTest extends TestCase
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

    /** Test well-formed Report creation */
    public function test_report_creation()
    {
        Notification::fake();
        $this->authAsUser();
        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id,
            "creator_id" => $this->user->id,
        ]);
        /** @var ReportReasons $report_reason */
        $report_reason = ReportReasons::factory()->create();
        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::REPORTS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::REPORT_TRACK_CREATE),
            [
                "extra_informations" => "Extra informations",
                "reportable_id" => $track->id,
                "reason_id" => $report_reason->id
            ]
        )->assertJsonStructure([
            "report"
        ]);
        Notification::assertSentTo($this->user, ReportNotification::class);
    }

    /** Test well-formed Report creation with null informations */
    public function test_report_creation_null_informations()
    {
        Notification::fake();
        $this->authAsUser();
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The extra informations field is required.");
        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id,
            "creator_id" => $this->user->id,
        ]);
        /** @var ReportReasons $report_reason */
        $report_reason = ReportReasons::factory()->create();
        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::REPORTS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::REPORT_TRACK_CREATE),
            [
                "extra_informations" => null,
                "reportable_id" => $track->id,
                "reason_id" => $report_reason->id
            ]
        );
    }


    /** Test Report update */

    /** Test well-formed Report update */
    public function test_report_update()
    {
        $this->authAsUser();
        /** @var Reports $report */
        $report = Reports::factory()->create();
        $report->update([
            "reporter_id" => $this->user->id
        ]);
        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id,
            "creator_id" => $this->user->id,
        ]);
        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::REPORTS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::REPORT_UPDATE)
            ->route([
                "report_id" => $report->id
            ]),
            [
                "extra_informations" => "Extra informations"
            ]
        )->assertJsonStructure([
            "report"
        ]);
    }

    /** Test well-formed Report update on unowned report*/
    public function test_report_unowned_update()
    {
        $this->authAsUser();
        $this->expectExceptionMessage(config("error-codes.REPORT_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.REPORT_NOT_FOUND.code"));
        /** @var ReportReasons $report */
        $report = Reports::factory()->create();
        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id,
            "creator_id" => $this->user->id,
        ]);
        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::REPORTS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::REPORT_UPDATE)
            ->route([
                "report_id" => $report->id
            ]),
            [
                "extra_informations" => "Extra informations"
            ]
        );
    }

    /** Test well-formed Report updated with null informations */
    public function test_report_update_null_informations()
    {
        $this->authAsUser();
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The extra informations field is required.");
        /** @var Reports $report */
        $report = Reports::factory()->create();
        $report->update([
            "reporter_id" => $this->user->id
        ]);
        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::REPORTS)
            ->method(RouteMethod::PUT)
            ->name(RouteName::REPORT_UPDATE)
            ->route([
                "report_id" => $report->id
            ]),
            [
                "extra_informations" => null
            ]
        );
    }
}
