<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class LandingPageController extends Controller
{
    /**
     * Main public page of the website
     * @return Response|ResponseFactory
     */
    public function index(): Response|ResponseFactory
    {
        return inertia("Welcome", [

        ]);
    }
}
