<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\SmartyStreetApi\Enums;

enum SmartyStreetApiRoutes: string
{
    case RENDER_INDEX = "authenticated.a_p_i_smarty_street.render.index";

    case GET_SAMPLE = "authenticated.a_p_i_smarty_street.get.sample";
    case GET_SAMPLE_1 = "authenticated.a_p_i_smarty_street.get.sample1";

    case PUT_SAMPLE = "authenticated.a_p_i_smarty_street.put.sample";
    case PUT_SAMPLE_1 = "authenticated.a_p_i_smarty_street.put.sample1";

    case PATCH_SAMPLE = "authenticated.a_p_i_smarty_street.patch.sample";
    case PATCH_SAMPLE_1 = "authenticated.a_p_i_smarty_street.patch.sample1";

    case DELETE_SAMPLE = "authenticated.a_p_i_smarty_street.delete.sample";
    case DELETE_SAMPLE_1 = "authenticated.a_p_i_smarty_street.delete.sample1";

    case POST_PUBLIC_SAMPLE = "public.a_p_i_smarty_street.post.sample";
    case POST_PUBLIC_SAMPLE_1 = "public.a_p_i_smarty_street.post.sample1";
}
