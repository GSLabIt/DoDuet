<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace App\DTOs;

use Doinc\Modules\Settings\Models\DTOs\Setting;

class SettingNineRandomTracks extends Setting
{
    public string $challenge_id;

    /** @var string[] $track_ids */
    public array $track_ids;
    public int $listened;
}
