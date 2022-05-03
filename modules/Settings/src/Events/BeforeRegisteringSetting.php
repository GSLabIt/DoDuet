<?php
/*
* Copyright (c) 2022 - Do Group LLC - All Right Reserved.
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
*/

namespace Doinc\Modules\Settings\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BeforeRegisteringSetting
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
    * Create a new event instance.
    *
    * @return  void
    */
    public function __construct(
        public string $name,
        public string $class,
        public ?string $default_value
    )
    {}
}
