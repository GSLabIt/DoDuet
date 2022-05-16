<?php
/*
* Copyright (c) 2022 - Do Group LLC - All Right Reserved.
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
*/

namespace Doinc\Modules\TelnyxExtendedApi\Events;

use Doinc\Modules\TelnyxExtendedApi\Models\DTOs\TelnyxPhoneLookupRootDTO;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NumberLookup
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
    * Create a new event instance.
    *
    * @return  void
    */
    public function __construct(
        public string $phone_number,
        public TelnyxPhoneLookupRootDTO $dto
    )
    {}
}
