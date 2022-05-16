<?php

namespace Doinc\Modules\SmartyStreetApi\Enums;

enum SmartyVerificationStatus: string
{
    /**
     * Not verified. The output fields will contain the input data.
     */
    case NONE = "None";

    /**
     * Partial match to a single address record. Better input might result in a better match. If the address
     * precision is 'Premise,' the address is probably deliverable, though it may lack an apartment or suite number.
     */
    case PARTIAL = "Partial";

    /**
     * Ambiguous — Multiple matching addresses found. Each candidate address will have its own precision level. A common
     * "ambiguous" scenario is that the output will contain two versions of the same address — one with a company name
     * and one without.
     */
    case AMBIGUOUS = "Ambiguous";

    /**
     * The address was verified, at the indicated precision level. NOTE: A verification_status of 'Verified'
     * does not necessarily indicate that the address is valid or deliverable. For the address to be valid, its address
     * precision must be 'Premise' or 'DeliveryPoint.'
     */
    case VERIFIED = "Verified";
}
