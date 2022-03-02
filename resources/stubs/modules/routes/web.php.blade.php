<?php
/*
 * Copyright (c) {{$year}} - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, {{$year}}
 */

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use {{$namespace}}\Enums\{{$studly}}Routes;
use {{$namespace}}\Http\Controllers\{{$studly}}Controller;

// +-------------------+
// |   AUTHENTICATED   |
// +-------------------+

Route::middleware(['auth:sanctum', 'verified'])
    ->prefix('{{$snake}}')
    ->group(function () {
        // +---------+
        // |   GET   |
        // +---------+

        Route::get(
            '/',
            [{{$studly}}Controller::class, "index"]
        )->name({{$studly}}Routes::RENDER_INDEX->value);

        Route::get(
            "/sample",
            [{{$studly}}Controller::class, "sample"]
        )->name({{$studly}}Routes::GET_SAMPLE->value);

        // +---------+
        // |   PUT   |
        // +---------+

        Route::put(
            "/sample",
            [{{$studly}}Controller::class, "sample"]
        )->name({{$studly}}Routes::PUT_SAMPLE->value);

        // +-----------+
        // |   PATCH   |
        // +-----------+

        Route::patch(
            "/sample",
            [{{$studly}}Controller::class, "sample"]
        )->name({{$studly}}Routes::PATCH_SAMPLE->value);

        // +------------+
        // |   DELETE   |
        // +------------+

        Route::delete(
            "/sample",
            [{{$studly}}Controller::class, "sample"]
        )->name({{$studly}}Routes::DELETE_SAMPLE->value);
    });

// +------------+
// |   PUBLIC   |
// +------------+

Route::prefix("{{$snake}}")
    ->group(function() {
        // +----------+
        // |   POST   |
        // +----------+

        Route::post(
            '/',
            [{{$studly}}Controller::class, "sample"]
        )->name({{$studly}}Routes::POST_PUBLIC_SAMPLE->value);
    });
