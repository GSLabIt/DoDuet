<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\TelnyxExtendedApi\Enums;

enum TelnyxExtendedApiRoutes: string
{
    case RENDER_INDEX = "authenticated.telnyx_extended_api.render.index";

    case GET_SAMPLE = "authenticated.telnyx_extended_api.get.sample";
    case GET_SAMPLE_1 = "authenticated.telnyx_extended_api.get.sample1";

    case PUT_SAMPLE = "authenticated.telnyx_extended_api.put.sample";
    case PUT_SAMPLE_1 = "authenticated.telnyx_extended_api.put.sample1";

    case PATCH_SAMPLE = "authenticated.telnyx_extended_api.patch.sample";
    case PATCH_SAMPLE_1 = "authenticated.telnyx_extended_api.patch.sample1";

    case DELETE_SAMPLE = "authenticated.telnyx_extended_api.delete.sample";
    case DELETE_SAMPLE_1 = "authenticated.telnyx_extended_api.delete.sample1";

    case POST_PUBLIC_SAMPLE = "public.telnyx_extended_api.post.sample";
    case POST_PUBLIC_SAMPLE_1 = "public.telnyx_extended_api.post.sample1";
}
