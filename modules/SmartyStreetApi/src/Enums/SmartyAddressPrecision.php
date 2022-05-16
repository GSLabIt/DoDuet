<?php

namespace Doinc\Modules\SmartyStreetApi\Enums;

enum SmartyAddressPrecision: string
{
    /**
     * None of the address is verified.
     */
    case NONE = "None";

    /**
     * Address is only verified down to the administrative area (i.e., region or province).
     */
    case ADMINISTRATIVEAREA = "AdministrativeArea";

    /**
     * Address is only verified down to the locality (i.e., city).
     */
    case LOCALITY = "Locality";

    /**
     * Address is only verified down to the thoroughfare (i.e., street).
     */
    case THOROUGHFARE = "Thoroughfare";

    /**
     * Address is verified down to the premise (i.e., building).
     */
    case PREMISE = "Premise";

    /**
     * Address is verified down to the delivery point (i.e., mailbox or sub-building).
     */
    case DELIVERY_POINT = "DeliveryPoint";
}
