<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Referral\Tests\Feature;

use App\Models\User;
use Doinc\Modules\Referral\Enums\ReferralRoutes;
use Doinc\Modules\Referral\Events\NewReferralReceived;
use Doinc\Modules\Referral\Events\ReferralRedeemed;
use Doinc\Modules\Referral\Facades\Referral;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReferralTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();
    }

    public function test_can_create_and_get_referral_code()
    {
        $this->seed();
        $this->actingAs($user = User::factory()->create());

        $ref = Referral::getOrCreate(false);
        $this->assertNotEmpty($ref);
        $this->assertEquals(40, strlen($ref));

        $ref_regenerated = Referral::getOrCreate(false);
        $this->assertEquals(40, strlen($ref));
        $this->assertEquals($ref, $ref_regenerated);
    }

    public function test_can_create_and_get_random_referral_code()
    {
        $this->seed();
        $this->actingAs($user = User::factory()->create());

        $ref = Referral::getOrCreate();
        $this->assertNotEmpty($ref);
        $this->assertEquals(40, strlen($ref));

        $ref_regenerated = Referral::getOrCreate();
        $this->assertEquals(40, strlen($ref));
        $this->assertEquals($ref, $ref_regenerated);
    }

    public function test_retrieve_the_correct_url()
    {
        $this->seed();
        $this->actingAs($user = User::factory()->create());
        $ref = Referral::getOrCreate();

        $url = Referral::url();
        $computed = route(ReferralRoutes::POST_STORE_REFERRAL->value, ["ref" => $user->referral->code]);
        $this->assertEquals($computed, $url);
    }

    public function test_retrieve_the_correct_referral_prize()
    {
        $this->seed();
        $this->actingAs($user = User::factory()->create());
        $ref = Referral::getOrCreate();

        config()->set("referral.prizes", [
            [
                "min" => 0,
                "max" => 1,
                "prize" => 2
            ],
            [
                "min" => 2,
                "max" => 3,
                "prize" => 5
            ]
        ]);

        $u1 = User::factory()->create();

        $this->assertEquals(2, Referral::newRefPrize());

        $u1->referredBy()->create([
            "id" => Str::uuid()->toString(),
            "referrer_id" => $user->id,
            "prize" => 2
        ]);

        $this->assertEquals(5, Referral::newRefPrize());
    }

    public function test_retrieve_the_correct_total_referred_prize()
    {
        $this->seed();
        $this->actingAs($user = User::factory()->create());
        $ref = Referral::getOrCreate();

        config()->set("referral.prizes", [
            [
                "min" => 0,
                "max" => 1,
                "prize" => 2
            ],
            [
                "min" => 2,
                "max" => 3,
                "prize" => 5
            ]
        ]);

        $u1 = User::factory()->create();
        $u2 = User::factory()->create();

        $this->assertEquals(0, Referral::totalRefPrize());

        $u1->referredBy()->create([
            "id" => Str::uuid()->toString(),
            "referrer_id" => $user->id,
            "prize" => Referral::newRefPrize()
        ]);

        $this->assertEquals(2, Referral::totalRefPrize());

        $u2->referredBy()->create([
            "id" => Str::uuid()->toString(),
            "referrer_id" => $user->id,
            "prize" => Referral::newRefPrize()
        ]);

        $this->assertEquals(7, Referral::totalRefPrize());
    }

    public function test_redeeming_prizes_fires_event()
    {
        $this->seed();
        Event::fake();
        $this->actingAs($user = User::factory()->create());
        Referral::getOrCreate();

        config()->set("referral.prizes", [
            [
                "min" => 0,
                "max" => 1,
                "prize" => 2
            ],
            [
                "min" => 2,
                "max" => 3,
                "prize" => 5
            ]
        ]);

        $u1 = User::factory()->create();

        $referred = $u1->referredBy()->create([
            "id" => Str::uuid()->toString(),
            "referrer_id" => $user->id,
            "prize" => Referral::newRefPrize()
        ]);

        $this->assertEquals(2, Referral::redeem($referred->id));

        Event::assertDispatched(ReferralRedeemed::class);
    }

    public function test_redeeming_non_owned_referral_throws()
    {
        $this->seed();
        $this->actingAs($user = User::factory()->create());
        Referral::getOrCreate();

        $u1 = User::factory()->create();
        $referred = $u1->referredBy()->create([
            "id" => Str::uuid()->toString(),
            "referrer_id" => $user->id,
            "prize" => Referral::newRefPrize()
        ]);

        $this->actingAs($user = User::factory()->create());
        Referral::getOrCreate();

        $this->expectExceptionMessage(config("referral.error_codes.REFERRED_USER_NOT_FOUND.message"));
        Referral::redeem($referred->id);
    }

    public function test_redeeming_prizes_more_than_once_throws()
    {
        $this->seed();
        Event::fake();

        $this->actingAs($user = User::factory()->create());
        Referral::getOrCreate();

        $u1 = User::factory()->create();

        $referred = $u1->referredBy()->create([
            "id" => Str::uuid()->toString(),
            "referrer_id" => $user->id,
            "prize" => Referral::newRefPrize()
        ]);

        $this->assertEquals(2, Referral::redeem($referred->id));

        $this->expectExceptionMessage(config("referral.error_codes.REFERRED_USER_ALREADY_REDEEMED.message"));
        Referral::redeem($referred->id);
    }

    public function test_finds_referral_in_actions()
    {
        $this->seed();
        Event::fake();

        /** @var User $user */
        $this->actingAs($user = User::factory()->create());
        $ref = Referral::getOrCreate();

        /** @var User $u1 */
        $this->actingAs($u1 = User::factory()->create());
        session()->put("referral_code", $ref);

        Referral::check();

        $this->assertEquals($user->id, $u1->referredBy->referrer->id);
        $this->assertEquals($u1->id, $u1->referredBy->referred->id);

        Event::assertDispatched(NewReferralReceived::class);
    }

    public function test_providing_invalid_referral_dont_emit_event()
    {
        $this->seed();
        Event::fake();

        /** @var User $user */
        $this->actingAs($user = User::factory()->create());
        session()->put("referral_code", Str::uuid()->toString());

        Referral::check();
        Event::assertNotDispatched(NewReferralReceived::class);
    }
}
