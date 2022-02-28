<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Modules\Referral\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Inertia\Response;
use Inertia\ResponseFactory;

class ReferralController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response|ResponseFactory
     */
    public function index(): Response|ResponseFactory
    {
        return inertia('Referral::index');
    }

    /**
     * Generates the referral url for the current user.
     *
     * @return JsonResponse
     */
    public function url(): JsonResponse
    {
        return response()->json([
            "url" => ReferralStaticController::url()
        ]);
    }

    /**
     * Get the prize that will be received by the referrer for the next referred user.
     *
     * @return JsonResponse
     */
    public function newRefPrize(): JsonResponse
    {
        return response()->json([
            "prize" => ReferralStaticController::newRefPrize()
        ]);
    }

    /**
     * Get the prize that will be received by the referrer for the next referred user.
     *
     * @return JsonResponse
     */
    public function totalRefPrize(): JsonResponse
    {
        return response()->json([
            "prize" => ReferralStaticController::totalRefPrize()
        ]);
    }

    /**
     * Get the prize that will be received by the referrer for the next referred user.
     *
     * @param string $referred_id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function redeem(string $referred_id): JsonResponse
    {
        return response()->json([
            "prize" => ReferralStaticController::redeem($referred_id)
        ]);
    }

    /**
     * Get the prize that will be received by the referrer for the next referred user.
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function redeemAll(): JsonResponse
    {
        return response()->json([
            "prize" => ReferralStaticController::redeemAll()
        ]);
    }
}
