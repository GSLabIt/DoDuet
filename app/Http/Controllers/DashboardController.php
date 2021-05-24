<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Response;
use Inertia\ResponseFactory;

class DashboardController extends Controller
{
    /**
     * Dashboard main page
     * @return Response|ResponseFactory
     */
    public function index(): Response|ResponseFactory
    {
        return inertia("Dashboard", [

        ]);
    }

    public function walletRequired(): Response|ResponseFactory
    {
        return inertia("User/" . directoryFromClass(__CLASS__, false) . "/" . Str::camel(__FUNCTION__), []);
    }
}
