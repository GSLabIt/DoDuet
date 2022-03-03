{!! $opening_tag !!}
/*
 * Copyright (c) {{$year}} - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, {{$year}}
 */

namespace {{$namespace}}\Enums;

enum {{$studly}}Routes: string
{
    case RENDER_INDEX = "authenticated.{{$snake}}.render.index";

    case GET_SAMPLE = "authenticated.{{$snake}}.get.sample";
    case GET_SAMPLE_1 = "authenticated.{{$snake}}.get.sample1";

    case PUT_SAMPLE = "authenticated.{{$snake}}.put.sample";
    case PUT_SAMPLE_1 = "authenticated.{{$snake}}.put.sample1";

    case PATCH_SAMPLE = "authenticated.{{$snake}}.patch.sample";
    case PATCH_SAMPLE_1 = "authenticated.{{$snake}}.patch.sample1";

    case DELETE_SAMPLE = "authenticated.{{$snake}}.delete.sample";
    case DELETE_SAMPLE_1 = "authenticated.{{$snake}}.delete.sample1";

    case POST_PUBLIC_SAMPLE = "public.{{$snake}}.post.sample";
    case POST_PUBLIC_SAMPLE_1 = "public.{{$snake}}.post.sample1";
}
