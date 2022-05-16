<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\TelnyxExtendedApi\Models\DTOs;

use Doinc\Modules\SmartyStreetApi\Enums\CountriesISO2;
use Doinc\Modules\SmartyStreetApi\Models\DTOs\CasterSmartyCountriesISO2;
use JessArcher\CastableDataTransferObject\CastableDataTransferObject;
use Spatie\DataTransferObject\Attributes\DefaultCast;

#[
    DefaultCast(CountriesISO2::class, CasterSmartyCountriesISO2::class)
]
class TelnyxPhoneLookupDataDTO extends CastableDataTransferObject
{
    /**
     * @var TelnyxPhoneLookupCallerDTO|null
     */
    public ?TelnyxPhoneLookupCallerDTO $caller_name;

    /**
     * @var TelnyxPhoneLookupCarrierDTO|null
     */
    public ?TelnyxPhoneLookupCarrierDTO $carrier;

    /**
     * Region code that matches the specific country calling code
     * @var CountriesISO2|null
     */
    public ?CountriesISO2 $country_code;

    /**
     * Hyphen-separated national number, preceded by the national destination code (NDC), with a 0 prefix, if an NDC
     * is found
     * @var string
     */
    public string $national_format;

    /**
     * E164-formatted phone number
     * @var string
     */
    public string $phone_number;

    /**
     * @var array|null
     */
    public ?array $portability;

    /**
     * Identifies the type of record
     * @var string
     */
    public string $record_type;
}
