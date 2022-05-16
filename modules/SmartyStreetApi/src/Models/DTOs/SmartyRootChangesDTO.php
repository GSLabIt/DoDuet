<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\SmartyStreetApi\Models\DTOs;

use Doinc\Modules\SmartyStreetApi\Enums\SmartyChangesScale;
use JessArcher\CastableDataTransferObject\CastableDataTransferObject;
use Spatie\DataTransferObject\Attributes\DefaultCast;

#[
    DefaultCast(SmartyChangesScale::class, CasterSmartyChangesScale::class)
]
class SmartyRootChangesDTO extends CastableDataTransferObject
{
    /**
     * If present, the degree of change to the name of the recipient, firm, or company at this address.
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $organization;

    /**
     * If present, these fields show the degree of change to each of the address lines.
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $address1;
    public ?SmartyChangesScale $address2;
    public ?SmartyChangesScale $address3;
    public ?SmartyChangesScale $address4;
    public ?SmartyChangesScale $address5;
    public ?SmartyChangesScale $address6;
    public ?SmartyChangesScale $address7;
    public ?SmartyChangesScale $address8;
    public ?SmartyChangesScale $address9;
    public ?SmartyChangesScale $address10;
    public ?SmartyChangesScale $address11;
    public ?SmartyChangesScale $address12;

    /**
     * Contains the various basic elements of the address.
     * @var SmartyComponentsChangesDTO
     */
    public SmartyComponentsChangesDTO $components;
}
