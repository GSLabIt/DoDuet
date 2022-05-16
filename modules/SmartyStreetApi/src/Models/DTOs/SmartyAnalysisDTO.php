<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\SmartyStreetApi\Models\DTOs;

use Doinc\Modules\SmartyStreetApi\Enums\SmartyAddressPrecision;
use Doinc\Modules\SmartyStreetApi\Enums\SmartyVerificationStatus;
use JessArcher\CastableDataTransferObject\CastableDataTransferObject;
use Spatie\DataTransferObject\Attributes\DefaultCast;

#[
    DefaultCast(SmartyVerificationStatus::class, CasterSmartyVerificationStatus::class),
    DefaultCast(SmartyAddressPrecision::class, CasterSmartyAddressPrecision::class)
]
class SmartyAnalysisDTO extends CastableDataTransferObject
{
    /**
     * Indicates the verification status of the address.
     * None — Not verified. The output fields will contain the input data.
     * Partial — Partial match to a single address record. Better input might result in a better match. If the address
     * precision is 'Premise,' the address is probably deliverable, though it may lack an apartment or suite number.
     * Ambiguous — Multiple matching addresses found. Each candidate address will have its own precision level. A common
     * "ambiguous" scenario is that the output will contain two versions of the same address — one with a company name
     * and one without.
     * Verified — The address was verified, at the indicated precision level. NOTE: A verification_status of 'Verified'
     * does not necessarily indicate that the address is valid or deliverable. For the address to be valid, its address
     * precision must be 'Premise' or 'DeliveryPoint.'
     * @var SmartyVerificationStatus
     */
    public SmartyVerificationStatus $verification_status;

    /**
     * Indicates the precision level to which the address is verified.
     * None — None of the address is verified.
     * AdministrativeArea — Address is only verified down to the administrative area (i.e., region or province).
     * Locality — Address is only verified down to the locality (i.e., city).
     * Thoroughfare — Address is only verified down to the thoroughfare (i.e., street).
     * Premise — Address is verified down to the premise (i.e., building).
     * DeliveryPoint — Address is verified down to the delivery point (i.e., mailbox or subbuilding).
     * @var SmartyAddressPrecision
     */
    public SmartyAddressPrecision $address_precision;

    /**
     * Indicates the best address_precision available for the input country.
     * @var SmartyAddressPrecision|null
     */
    public ?SmartyAddressPrecision $max_address_precision;

    /**
     * Contains a collection of address components paired with values which specify the difference between corresponding
     * input/lookup and output/candidate data. See the explanation of possible Changes values below.
     * @var SmartyRootChangesDTO
     */
    public SmartyRootChangesDTO $changes;
}
