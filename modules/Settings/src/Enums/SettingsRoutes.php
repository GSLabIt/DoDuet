<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Settings\Enums;

enum SettingsRoutes: string
{
    case RENDER_INDEX = "authenticated.settings.render.index";

    case GET_SAMPLE = "authenticated.settings.get.sample";
    case GET_SAMPLE_1 = "authenticated.settings.get.sample1";

    case PUT_SAMPLE = "authenticated.settings.put.sample";
    case PUT_SAMPLE_1 = "authenticated.settings.put.sample1";

    case PATCH_SAMPLE = "authenticated.settings.patch.sample";
    case PATCH_SAMPLE_1 = "authenticated.settings.patch.sample1";

    case DELETE_SAMPLE = "authenticated.settings.delete.sample";
    case DELETE_SAMPLE_1 = "authenticated.settings.delete.sample1";

    case POST_PUBLIC_SAMPLE = "public.settings.post.sample";
    case POST_PUBLIC_SAMPLE_1 = "public.settings.post.sample1";
}
