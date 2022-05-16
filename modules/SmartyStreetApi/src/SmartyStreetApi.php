<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\SmartyStreetApi;

class SmartyStreetApi
{
    /**
     * Access the smarty international functionalities
     *
     * @return SmartyInternational
     */
    public function international(): SmartyInternational
    {
        return (new SmartyInternational())->init(
            config("api_smarty_street.auth_id"),
            config("api_smarty_street.auth_token")
        );
    }
}
