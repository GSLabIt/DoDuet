<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Settings\Models\DTOs;

use JessArcher\CastableDataTransferObject\CastableDataTransferObject;

abstract class Setting extends CastableDataTransferObject
{
    protected string $group = "";
    protected string $name = "";

    public function qualifySetting(string $group, string $name) {
        $this->group = $group;
        $this->name = $name;
    }

    public function qualifiedName(): string {
        return (!empty($this->group) ? "$this->group." : "") . $this->name;
    }
}
