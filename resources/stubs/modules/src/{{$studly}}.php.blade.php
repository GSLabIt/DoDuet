{!! $opening_tag !!}
/*
 * Copyright (c) {{$year}} - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, {{$year}}
 */

namespace {{$namespace}};

use App\Models\User;
use {{$namespace}}\Enums\{{$capitalized}}Routes;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class {{$studly}}
{
    /**
     * ___.
     *
     * @return string
     */
    public function sample(): string
    {
        /**@var User $user */
        $user = auth()->user();

        return $user->id;
    }
}
