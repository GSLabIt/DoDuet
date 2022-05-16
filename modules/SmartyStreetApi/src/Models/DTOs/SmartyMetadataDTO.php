<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\SmartyStreetApi\Models\DTOs;

use JessArcher\CastableDataTransferObject\CastableDataTransferObject;

class SmartyMetadataDTO extends CastableDataTransferObject
{
    /**
     * The horizontal component used for geographic positioning; it is the angle between 0° (the equator) and ±90°
     * (north or south) at the poles measured in decimal degrees. It is the first value in an ordered pair of latitude,
     * longitude.
     * A negative number denotes a location south of the equator; a positive number is north.
     * Combining lat/long values enables you to pinpoint addresses on a map.
     * @var float|null
     */
    public ?float $latitude;

    /**
     * The vertical component used for geographic positioning; it is the angle between 0° (the Prime Meridian) and
     * ±180° (westward or eastward) measured in decimal degrees. It is the second number in an ordered pair of
     * (latitude, longitude).
     * A negative number indicates a location west of Greenwich, England; a positive number east.
     * Combining lat/long values enables you to pinpoint addresses on a map.
     * @var float|null
     */
    public ?float $longitude;

    /**
     * Indicates the precision level of the latitude and longitude values.
     * None — Geocode not known, possibly because address is invalid.
     * AdministrativeArea — Geocode is only accurate down to the administrative area (i.e., region or province).
     * Locality — Geocode is only accurate down to the locality (i.e., city).
     * Thoroughfare — Geocode is only accurate down to the thoroughfare (i.e., street).
     * Premise — Geocode is accurate down to the premise (i.e., building).
     * DeliveryPoint — Geocode is accurate down to the actual delivery point (i.e., mailbox or subbuilding).
     * @var string|null
     */
    public ?string $geocode_precision;

    /**
     * Indicates the best geocode_precision available for the input country.
     * @var string|null
     */
    public ?string $max_geocode_precision;

    /**
     * A template that shows where we positioned the different address components on line 1, line 2, etc.
     * (The format changes from one country to another.)
     * Due to the ever-changing nature of the underlying data, this field may contain values that are not referenced in
     * the address components.
     * Example:
     * building | premise thoroughfare | postal_code locality
     * Each "pipe" character (|) represents a line break. Following this guide, the numbered address fields would be
     * composed accordingly:
     * Address 1: building
     * Address 2: premise thoroughfare
     * Address 3: postal_code locality
     * For native languages that do not use spaces between words, the corresponding component fields will also not have
     * spaces between them.
     * The address_format field will not be present for US addresses. Here's some additional info on the composition
     * of US addresses.
     * @var string|null
     */
    public ?string $address_format;
}
