<?php
/*
 * Copyright (c) {{$year}} - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, {{$year}}
 */

namespace {{$namespace}}\Http\Controllers;

use {{$namespace}}\Facades\Referral;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Inertia\Response;
use Inertia\ResponseFactory;

class {{$studly}}Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response|ResponseFactory
     */
    public function index(): Response|ResponseFactory
    {
        return inertia('{{$studly}}::index');
    }

    /**
     * Stores ___.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            "___" => "required|string"
        ]);

        // ___
    }

    /**
     * ___.
     *
     * @return JsonResponse
     */
    public function sample(): JsonResponse
    {
        // ___
    }
}
