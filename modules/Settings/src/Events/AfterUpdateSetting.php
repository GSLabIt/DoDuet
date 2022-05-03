<?php
/*
* Copyright (c) 2022 - Do Group LLC - All Right Reserved.
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
*/

namespace Doinc\Modules\Settings\Events;

use Doinc\Modules\Settings\Models\DTOs\Setting;
use Doinc\Modules\Settings\Models\UserSettings;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AfterUpdateSetting
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
    * Create a new event instance.
    *
    * @return  void
    */
    public function __construct(
        public bool $success,
        public UserSettings $user_setting,
        public Setting $dto
    )
    {}
}
