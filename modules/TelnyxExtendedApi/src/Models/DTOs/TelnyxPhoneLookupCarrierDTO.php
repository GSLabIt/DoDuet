<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\TelnyxExtendedApi\Models\DTOs;

use Doinc\Modules\TelnyxExtendedApi\Enums\TelnyxPhoneType;
use JessArcher\CastableDataTransferObject\CastableDataTransferObject;
use Spatie\DataTransferObject\Attributes\DefaultCast;

#[
    DefaultCast(TelnyxPhoneType::class, CasterTelnyxPhoneType::class)
]
class TelnyxPhoneLookupCarrierDTO extends CastableDataTransferObject
{
    /**
     * Region code that matches the specific country calling code if the requested phone number type is mobile
     * @var string|null
     */
    public ?string $mobile_country_code;

    /**
     * National destination code (NDC), with a 0 prefix, if an NDC is found and the requested phone number type
     * is mobile
     * @var string|null
     */
    public ?string $mobile_network_code;

    /**
     * SPID (Service Provider ID) name, if the requested phone number has been ported; otherwise, the name of carrier
     * who owns the phone number block
     * @var string|null
     */
    public ?string $name;

    /**
     * A phone number type that identifies the type of service associated with the requested phone number
     * @var TelnyxPhoneType|null
     */
    public ?TelnyxPhoneType $type;
}
