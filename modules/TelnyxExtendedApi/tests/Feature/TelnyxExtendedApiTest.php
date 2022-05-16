<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\TelnyxExtendedApi\Tests\Feature;

use Doinc\Modules\SmartyStreetApi\Enums\CountriesISO2;
use Doinc\Modules\TelnyxExtendedApi\Events\NumberLookup;
use Doinc\Modules\TelnyxExtendedApi\Exceptions\InvalidNumber;
use Doinc\Modules\TelnyxExtendedApi\Facades\TelnyxExtendedApi;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TelnyxExtendedApiTest extends TestCase
{
    public function test_real_number_is_valid()
    {
        Event::fake();
        $response = TelnyxExtendedApi::numberLookup("+393205612210");

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->hasCarrier());
        $this->assertFalse($response->hasCaller());
        $this->assertEquals(CountriesISO2::IT, $response->data->country_code);
        $this->assertEquals("+393205612210", $response->data->phone_number);
        $this->assertEquals("320 561 2210", $response->data->national_format);

        Event::assertDispatched(NumberLookup::class);
    }

    public function test_strangely_formatted_real_number_is_valid()
    {
        Event::fake();
        $response = TelnyxExtendedApi::numberLookup("+39 320 56 12 21 0");

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->hasCarrier());
        $this->assertFalse($response->hasCaller());
        $this->assertEquals(CountriesISO2::IT, $response->data->country_code);
        $this->assertEquals("+393205612210", $response->data->phone_number);
        $this->assertEquals("320 561 2210", $response->data->national_format);

        Event::assertDispatched(NumberLookup::class);
    }

    public function test_toll_free_number_is_invalid()
    {
        Event::fake();
        $response = TelnyxExtendedApi::numberLookup("+39 800 595 459", with_caller: true);

        $this->assertFalse($response->isValid());
        $this->assertTrue($response->hasCarrier());
        $this->assertFalse($response->hasCaller());
        $this->assertEquals(CountriesISO2::IT, $response->data->country_code);
        $this->assertEquals("+39800595459", $response->data->phone_number);
        $this->assertEquals("800 595 459", $response->data->national_format);

        Event::assertDispatched(NumberLookup::class);
    }

    public function test_invalid_number_throws()
    {
        Event::fake();

        $this->expectException(InvalidNumber::class);
        TelnyxExtendedApi::numberLookup("0000 800 595 459", with_caller: true);

        Event::assertNotDispatched(NumberLookup::class);
    }
}
