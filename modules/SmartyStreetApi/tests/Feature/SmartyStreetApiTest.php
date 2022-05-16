<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\SmartyStreetApi\Tests\Feature;

use Doinc\Modules\SmartyStreetApi\Enums\CountriesISO3;
use Doinc\Modules\SmartyStreetApi\Enums\SmartyAddressPrecision;
use Doinc\Modules\SmartyStreetApi\Enums\SmartyChangesScale;
use Doinc\Modules\SmartyStreetApi\Enums\SmartyVerificationStatus;
use Doinc\Modules\SmartyStreetApi\Facades\SmartyStreetApi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SmartyStreetApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();
    }

    protected function validSampleJSON()
    {
        Http::fakeSequence()
            ->push(json_decode(file_get_contents(__DIR__ . "/../assets/sample_0.json")))
            ->whenEmpty(Http::response());
    }

    protected function partialSampleJSON()
    {
        Http::fakeSequence()
            ->push(json_decode(file_get_contents(__DIR__ . "/../assets/sample_1.json")))
            ->whenEmpty(Http::response());
    }

    protected function incorrectSampleJSON()
    {
        Http::fakeSequence()
            ->push(json_decode(file_get_contents(__DIR__ . "/../assets/sample_2.json")))
            ->whenEmpty(Http::response());
    }

    public function test_correct_info_inserted()
    {
        $this->validSampleJSON();

        $response = SmartyStreetApi::international()
            ->verify(
                CountriesISO3::ITA,
                "via venti settembre 54",
                "verona",
                "verona",
                "37129"
            );

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->hasChanges());

        $this->assertEquals("Via Venti Settembre 54", $response->address1);
        $this->assertEquals("37129 Verona VR", $response->address2);
        foreach ([
                     $response->address3,
                     $response->address4,
                     $response->address5,
                     $response->address6,
                     $response->address7,
                     $response->address8,
                     $response->address9,
                     $response->address10,
                     $response->address11,
                     $response->address12,
                 ] as $addr) {
            $this->assertNull($addr);
        }
        $this->assertEquals("Veneto", $response->components->super_administrative_area);
        $this->assertEquals("VR", $response->components->administrative_area);
        $this->assertEquals(CountriesISO3::ITA, $response->components->country_iso_3);
        $this->assertEquals("Verona", $response->components->locality);
        $this->assertEquals("37129", $response->components->postal_code);
        $this->assertEquals("37129", $response->components->postal_code_short);
        $this->assertEquals("54", $response->components->premise);
        $this->assertEquals("54", $response->components->premise_number);
        $this->assertEquals("Via Venti Settembre", $response->components->thoroughfare);
        $this->assertEquals("Venti Settembre", $response->components->thoroughfare_name);
        $this->assertEquals("Via", $response->components->thoroughfare_type);
        $this->assertEquals("thoroughfare premise|postal_code locality administrative_area", $response->metadata->address_format);
        $this->assertEquals(SmartyVerificationStatus::VERIFIED, $response->analysis->verification_status);
        $this->assertEquals(SmartyAddressPrecision::PREMISE, $response->analysis->address_precision);
        $this->assertEquals(SmartyAddressPrecision::PREMISE, $response->analysis->max_address_precision);
        $this->assertEquals(SmartyChangesScale::VERIFIED_ALIAS_CHANGE, $response->analysis->changes->address1);
        $this->assertEquals(SmartyChangesScale::VERIFIED_ALIAS_CHANGE, $response->analysis->changes->address2);
        $this->assertEquals(SmartyChangesScale::ADDED, $response->analysis->changes->components->super_administrative_area);
        $this->assertEquals(SmartyChangesScale::VERIFIED_ALIAS_CHANGE, $response->analysis->changes->components->administrative_area);
        $this->assertEquals(SmartyChangesScale::ADDED, $response->analysis->changes->components->country_iso_3);
        $this->assertEquals(SmartyChangesScale::VERIFIED_NO_CHANGE, $response->analysis->changes->components->locality);
        $this->assertEquals(SmartyChangesScale::VERIFIED_LARGE_CHANGE, $response->analysis->changes->components->postal_code);
        $this->assertEquals(SmartyChangesScale::VERIFIED_LARGE_CHANGE, $response->analysis->changes->components->postal_code_short);
        $this->assertEquals(SmartyChangesScale::VERIFIED_NO_CHANGE, $response->analysis->changes->components->premise);
        $this->assertEquals(SmartyChangesScale::VERIFIED_NO_CHANGE, $response->analysis->changes->components->premise_number);
        $this->assertEquals(SmartyChangesScale::VERIFIED_NO_CHANGE, $response->analysis->changes->components->thoroughfare);
        $this->assertEquals(SmartyChangesScale::IDENTIFIED_NO_CHANGE, $response->analysis->changes->components->thoroughfare_name);
        $this->assertEquals(SmartyChangesScale::IDENTIFIED_NO_CHANGE, $response->analysis->changes->components->thoroughfare_type);
    }

    public function test_partial_info_inserted()
    {
        $this->partialSampleJSON();

        $response = SmartyStreetApi::international()
            ->verify(
                CountriesISO3::ITA,
                "via venti settembre 54",
                "verona",
                "verona",
                "37129"
            );

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->hasChanges());

        $this->assertEquals("Via Venti Settembre 54", $response->address1);
        $this->assertEquals("37129 Verona VR", $response->address2);
        foreach ([
                     $response->address3,
                     $response->address4,
                     $response->address5,
                     $response->address6,
                     $response->address7,
                     $response->address8,
                     $response->address9,
                     $response->address10,
                     $response->address11,
                     $response->address12,
                 ] as $addr) {
            $this->assertNull($addr);
        }
        $this->assertEquals("Veneto", $response->components->super_administrative_area);
        $this->assertEquals("VR", $response->components->administrative_area);
        $this->assertEquals(CountriesISO3::ITA, $response->components->country_iso_3);
        $this->assertEquals("Verona", $response->components->locality);
        $this->assertEquals("37129", $response->components->postal_code);
        $this->assertEquals("37129", $response->components->postal_code_short);
        $this->assertEquals("54", $response->components->premise);
        $this->assertEquals("54", $response->components->premise_number);
        $this->assertEquals("Via Venti Settembre", $response->components->thoroughfare);
        $this->assertEquals("thoroughfare premise|postal_code locality administrative_area", $response->metadata->address_format);
        $this->assertEquals(SmartyVerificationStatus::VERIFIED, $response->analysis->verification_status);
        $this->assertEquals(SmartyAddressPrecision::PREMISE, $response->analysis->address_precision);
        $this->assertEquals(SmartyAddressPrecision::PREMISE, $response->analysis->max_address_precision);
        $this->assertEquals(SmartyChangesScale::VERIFIED_ALIAS_CHANGE, $response->analysis->changes->address1);
        $this->assertEquals(SmartyChangesScale::VERIFIED_ALIAS_CHANGE, $response->analysis->changes->address2);
        $this->assertEquals(SmartyChangesScale::ADDED, $response->analysis->changes->components->super_administrative_area);
        $this->assertEquals(SmartyChangesScale::VERIFIED_ALIAS_CHANGE, $response->analysis->changes->components->administrative_area);
        $this->assertEquals(SmartyChangesScale::ADDED, $response->analysis->changes->components->country_iso_3);
        $this->assertEquals(SmartyChangesScale::VERIFIED_NO_CHANGE, $response->analysis->changes->components->locality);
        $this->assertEquals(SmartyChangesScale::VERIFIED_NO_CHANGE, $response->analysis->changes->components->postal_code);
        $this->assertEquals(SmartyChangesScale::VERIFIED_NO_CHANGE, $response->analysis->changes->components->postal_code_short);
        $this->assertEquals(SmartyChangesScale::VERIFIED_NO_CHANGE, $response->analysis->changes->components->premise);
        $this->assertEquals(SmartyChangesScale::VERIFIED_NO_CHANGE, $response->analysis->changes->components->premise_number);
        $this->assertEquals(SmartyChangesScale::VERIFIED_NO_CHANGE, $response->analysis->changes->components->thoroughfare);
    }

    public function test_wrong_info_inserted()
    {
        $this->incorrectSampleJSON();

        $response = SmartyStreetApi::international()
            ->verify(
                CountriesISO3::ITA,
                "via venti settembre 54",
                "verona",
                "verona",
                "37129"
            );

        $this->assertFalse($response->isValid());
        $this->assertFalse($response->hasChanges());
    }
}
