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
class SmartyComponentsChangesDTO extends CastableDataTransferObject
{
    /**
     * The ISO 3166-1 alpha-3 country code.
     * See our full listing for details.
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $country_iso_3;

    /**
     * The most common administrative division within a country
     * (e.g., province in Canada)
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $administrative_area;

    /**
     * The largest administrative division within a country
     * (e.g., region in France)
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $super_administrative_area;

    /**
     * The smallest administrative division within a country
     * (e.g., county in Germany)
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $sub_administrative_area;

    /**
     * Within a country, this is the most common population center.
     * (e.g., city in Chile)
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $locality;

    /**
     * If there is additional information about the locality, it will be here.
     * (e.g., neighborhood in Turkey)
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $dependent_locality;

    /**
     * If the dependent_locality has a name, you'll find it here.
     * (E.g., the dependent_locality "Dong Cheng Qu" is named "Dong Cheng.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $dependent_locality_name;

    /**
     * If there is additional information about the dependent_locality, you'll find it here.
     * (e.g., village in the United Kingdom)
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $double_dependent_locality;

    /**
     * The complete postal code for the delivery point
     * (e.g., V6G1V9 in Canada)
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $postal_code;

    /**
     * Primary postal code information
     * (e.g., 90210 in the United States)
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $postal_code_short;

    /**
     * Secondary postal code information
     * (e.g., 3425 in the United States)
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $postal_code_extra;

    /**
     * Alphanumeric code pertaining to an individual location
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $premise;

    /**
     * Extra information about the premise that is not necessarily authoritative but might still be useful
     * (E.g., in a French address, 25 bis rue Emile Zola, 91190 Gif Sur Yvette, France, the premise number could be
     * followed by the word "bis" which would be considered premise_extra data.)
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $premise_extra;

    /**
     * The alphanumeric component of the premise field
     * (E.g., if premise contains "Plot 7/7A" premise_number would contain "7/7A.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $premise_number;

    /**
     * The premise type component of the premise field
     * (E.g., if premise contains "Plot 7/7A" premise_type would contain "Plot.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $premise_type;

    /**
     * All thoroughfare components combined
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $thoroughfare;

    /**
     * The directional prefix component of the thoroughfare
     * (E.g., if thoroughfare contains "N Main St" thoroughfare_predirection would contain "N."
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $thoroughfare_predirection;

    /**
     * The directional suffix component of the thoroughfare
     * (E.g., if thoroughfare contains "Main St N" thoroughfare_postdirection would contain "N.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $thoroughfare_postdirection;

    /**
     * The name component of the thoroughfare
     * (E.g., if thoroughfare contains "Main St" thoroughfare_name would contain "Main.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $thoroughfare_name;

    /**
     * The trailing thoroughfare type component of the thoroughfare
     * (E.g., if thoroughfare contains "N Main St" thoroughfare_trailing_type would contain "St.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $thoroughfare_trailing_type;

    /**
     * The leading thoroughfare type component of the thoroughfare
     * (E.g., if thoroughfare contains "Rue De La Gare" thoroughfare_leading_type would contain "Rue.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $thoroughfare_type;

    /**
     * All of the dependent thoroughfare components combined
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $dependent_thoroughfare;

    /**
     * The directional prefix component of the dependent_thoroughfare
     * (E.g., if dependent_thoroughfare contains "N Main St" dependent_thoroughfare_predirection would contain "N.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $dependent_thoroughfare_predirection;

    /**
     * The directional suffix component of the dependent_thoroughfare
     * (E.g., if dependent_thoroughfare contains "Main St N" dependent_thoroughfare_postdirection would contain "N.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $dependent_thoroughfare_postdirection;

    /**
     * The name component of the dependent_thoroughfare
     * (E.g., if dependent_thoroughfare contains "N Main St" dependent_thoroughfare_name would contain "Main.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $dependent_thoroughfare_name;

    /**
     * The trailing dependent_thoroughfare type component of the dependent_thoroughfare
     * (E.g., if dependent_thoroughfare contains "N Main St" dependent_thoroughfare_trailing_type would contain "St.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $dependent_thoroughfare_trailing_type;

    /**
     * The leading thoroughfare type component of the dependent_thoroughfare field
     * (E.g., if dependent_thoroughfare contains "Rue De La Gare" dependent_thoroughfare_type would contain "Rue.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $dependent_thoroughfare_type;

    /**
     * The descriptive name that identifies an individual location, if one exists
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $building;

    /**
     * The leading building type component of the building
     * (E.g., if building contains "Bloc C" building_leading_type would contain "Bloc.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $building_leading_type;

    /**
     * The name component of the building
     * (E.g., if building contains "Westminster House" building_name would contain "Westminster.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $building_name;

    /**
     * The trailing building type component of the building
     * (E.g., if building contains "Westminster House" building_trailing_type would contain "House.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $building_trailing_type;

    /**
     * All sub_building components combined
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $sub_building;

    /**
     * The leading sub-building type of the sub_building
     * (E.g., if sub_building contains "Flat 1" sub_building_type would contain "Flat.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $sub_building_type;

    /**
     * The alphanumeric component of the sub_building
     * (E.g., if sub_building contains "Flat 1" sub_building_number would contain "1.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $sub_building_number;

    /**
     * The descriptive name component of the sub_building
     * (E.g., if sub_building contains "Basement Flat" sub_building_name would contain "Basement.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $sub_building_name;

    /**
     * All post_box Post Office Box components combined
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $post_box;

    /**
     * The type component of the post_box
     * (E.g., if post_box contains "PO Box 1234" post_box_type would contain "PO Box.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $post_box_type;

    /**
     * The alphanumeric component of the postbox
     * (E.g., if post_box contains "PO Box 1234" post_box_number would contain "1234.")
     * @var SmartyChangesScale|null
     */
    public ?SmartyChangesScale $post_box_number;
}
